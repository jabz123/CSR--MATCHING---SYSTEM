<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\pm_categoryentity;
require_once __DIR__ . '/../Entity/pm_categoryentity.php';

final class PMSearchCategoryController
{
    private pm_categoryentity $entity;

    public function __construct()
    {
        $this->entity = new pm_categoryentity();
    }

    /**
     * Search categories by name.
     * If $term is empty, returns all categories.
     */
    public function searchCategories(string $term): array
    {
        if (trim($term) === '') {
            return $this->entity->fetchAllCategories();
        }

        return $this->entity->searchCategories($term);
    }
}
