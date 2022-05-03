<?php declare(strict_types=1);

namespace VitesseCms\Picnic\Services;

use Exception;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use VitesseCms\Picnic\DTO\CartDTO;
use VitesseCms\Picnic\DTO\CategoriesDTO;
use VitesseCms\Picnic\DTO\ListsDTO;
use VitesseCms\Picnic\DTO\ProductDTO;
use VitesseCms\Picnic\DTO\SearchResultDTO;
use VitesseCms\Picnic\Enums\PicnicEnum;

class PicnicService
{
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var string[]
     */
    private $headers;

    /**
     * @var Client
     */
    private $client;

    public function __construct(
        Client  $client,
        ?string $authHeader
    )
    {
        $this->baseUrl = $this->generateBaseUrl();
        $this->client = $client;

        $this->headers = [
            'User-Agent' => 'okhttp/3.9.0',
            'Content-Type' => 'application/json; charset=UTF-8',
            PicnicEnum::AUTH_HEADER => $authHeader
        ];

        return $this;
    }

    protected function generateBaseUrl()
    {
        return 'https://storefront-prod.nl.Picnicinternational.com/api/' . 15;
    }

    /*protected function get($path)
    {
        $url = $this->baseUrl . $path;
        return json_decode((string)$this->get($url)->getBody(), true);
    }

    protected function post($path, $data)
    {
        $url = $this->baseUrl . $path;
        return json_decode((string)$this->post($url, ['json' => $data])->getBody(), true);
    }*/

    public function getUser()
    {
        return json_decode((string)$this->get('/user')->getBody(), true);
    }

    public function search(string $query): SearchResultDTO
    {
        return new SearchResultDTO($this->get('/search?search_term=' . $query));
    }

    public function getList(string $listPath, string $subList = null): ListsDTO
    {
        $path = ['/lists/', $listPath];
        if ($subList !== null):
            $path[] = '?sublist=' . $subList;
        endif;
        //https://storefront-prod.nl.picnicinternational.com/api/15/lists/promotions
        //https://storefront-prod.nl.picnicinternational.com/api/15/lists/promotions?sublist=624d52036a3ea840a1408e19

        return new ListsDTO(json_decode((string)$this->get(implode('', $path))->getBody(), true));
    }

    public function getCart(): CartDTO
    {
        return new CartDTO($this->get('/cart'));
    }

    public function addProduct(int $productId, int $count = 1): ResponseInterface
    {
        return $this->post('/cart/add_product', ['product_id' => $productId, 'count' => $count]);
    }

    public function removeProduct(int $productId, int $count = 1): ResponseInterface
    {
        return $this->post('/cart/remove_product', ['product_id' => $productId, 'count' => $count]);
    }

    public function clearCart()
    {
        return $this->post('/cart/clear');
    }

    public function getDeliverySlots()
    {
        return $this->get('/cart/delivery_slots')->getBody();
    }

    public function getDelivery($deliveryId)
    {
        $path = '/deliveries/' . $deliveryId;
        $data = [];
        return $this->post($path, $data);
    }

    public function getDeliveries($summary = false)
    {
        $data = [];
        if ($summary) {
            return $this->post('/deliveries/summary', $data);
        }
        return $this->post('/lists');
    }

    public function getCurrentDeliveries()
    {
        $data = "CURRENT";
        return $this->post('/deliveries/', $data);
    }

    public function getCategories(): CategoriesDTO
    {
        return new CategoriesDTO($this->get('/my_store?depth=0'));
    }

    public function getProduct(int $productId): ProductDTO
    {
        return new ProductDTO(json_decode((string)$this->get('/product/' . $productId)->getBody(), true)['product_details']);
    }

    public function login($username, $password): array
    {
        if ($this->headerExists('x-Picnic-auth')) {
            $this->removeHeaderByKey('x-Picnic-auth');
        }

        $url = '/user/login';
        $secret = md5(utf8_encode($password));
        $data = [
            'key' => $username,
            'secret' => $secret,
            'client_id' => 1
        ];

        $response = $this->post(
            $url,
            $data,
            ['headers' => $this->headers]
        );

        //if ($response) {
        $this->headers[PicnicEnum::AUTH_HEADER] = $response->getHeader(PicnicEnum::AUTH_HEADER);
        //}
        return $response->getHeader(PicnicEnum::AUTH_HEADER);
    }

    public function post(string $uri, $data = null, array $options = [])
    {
        $options['headers'] = $this->headers;
        if ($data !== null) :
            $options['json'] = $data;
        endif;

        $response = $this->client->request('POST', $this->baseUrl . $uri, $options);
        if ($response->getStatusCode() !== 200) {
            throw new Exception('Something went wrong');
        }

        //return json_decode((string)$response->getBody(), true);
        return $response;
    }

    public function get($uri = '', array $options = []): ResponseInterface
    {
        $options['headers'] = $this->headers;

        $response = $this->client->request('GET', $this->baseUrl . $uri, $options);
        if ($response->getStatusCode() !== 200) {
            throw new Exception('Something went wrong');
        }

        return $response;
    }

    private function removeHeaderByKey($key)
    {
        unset($this->headers[$key]);
    }

    private function headerExists($name)
    {
        return array_key_exists($name, $this->headers);
    }
}