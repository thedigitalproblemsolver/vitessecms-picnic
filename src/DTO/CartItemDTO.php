<?php declare(strict_types=1);

namespace VitesseCms\Picnic\DTO;

class CartItemDTO
{
    /**
     * @var array
     */
    private $item;

    public function __construct(array $item)
    {
        $this->item = $item;
    }

    public function getDisplayPrice(): float
    {
        return $this->item['display_price'] / 100;
    }

    public function getId(): string
    {
        return $this->item['items'][0]['id'];
    }

    public function getName(): string
    {
        return $this->item['items'][0]['name'];
    }
}