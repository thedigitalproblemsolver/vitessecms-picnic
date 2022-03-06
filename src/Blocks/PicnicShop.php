<?php declare(strict_types=1);

namespace VitesseCms\Picnic\Blocks;

use stdClass;
use VitesseCms\Block\AbstractBlockModel;
use VitesseCms\Block\Models\Block;
use VitesseCms\Picnic\Enums\PicnicEnum;
use VitesseCms\Picnic\Forms\LoginForm;
use VitesseCms\Picnic\Forms\SearchForm;
use VitesseCms\Picnic\Services\PicnicService;

class PicnicShop extends AbstractBlockModel
{
    public function parse(Block $block): void
    {
        if (!$this->getDi()->session->has(PicnicEnum::AUTH_HEADER)):
            $block->setTemplate('views/blocks/PicnicShop/login');
            $block->set('form', (new LoginForm())->renderForm('picnic/index/login/'));
        else:
            /** @var PicnicService $picnicService */
            $picnicService = $block->getDi()->eventsManager->fire(PicnicEnum::ATTACH_SERVICE_LISTENER, new stdClass());
//var_dump(json_decode((string)$picnicService->search('koffie')->getBody(), true)[0]['items'][0]);
            //var_dump(json_decode((string)$picnicService->search('koffie')->getBody(), true)[0]['items'][0]);
        //$picnicService->getDeliveries()
            $block->setTemplate('views/blocks/PicnicShop/default');
            $block->set('searchForm', (new SearchForm())->renderForm('','picnic-search-form', true) );
            if($this->getDi()->session->has(PicnicEnum::AUTH_HEADER) && $this->getDi()->request->hasPost('searchTerm')):
                $block->set('searchResult', $picnicService->search($this->getDi()->request->getPost('searchTerm')));
            endif;


        endif;

        parent::parse($block);
    }
}
