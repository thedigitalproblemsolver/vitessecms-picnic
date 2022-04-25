<?php declare(strict_types=1);

namespace VitesseCms\Picnic\DTO;

use VitesseCms\Picnic\Models\Favorite;

class ProductDTO
{
    /**
     * @var mixed
     */
    private $data;

    /**
     * @var Favorite
     */
    private $favorite;

    /*private const ImageSizes = [
        TINY: "tiny",
        SMALL: "small",
        MEDIUM: "medium",
        LARGE: "large",
        EXTRA_LARGE: "extra-large"
    ]*/

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->favorite = false;
    }

    public function getId(): string
    {
        return $this->data['id'];
    }

    public function getName(): string
    {
        return $this->data['name'];
    }

    public function getDisplayPrice(): float
    {
        return $this->data['display_price']/100;
    }

    public function getDescription(): string
    {
        return $this->data['description']??'';
    }

    public function getImageUrl(): string
    {
        return  'https://storefront-prod.nl.Picnicinternational.com/static/images/'.$this->data['image_id'].'/large.png';
    }

    public function getFavorite(): Favorite
    {
        return $this->favorite;
    }

    public function isFavorite(): bool
    {
        return $this->favorite !== null;
    }

    public function setFavorite(?Favorite $favorite): self
    {
        $this->favorite = $favorite;

        return $this;
    }
}