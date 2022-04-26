<?php declare(strict_types=1);

namespace VitesseCms\Picnic\Blocks;

use stdClass;
use VitesseCms\Block\AbstractBlockModel;
use VitesseCms\Block\Models\Block;
use VitesseCms\Core\Services\ViewService;
use VitesseCms\Database\Models\FindValue;
use VitesseCms\Database\Models\FindValueIterator;
use VitesseCms\Picnic\Enums\PicnicEnum;
use VitesseCms\Picnic\Forms\LoginForm;
use VitesseCms\Picnic\Forms\SearchForm;
use VitesseCms\Picnic\Helpers\BreadcrumbHelper;
use VitesseCms\Picnic\Repositories\FavoriteRepository;
use VitesseCms\Picnic\Services\PicnicService;

class PicnicShop extends AbstractBlockModel
{
    /**
     * @var PicnicService
     */
    private $picnicService;

    /**
     * @var FavoriteRepository
     */
    private $favoriteRepository;

    public function __construct(ViewService $view)
    {
        parent::__construct($view);
        $this->favoriteRepository = new FavoriteRepository();
    }

    public function parse(Block $block): void
    {
        if (!$this->getDi()->session->has(PicnicEnum::AUTH_HEADER)):
            $block->setTemplate('views/blocks/PicnicShop/login');
            $block->set('form', (new LoginForm())->renderForm('picnic/index/login/'));
        else:
            $this->picnicService = $block->getDi()->eventsManager->fire(PicnicEnum::ATTACH_SERVICE_LISTENER, new stdClass());
            $block->setTemplate('views/blocks/PicnicShop/default');
            $block->set('searchForm', (new SearchForm())->renderForm(
                $this->getDi()->view->getCurrentItem()->getSlug(),
                'picnic-search-form', true)
            );
            $block->set('currentSlug', $this->getDi()->view->getCurrentItem()->getSlug());
            $block->set('user', $this->getDi()->user);

            switch ($this->getDi()->request->get('action')) :
                case 'list':
                    $block->set('title', 'lijstweergave');
                    $block->set('listActive', true);
                    $this->parseList($block);
                    break;
                case 'cart':
                    $block->set('cart', $this->picnicService->getCart());
                    $block->set('title', 'mandje');
                    $block->set('cartActive', true);
                    break;
                case 'favorite':
                    $this->parseFavorite($block);
                    $block->set('title', 'favorieten');
                    $block->set('favoriteActive', true);
                    break;
                case 'settings':
                    $block->set('title', 'settings');
                    $block->set('settingsActive', true);
                    break;
                default:
                    $block->set('title', 'zoeken');
                    $block->set('searchActive', true);
                    $this->parseSearch($block);
            endswitch;
        endif;

        parent::parse($block);
    }

    private function parseSearch(Block $block): void
    {
        if ($this->getDi()->request->hasPost('picnic_searchTerm')):
            $this->getDi()->session->set('picnic_searchTerm', $this->getDi()->request->getPost('picnic_searchTerm'));
        endif;

        if (
            $this->getDi()->session->has('picnic_searchTerm')
            && !empty($this->getDi()->session->get('picnic_searchTerm'))
        ) :
            $searchResult = $this->picnicService->search($this->getDi()->session->get('picnic_searchTerm'));
            $items = [];
            foreach ($searchResult->getItems() as $key => $item) :
                $item->setFavorite($this->favoriteRepository->findFirst(
                    new FindValueIterator([
                        new FindValue('userId', (string)$this->getDi()->user->getId()),
                        new FindValue('picnicId', (int)$item->getId())
                    ])
                ));
                $items[] = $item;
            endforeach;
            $searchResult->setItems($items);
            $block->set('searchResult', $searchResult);
        else :
            $block->set('title', 'Eerder besteld');
            $block->set('categories', $this->picnicService->getCategories());
            $previousOrderedWithFavorite = [];
            foreach ($block->_('categories')->getPreviousOrdered() as $product):
                $product->setFavorite($this->favoriteRepository->findFirst(
                    new FindValueIterator([
                        new FindValue('userId', (string)$this->getDi()->user->getId()),
                        new FindValue('picnicId', (int)$product->getId())
                    ])
                ));
                $previousOrderedWithFavorite[] = $product;
            endforeach;
            $block->set('previousOrdered', $previousOrderedWithFavorite);
            $block->set('hasPreviousOrdered', $block->_('categories')->hasPreviousOrdered());
        endif;
    }

    private function parseFavorite(Block $block): void
    {
        $favorites = $this->favoriteRepository->findAll(
            new FindValueIterator([new FindValue('userId', (string)$this->getDi()->user->getId())])
        );
        if ($favorites->count()):
            $products = [];
            while ($favorites->valid()):
                $favorite = $favorites->current();
                $favorite->setProduct($this->picnicService->getProduct($favorite->getPicnicId()));
                $products[] = $favorite;
                $favorites->next();
            endwhile;

            $block->set('favorites', $products);
            $block->set('hasFavorites', true);
        endif;
    }

    private function parseList(Block $block): void
    {
        if ($this->getDi()->request->has('list')):
            $lists = $this->picnicService->getList(
                $this->getDi()->request->get('list'),
                $this->getDi()->request->get('subList')
            );
            if ($lists->hasProducts()):
                $products = [];
                foreach ($lists->getProducts() as $product) :
                    $product->setFavorite($this->favoriteRepository->findFirst(
                        new FindValueIterator([
                            new FindValue('userId', (string)$this->getDi()->user->getId()),
                            new FindValue('picnicId', (int)$product->getId())
                        ])
                    ));
                    $products[] = $product;
                endforeach;
                $lists->setProducts($products);
            endif;
        else :
            $lists = $this->picnicService->getCategories();
            $this->getDi()->session->set('breadcrumbs', new BreadcrumbHelper());
        endif;

        $block->set('breadcrumbs', $this->getDi()->session->get('breadcrumbs'));
        $block->set('lists', $lists);
    }
}
