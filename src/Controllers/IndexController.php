<?php declare(strict_types=1);

namespace VitesseCms\Picnic\Controllers;

use stdClass;
use VitesseCms\Core\AbstractEventController;
use VitesseCms\Mustache\DTO\RenderTemplateDTO;
use VitesseCms\Mustache\Enum\ViewEnum;
use VitesseCms\Picnic\Enums\PicnicEnum;
use VitesseCms\Picnic\Services\PicnicService;

class IndexController extends AbstractEventController
{
    public function loginAction()
    {
        if(!$this->session->has(PicnicEnum::AUTH_HEADER)):
            /** @var PicnicService $picnicService */
            $picnicService = $this->eventsManager->fire(PicnicEnum::ATTACH_SERVICE_LISTENER, new stdClass());
            $auth = $picnicService->login(
                $this->request->get(PicnicEnum::USERNAME),
                $this->request->get(PicnicEnum::PASSWORD)
            );
            $this->session->set(PicnicEnum::AUTH_HEADER,$auth[0]);
        endif;

        $this->redirect();
    }

    public function productAction(int $productId): void
    {
        if($this->session->has(PicnicEnum::AUTH_HEADER)):
            /** @var PicnicService $picnicService */
            $picnicService = $this->eventsManager->fire(PicnicEnum::ATTACH_SERVICE_LISTENER, new stdClass());
            $this->view->setVar(
                'content',
                $this->eventsManager->fire(
                    ViewEnum::RENDER_TEMPLATE_EVENT,
                    new RenderTemplateDTO(
                        'views/product_detail',
                        '',
                        ['product' => $picnicService->getProduct($productId)]
                    )
                )
            );
        endif;

        $this->prepareView();
    }

    public function addtocartAction(int $productId, int $amount = 1): void
    {
        if($this->session->has(PicnicEnum::AUTH_HEADER)):
            /** @var PicnicService $picnicService */
            $picnicService = $this->eventsManager->fire(PicnicEnum::ATTACH_SERVICE_LISTENER, new stdClass());
            $picnicService->addProduct($productId, $amount);
        endif;

        $this->flash->setSucces('Aan mandje toegevoegd');
        $this->redirect();
    }
}
