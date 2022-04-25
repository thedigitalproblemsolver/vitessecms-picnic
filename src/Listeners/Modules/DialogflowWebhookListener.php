<?php declare(strict_types=1);

namespace VitesseCms\Picnic\Listeners\Modules;

use Dialogflow\Action\Questions\ListCard;
use Dialogflow\Action\Questions\ListCard\Option;
use Phalcon\Events\Event;
use VitesseCms\Google\Services\WebhookService;
use VitesseCms\Picnic\DTO\CartItemDTO;
use VitesseCms\Picnic\DTO\ProductDTO;
use VitesseCms\Picnic\Services\PicnicService;

class DialogflowWebhookListener
{
    /**
     * @var PicnicService
     */
    private $picnic;

    private $username;

    private $password;

    public function __construct(PicnicService $picnic, string $username, string $password)
    {
        $this->picnic = $picnic;
        $this->username = $username;
        $this->password = $password;
    }

    public function PicnicSearchProducts(Event $event, WebhookService $webhookService ) : void
    {
        if (
            !empty($this->username)
            && !empty($this->password)
            && $webhookService->getRequestSource() === 'google'
        ) :
            $this->picnic->login($this->username,$this->password);
            $searchResults = $this->picnic->search($webhookService->getParameters()['searchterm']);

            $conversation = $webhookService->getActionConversation();
            $surface = $conversation->getSurface();
            if(!$surface->hasScreen()) :
                if($searchResults->getItemsCount() > 10 ) :
                    $message = 'I found '.$searchResults->getItemsCount().' results. That is to much. Please try another searchterm';
                else :
                    $message = 'I found '.$searchResults->getItemsCount().' results. These are: ';
                    foreach ($searchResults->getItems() as $key => $productDTO) :
                        $message .= $productDTO->getName().'. ';
                    endforeach;
                endif;
                $conversation->ask($message);
            else :
                $conversation->ask('I found the items above. To order say "add optionnumber to cart" ');
                $listCard = ListCard::create()->title('Search result');
                /**
                 * @var  $key
                 * @var  ProductDTO $productDTO
                 */
                foreach ($searchResults->getItems() as $key => $productDTO) :
                    $listCard->addOption(Option::create()
                        ->key('OPTION_'.$productDTO->getId())
                        ->title($productDTO->getName())
                        ->image($productDTO->getImage())
                    );
                endforeach;

                $conversation->ask($listCard);
            endif;
            $webhookService->reply($conversation);
        endif;
    }

    public function PicnicAddToCart(Event $event, WebhookService $webhookService) : void
    {
        if (
            !empty($this->username)
            && !empty($this->password)
            && $webhookService->getRequestSource() === 'google'
        ) :
            $conversation = $webhookService->getActionConversation();
            $surface = $conversation->getSurface();
            if(!$surface->hasScreen()) :
                $conversation->ask('This is work in progress');
                $webhookService->reply($conversation);
            else :
                $conversation = $webhookService->getActionConversation();
                $productId = (int)str_replace('OPTION_', '', $conversation->getArguments()->get('OPTION'));
                $this->picnic->login($this->username,$this->password);
                $this->picnic->addProduct($productId);
            endif;
        endif;
    }

    public function PicnicGetCart(Event $event, WebhookService $webhookService) : void
    {
        if (
            !empty($this->username)
            && !empty($this->password)
            && $webhookService->getRequestSource() === 'google'
        ) :
            $this->picnic->login($this->username,$this->password);
            $cart = $this->picnic->getCart();

            $conversation = $webhookService->getActionConversation();
            $surface = $conversation->getSurface();
            if(!$surface->hasScreen()) :
                /*if($cart->getItemsCount() > 10 ) :
                    $message = 'I found '.$searchResults->getItemsCount().' results. That is to much. Please try another searchterm';
                else :
                    $message = 'I found '.$searchResults->getItemsCount().' results. These are: ';
                    foreach ($searchResults->getItems() as $key => $searchResultItemDTO) :
                        $message .= $searchResultItemDTO->getName().'. ';
                    endforeach;
                endif;
                $conversation->ask($message);*/
            else :
                $conversation->ask('Your cart contains the items above.');
                $listCard = ListCard::create()->title('The content of your cart');
                /**
                 * @var  $key
                 * @var  CartItemDTO $cartItemDTO
                 */
                foreach ($cart->getItems() as $key => $cartItemDTO) :
                    $listCard->addOption(Option::create()
                        ->key('OPTION_'.$cartItemDTO->getId())
                        ->title($cartItemDTO->getName())
                        //->image($cartItemDTO->getImage())
                    );
                endforeach;

                $conversation->ask($listCard);
            endif;
            $webhookService->reply($conversation);
        endif;
    }
}