<?php
declare(strict_types=1);

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

class InitiateListeners implements InitiateListenersInterface
{
    public static function setListeners(InjectableInterface $injectable): void
    {
        $picnicService = new PicnicService(new Client(), $injectable->session->get(PicnicEnum::AUTH_HEADER));

        $injectable->eventsManager->attach(
            PicnicEnum::ATTACH_SERVICE_LISTENER,
            new PicnicServiceListener($picnicService)
        );
        $injectable->eventsManager->attach(
            GoogleEnum::DIALOGFLOW_WEBHOOK_EVENT,
            new DialogflowWebhookListener(
                $picnicService,
                $injectable->setting->getString(SettingEnum::LOGIN_USERNAME),
                $injectable->setting->getString(SettingEnum::LOGIN_PASSWORD),
            )
        );
    }
}
