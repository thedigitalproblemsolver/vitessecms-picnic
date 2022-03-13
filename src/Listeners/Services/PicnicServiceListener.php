<?php declare(strict_types=1);

namespace VitesseCms\Picnic\Listeners\Services;

use Phalcon\Events\Event;
use VitesseCms\Picnic\Services\PicnicService;

class PicnicServiceListener
{
    /**
     * @var PicnicService
     */
    private $picnic;

    public function __construct(PicnicService $picnic)
    {
        $this->picnic = $picnic;
    }

    public function attach( Event $event): PicnicService
    {
        return $this->picnic;
    }
}