<?php declare(strict_types=1);

namespace VitesseCms\Picnic\DTO;

use Psr\Http\Message\ResponseInterface;

class CategoriesDTO {

    /**
     * @var mixed
     */
    private $data;

    /**
     * @var array
     */
    private $categories;

    /**
     * @var array
     */
    private $previousOrdered;

    public function __construct(ResponseInterface $response)
    {
        $this->data = json_decode((string)$response->getBody(), true);
        $this->categories = [];
        $this->previousOrdered = [];
        foreach ($this->data['catalog'] as $category) :
            $categoryDTO = new CategoryDTO($category);
            if($categoryDTO->getId() === 'purchases'):
               foreach($categoryDTO->getSubCategories()[0]->getSubCategories() as $product):
                   $this->previousOrdered[] = new ProductDTO($product->getData());
               endforeach;
           else :
               $this->categories[] = $categoryDTO;
                //var_dump($category);
           //
               //var_dump($category['items'][1]);
               //die();
            endif;
        endforeach;
    }

    public function hasCategories() : bool
    {
        return count($this->categories) > 0;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function getPreviousOrdered(): array
    {
        return $this->previousOrdered;
    }

    public function hasPreviousOrdered(): bool
    {
        return count($this->previousOrdered) > 0;
    }
}