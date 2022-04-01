<?php declare(strict_types=1);

namespace VitesseCms\Picnic\Listeners;

use GuzzleHttp\Client;
use VitesseCms\Core\Interfaces\InitiateListenersInterface;
use VitesseCms\Core\Interfaces\InjectableInterface;
use VitesseCms\Google\Enums\GoogleEnum;
use VitesseCms\Picnic\Enums\PicnicEnum;
use VitesseCms\Picnic\Enums\SettingEnum;
use VitesseCms\Picnic\Listeners\Modules\DialogflowWebhookListener;
use VitesseCms\Picnic\Listeners\Services\PicnicServiceListener;
use VitesseCms\Picnic\Services\PicnicService;

class InitiateListeners  implements InitiateListenersInterface
{
    public static function setListeners(InjectableInterface $di): void
    {
        $picnicService = new PicnicService(new Client(), $di->session->get(PicnicEnum::AUTH_HEADER));

        $di->eventsManager->attach(
            PicnicEnum::ATTACH_SERVICE_LISTENER,
            new PicnicServiceListener($picnicService)
        );
        $di->eventsManager->attach(
            GoogleEnum::DIALOGFLOW_WEBHOOK_EVENT,
            new DialogflowWebhookListener(
                $picnicService,
                $di->setting->getString(SettingEnum::LOGIN_USERNAME),
                $di->setting->getString(SettingEnum::LOGIN_PASSWORD),
            )
        );
    }
}
