<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\pm_categoryentity;

require_once __DIR__ . '/../Entity/pm_categoryentity.php';

final class PMCreateCategoryController
{
    private pm_categoryentity $entity;

    public function __construct()
    {
        $this->entity = new pm_categoryentity();
    }

    /** Receives already-trimmed data from Boundary */
    public function createCategory(string $categoryName): bool
    {
        return $this->entity->insertCategory($categoryName);
    }

    public function getAllCategories(): array
    {
        return $this->entity->fetchAllCategories();
    }
}
