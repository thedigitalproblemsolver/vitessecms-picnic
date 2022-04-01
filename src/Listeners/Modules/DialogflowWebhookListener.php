<?php declare(strict_types=1);

namespace VitesseCms\Picnic\Listeners\Modules;

use Dialogflow\Action\Questions\ListCard;
use Dialogflow\Action\Questions\ListCard\Option;
use Phalcon\Events\Event;
use VitesseCms\Google\Services\WebhookService;
use VitesseCms\Picnic\DTO\SearchResultItemDTO;
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
            && $webhookService->getRequestSource()=='google'
        ) :
            $this->picnic->login($this->username,$this->password);
            $searchResults = $this->picnic->search($webhookService->getParameters()['searchterm']);

            $conversation = $webhookService->getActionConversation();
            if($webhookService->isAudioOnly()) :
                if($searchResults->getItemsCount() > 10 ) :
                    $message = 'I found '.$searchResults->getItemsCount().' results. That is to much. Please try another searchterm';
                else :
                    $message = 'I found '.$searchResults->getItemsCount().' results. These are: ';
                    foreach ($searchResults->getItems() as $key => $searchResultItemDTO) :
                        $message .= $searchResultItemDTO->getName().'. ';
                    endforeach;
                endif;
                $conversation->ask($message);
            else :
                $conversation->ask('I found the items above');
                $listCard = ListCard::create()->title('Search result');
                /**
                 * @var  $key
                 * @var  SearchResultItemDTO $searchResultItemDTO
                 */
                foreach ($searchResults->getItems() as $key => $searchResultItemDTO) :
                    $listCard->addOption(Option::create()
                        ->key('OPTION_'.$key)
                        ->title($searchResultItemDTO->getName())
                        ->description('Select option '.$key)
                        ->image($searchResultItemDTO->getImage())
                    );
                endforeach;

                $conversation->ask($listCard);
            endif;
            $webhookService->reply($conversation);
        endif;
    }
}