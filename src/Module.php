<?php declare(strict_types=1);

namespace VitesseCms\Picnic;

use VitesseCms\Admin\Utils\AdminUtil;
use VitesseCms\Core\AbstractModule;
use Phalcon\DiInterface;
use VitesseCms\Picnic\Repositories\FavoriteRepository;
use VitesseCms\Picnic\Repositories\RepositoryCollection;

class Module extends AbstractModule
{
    public function registerServices(DiInterface $di, string $string = null)
    {
        parent::registerServices($di, 'Picnic');
        if (!AdminUtil::isAdminPage()) :
            $di->setShared('repositories', new RepositoryCollection(new FavoriteRepository()));
        endif;
    }
}