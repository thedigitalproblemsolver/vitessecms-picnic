<?php declare(strict_types=1);

namespace VitesseCms\Picnic\Repositories;

use VitesseCms\Database\Models\FindOrderIterator;
use VitesseCms\Database\Models\FindValueIterator;
use VitesseCms\Picnic\Models\Favorite;
use VitesseCms\Picnic\Models\FavoriteIterator;

class FavoriteRepository
{
    public function getById(string $id, bool $hideUnpublished = true, $renderFields = true): ?Favorite
    {
        Favorite::setFindPublished($hideUnpublished);
        Favorite::setRenderFields($renderFields);

        /** @var Favorite $item */
        $item = Favorite::findById($id);
        if (is_object($item)):
            return $item;
        endif;

        return null;
    }

    public function findAll(
        ?FindValueIterator $findValues = null,
        bool $hideUnpublished = true,
        ?int $limit = null,
        ?FindOrderIterator $findOrders = null
    ): FavoriteIterator
    {
        Favorite::setFindPublished($hideUnpublished);
        if ($limit !== null) :
            Favorite::setFindLimit($limit);
        endif;
        $this->parseFindValues($findValues);
        $this->parseFindOrders($findOrders);

        return new FavoriteIterator(Favorite::findAll());
    }

    public function findFirst(
        ?FindValueIterator $findValues = null,
        bool $hideUnpublished = true
    ): ?Favorite
    {
        $favorites = $this->findAll($findValues, $hideUnpublished);
        if($favorites->count() === 0) :
            return null;
        endif;

        return $favorites->current();
    }

    protected function parseFindValues(?FindValueIterator $findValues = null): void
    {
        if ($findValues !== null) :
            while ($findValues->valid()) :
                $findValue = $findValues->current();
                Favorite::setFindValue(
                    $findValue->getKey(),
                    $findValue->getValue(),
                    $findValue->getType()
                );
                $findValues->next();
            endwhile;
        endif;
    }

    protected function parseFindOrders(?FindOrderIterator $findOrders = null): void
    {
        if ($findOrders !== null) :
            while ($findOrders->valid()) :
                $findOrder = $findOrders->current();
                Favorite::addFindOrder(
                    $findOrder->getKey(),
                    $findOrder->getOrder()
                );
                $findOrders->next();
            endwhile;
        endif;
    }
}
