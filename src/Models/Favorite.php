<?php declare(strict_types=1);

namespace VitesseCms\Picnic\Models;

use VitesseCms\Database\AbstractCollection;
use VitesseCms\Picnic\DTO\ProductDTO;

class Favorite extends AbstractCollection
{
    /**
     * @var int
     */
    public $picnicId;

    /**
     * @var string
     */
    public $userId;

    /**
     * @var ProductDTO
     */
    private $product;

    public function setUserId(string $userId): Favorite
    {
        $this->userId = $userId;

        return $this;
    }

    public function setPicnicId(int $picnicId): Favorite
    {
        $this->picnicId = $picnicId;

        return $this;
    }

    public function getPicnicId(): int
    {
        return $this->picnicId;
    }

    public function getProduct(): ProductDTO
    {
        return $this->product;
    }

    public function setProduct(ProductDTO $product): Favorite
    {
        $this->product = $product;

        return $this;
    }
}