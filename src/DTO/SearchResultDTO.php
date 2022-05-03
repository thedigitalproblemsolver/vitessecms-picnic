<?php declare(strict_types=1);

namespace VitesseCms\Picnic\DTO;

use Psr\Http\Message\ResponseInterface;

class SearchResultDTO
{

    /**
     * @var mixed
     */
    private $data;

    /**
     * @var array
     */
    private $items;

    public function __construct(ResponseInterface $response)
    {
        $this->data = json_decode((string)$response->getBody(), true)[0];
        $items = [];
        foreach ($this->data['items'] as $item):
            if (isset($item['name'])) :
                $this->items[] = new ProductDTO($item);
            endif;
        endforeach;
    }

    public function setItems(array $items): SearchResultDTO
    {
        $this->items = $items;

        return $this;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function hasItems(): bool
    {
        return count($this->items) > 0;
    }

    public function getItemsCount(): int
    {
        return count($this->items);
    }
}