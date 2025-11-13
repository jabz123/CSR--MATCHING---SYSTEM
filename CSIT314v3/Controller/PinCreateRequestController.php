<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\requestEntity;

require_once __DIR__ . '/../Entity/requestEntity.php';

final class PinCreateRequestController
{
    private requestEntity $repo;

    public function __construct(?requestEntity $repo = null)
    {
        $this->repo = $repo ?? new requestEntity();
    }

    public function create(int $userId, int $categoryId, string $content, string $location, string $title): bool
    {
        return $this->repo->create($userId, $categoryId, $content, $location, $title);
    }

    public function fetchCategories(): array
    {
        return $this->repo->getCategories();
    }

}