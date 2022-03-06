<?php declare(strict_types=1);

namespace VitesseCms\Picnic\DTO;

use Psr\Http\Message\ResponseInterface;

class SearchResultDTO {

    /**
     * @var mixed
     */
    private $data;

    public function __construct(ResponseInterface $response)
    {
        $this->data = json_decode((string)$response->getBody(), true)[0];
    }

    public function getItems(): array
    {
        return $this->data['items'];
    }
}