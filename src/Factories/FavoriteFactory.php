<?php declare(strict_types=1);

namespace VitesseCms\Picnic\Factories;

use VitesseCms\Picnic\Models\Favorite;

class FavoriteFactory
{
    public static function create(
        int    $picnicId,
        string $userId
    ): Favorite
    {
        $favorite = (new Favorite())->setPicnicId($picnicId)->setUserId($userId);
        $favorite->setPublished(true);

        return $favorite;
    }
}