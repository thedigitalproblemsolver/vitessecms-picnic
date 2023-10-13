<?php declare(strict_types=1);

namespace VitesseCms\Picnic\Enums;

use VitesseCms\Core\AbstractEnum;

class PicnicEnum extends AbstractEnum
{
    public const ATTACH_SERVICE_LISTENER = 'picnicService:attach';
    public const AUTH_HEADER = 'x-Picnic-auth';
    public const USERNAME = 'picnic_username';
    public const PASSWORD = 'picnic_password';
    public const SINGLE_ARTICLE = 'SINGLE_ARTICLE';
    public const CATEGORY = 'CATEGORY';
}
