<?php declare(strict_types=1);

namespace VitesseCms\Picnic\Repositories;

use VitesseCms\Database\Interfaces\BaseRepositoriesInterface;

class RepositoryCollection implements BaseRepositoriesInterface
{
    /**
     * @var FavoriteRepository
     */
    public $favorite;

    public function __construct(
        FavoriteRepository $favoriteRepository
    )
    {
        $this->favorite = $favoriteRepository;
    }
}
