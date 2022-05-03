<?php declare(strict_types=1);

namespace VitesseCms\Picnic\DTO;

use VitesseCms\Picnic\Enums\PicnicEnum;

class ListsDTO
{
    /**
     * @var mixed
     */
    private $data;

    /**
     * @var array
     */
    private $categories;

    private $products;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->categories = [];
        $this->products = [];
        foreach ($this->data as $category) :
            if ($category['type'] === PicnicEnum::SINGLE_ARTICLE) :
                $this->products[] = new ProductDTO($category);
            elseif ($category['type'] === PicnicEnum::CATEGORY) :
                $this->categories[] = new CategoryDTO($category);
            endif;
        endforeach;
    }

    public function hasCategories(): bool
    {
        return count($this->categories) > 0;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function hasProducts(): bool
    {
        return count($this->products) > 0;
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public function setProducts(array $products): self
    {
        $this->products = $products;

        return $this;
    }
}