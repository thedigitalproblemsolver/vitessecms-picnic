<?php declare(strict_types=1);

namespace VitesseCms\Picnic\DTO;

class CategoryDTO
{
    /**
     * @var array
     */
    private $data;

    private $subCategories;

    private $parentList;

    public function __construct(array $data, ?string $parentList = null)
    {
        $this->data = $data;
        $this->parentList = $parentList;
        $this->subCategories = [];
        if (isset($this->data['items'])):
            foreach ($this->data['items'] as $subCategory) :
                $this->subCategories[] = new CategoryDTO($subCategory, $this->data['id']);
            endforeach;
        endif;
    }

    public function getId(): string
    {
        return $this->data['id'];
    }

    public function getName(): string
    {
        return $this->data['name'];
    }

    public function hasSubCategories(): bool
    {
        return count($this->subCategories) > 0;
    }

    public function getSubCategories(): array
    {
        return $this->subCategories;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getListLink(): string
    {
        if ($this->parentList !== null):
            return 'list=' . $this->parentList . '&sublist=' . $this->data['id'];
        endif;

        return 'list=' . $this->data['id'];
    }
}