<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\requestEntity;

require_once __DIR__ . '/../Entity/requestEntity.php';

/**
 * Controller: CSRSearchRequestsController
 * ---------------------------------------
 * Handles logic for searching or listing open requests.
 * (User Story #23 - Search Open Requests)
 */
final class CSRSearchRequestsController
{
    private requestEntity $entity;

    public function __construct()
    {
        $this->entity = new requestEntity();
    }

    /**
     * Retrieve open requests based on search keyword.
     * @param string|null $keyword (trimmed in boundary)
     * @return array
     */
    public function searchRequests(?string $keyword = null): array
    {
        return $this->entity->searchOpenRequests($keyword);
    }
}
