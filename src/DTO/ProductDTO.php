<?php declare(strict_types=1);

namespace VitesseCms\Picnic\DTO;

use Psr\Http\Message\ResponseInterface;

class ProductDTO
{
    /**
     * @var mixed
     */
    private $data;

    /*private const ImageSizes = [
        TINY: "tiny",
        SMALL: "small",
        MEDIUM: "medium",
        LARGE: "large",
        EXTRA_LARGE: "extra-large"
    ]*/

    public function __construct(ResponseInterface $response)
    {
        $this->data = json_decode((string)$response->getBody(), true);
    }

    public function getId(): string
    {
        return $this->data['product_details']['id'];
    }

    public function getName(): string
    {
        return $this->data['product_details']['name'];
    }

    public function getDescription(): string
    {
        return $this->data['product_details']['description']??'';
    }

    public function getImageUrl(): string
    {
        return  'https://storefront-prod.nl.Picnicinternational.com/static/images/'.$this->data['product_details']['image_id'].'/large.png';
    }
}