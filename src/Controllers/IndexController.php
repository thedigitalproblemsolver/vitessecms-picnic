<?php declare(strict_types=1);

namespace VitesseCms\Picnic\Controllers;

use stdClass;
use VitesseCms\Core\AbstractEventController;
use VitesseCms\Core\Interfaces\InjectableInterface;
use VitesseCms\Mustache\DTO\RenderTemplateDTO;
use VitesseCms\Mustache\Enum\ViewEnum;
use VitesseCms\Picnic\Enums\PicnicEnum;
use VitesseCms\Picnic\Factories\FavoriteFactory;
use VitesseCms\Picnic\Repositories\RepositoriesInterface;
use VitesseCms\Picnic\Services\PicnicService;

class IndexController extends AbstractEventController implements InjectableInterface, RepositoriesInterface
{
    public function loginAction()
    {
        if (!$this->session->has(PicnicEnum::AUTH_HEADER)):
            /** @var PicnicService $picnicService */
            $picnicService = $this->eventsManager->fire(PicnicEnum::ATTACH_SERVICE_LISTENER, new stdClass());
            $auth = $picnicService->login(
                $this->request->get(PicnicEnum::USERNAME),
                $this->request->get(PicnicEnum::PASSWORD)
            );
            $this->session->set(PicnicEnum::AUTH_HEADER, $auth[0]);
        endif;

        $this->redirect();
    }

    public function productAction(int $productId): void
    {
        if ($this->session->has(PicnicEnum::AUTH_HEADER)):
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
        if ($this->session->has(PicnicEnum::AUTH_HEADER)):
            /** @var PicnicService $picnicService */
            $picnicService = $this->eventsManager->fire(PicnicEnum::ATTACH_SERVICE_LISTENER, new stdClass());
            $picnicService->addProduct($productId, $amount);
        endif;

        $this->flash->setSucces('Aan mandje toegevoegd');
        $this->redirect();
    }

    public function removefromcartAction(int $productId, int $amount = 1): void
    {
        if ($this->session->has(PicnicEnum::AUTH_HEADER)):
            /** @var PicnicService $picnicService */
            $picnicService = $this->eventsManager->fire(PicnicEnum::ATTACH_SERVICE_LISTENER, new stdClass());
            $picnicService->removeProduct($productId, $amount);
        endif;

        $this->flash->setSucces('Product uit mandje verwijderd');
        $this->redirect();
    }

    public function addfavoriteAction(int $productId): void
    {
        if ($this->user->isLoggedIn()) :
            FavoriteFactory::create($productId, (string)$this->user->getId())->save();
            $this->flash->setSucces('Product aan favorieten toegevoegd');
        endif;

        $this->redirect();
    }

    public function removefavoriteAction(string $id): void
    {
        if ($this->user->isLoggedIn()) :
            $this->repositories->favorite->getById($id, false)->delete();
            $this->flash->setSucces('Product van favorieten verwijderd');
        endif;

        $this->redirect();
    }

    public function importOrdersAction(): void
    {
        if ($this->session->has(PicnicEnum::AUTH_HEADER)):
            /** @var PicnicService $picnicService */
            $picnicService = $this->eventsManager->fire(PicnicEnum::ATTACH_SERVICE_LISTENER, new stdClass());
            var_dump(json_decode((string)$picnicService->getCategories()->getBody(),true));
        endif;
die();
        $this->redirect();
    }
}
