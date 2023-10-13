<?php declare(strict_types=1);

namespace VitesseCms\Picnic\Models;

use ArrayIterator;

class FavoriteIterator extends ArrayIterator
{
    public function __construct(array $favorites)
    {
        parent::__construct($favorites);
    }

    public function current(): Favorite
    {
        return parent::current();
    }
}