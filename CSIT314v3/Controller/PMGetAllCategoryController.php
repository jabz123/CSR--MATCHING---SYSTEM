<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\pm_categoryentity;
require_once __DIR__ . '/../Entity/pm_categoryentity.php';

final class PMGetAllCategoryController
{
    private pm_categoryentity $entity;

    public function __construct()
    {
        $this->entity = new pm_categoryentity();
    }

    public function getAllCategories(): array
    {
        return $this->entity->fetchAllCategories();
    }
}
