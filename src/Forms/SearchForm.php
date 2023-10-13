<?php declare(strict_types=1);

namespace VitesseCms\Picnic\Forms;

use VitesseCms\Form\AbstractForm;
use VitesseCms\Form\Models\Attributes;

class SearchForm extends AbstractForm
{
    public function initialize(): void
    {
        $this->addText(
            '',
            'picnic_searchTerm',
            (new Attributes())->setDefaultValue($this->session->get('picnic_searchTerm'))
        )->addSubmitButton('Zoeken')
            ->addEmptyButton('Verwijder zoekterm');
    }

    public function renderForm(string $action, string $formName = null, bool $noAjax = false, bool $newWindow = false): string
    {
        $this->setColumn(0,'label', ['xs' => 12, 'sm' => 12, 'md' => 4, 'lg' => 3, 'xl' => 3]);
        $this->setColumn(12,'input', ['xs' => 12, 'sm' => 12, 'md' => 4, 'lg' => 3, 'xl' => 3]);

        return parent::renderForm($action, $formName, $noAjax, $newWindow);
    }
}