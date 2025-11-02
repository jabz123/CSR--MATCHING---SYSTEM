<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\pm_categoryentity;

require_once __DIR__ . '/../Entity/pm_categoryentity.php';

final class PMUpdateCategoryController
{
    private pm_categoryentity $entity;

    public function __construct()
    {
        $this->entity = new pm_categoryentity();
    }

    public function getCategory(int $id): ?array
    {
        return $this->entity->fetchCategoryById($id);
    }

    public function updateCategory(int $id, string $newName): bool
    {
        return $this->entity->updateCategory($id, $newName);
    }
}
