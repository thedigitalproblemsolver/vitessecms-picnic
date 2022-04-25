<?php declare(strict_types=1);

namespace VitesseCms\Picnic\Forms;

use VitesseCms\Form\AbstractForm;
use VitesseCms\Form\Models\Attributes;

class SearchForm extends AbstractForm
{
    public function initialize(): void
    {
        $this->addText(
            'Term',
            'picnic_searchTerm',
            (new Attributes())->setDefaultValue($this->session->get('picnic_searchTerm'))
        )->addSubmitButton('Zoeken')
            ->addEmptyButton('Verwijder zoekterm');
    }
}