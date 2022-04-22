<?php declare(strict_types=1);

namespace VitesseCms\Picnic\DTO;

use Psr\Http\Message\ResponseInterface;

class CartDTO
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
        $this->data = json_decode((string)$response->getBody(), true);
        $this->items = [];
        foreach ($this->data['items'] as $item) :
            $this->items[] = new CartItemDTO($item);
        endforeach;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function hasItems(): bool
    {
        return count($this->data['items']) > 0;
    }

    public function getCheckoutTotalPrice(): float
    {
        return $this->data['checkout_total_price']/100;
    }

    public function getItemsCount(): int
    {
        return count($this->data['items']);
    }
}