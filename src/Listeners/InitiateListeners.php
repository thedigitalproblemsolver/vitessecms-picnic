<?php declare(strict_types=1);

namespace VitesseCms\Picnic\Listeners;

use GuzzleHttp\Client;
use VitesseCms\Core\Interfaces\InitiateListenersInterface;
use VitesseCms\Core\Interfaces\InjectableInterface;
use VitesseCms\Picnic\Enums\PicnicEnum;
use VitesseCms\Picnic\Listeners\Services\PicnicServiceListener;
use VitesseCms\Picnic\Services\PicnicService;

class InitiateListeners  implements InitiateListenersInterface
{
    public static function setListeners(InjectableInterface $di): void
    {
        $di->eventsManager->attach(
            PicnicEnum::ATTACH_SERVICE_LISTENER,
            new PicnicServiceListener(
                new PicnicService(
                    new Client(),
                    $di->session->get(PicnicEnum::AUTH_HEADER)
                )
            )
        );
    }
}
