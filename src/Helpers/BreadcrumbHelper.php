<?php declare(strict_types=1);

namespace VitesseCms\Picnic\Helpers;

class BreadcrumbHelper
{
    private $breadcrumbItems;

    public function __construct()
    {
        $this->breadcrumbItems = [];
    }

    public function setItem(string $id): self
    {
        return $this;
    }

    public function getItems(): array
    {
        return $this->breadcrumbItems;
    }
}