<?php declare(strict_types=1);

namespace VitesseCms\Picnic\DTO;

use Psr\Http\Message\ResponseInterface;
use VitesseCms\Picnic\Models\Favorite;

class SearchResultItemDTO {

    /**
     * @var array
     */
    private $item;

    /**
     * @var Favorite
     */
    private $favorite;

    public function __construct(array $item)
    {
        $this->item = $item;
    }

    public function getName(): string
    {
        return $this->item['name'];
    }

    public function getId(): int
    {
        return (int)$this->item['id'];
    }

    public function getFavorite(): Favorite
    {
        return $this->favorite;
    }

    public function isFavorite(): bool
    {
        return $this->favorite !== null;
    }

    public function setFavorite(?Favorite $favorite): SearchResultItemDTO
    {
        $this->favorite = $favorite;

        return $this;
    }
}