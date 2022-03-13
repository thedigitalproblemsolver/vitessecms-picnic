<?php declare(strict_types=1);

namespace VitesseCms\Picnic\Forms;

use VitesseCms\Form\AbstractForm;
use VitesseCms\Form\Models\Attributes;
use VitesseCms\Picnic\Enums\PicnicEnum;

class LoginForm extends AbstractForm
{
    public function initialize(): void
    {
        $this->addText('username', PicnicEnum::USERNAME, (new Attributes())->setRequired())
            ->addPassword('password', PicnicEnum::PASSWORD, (new Attributes())->setRequired())
            ->addSubmitButton('%CORE_SAVE%');
    }
}