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

    public function __construct(array $data)
    {
        $this->data = $data;
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
}