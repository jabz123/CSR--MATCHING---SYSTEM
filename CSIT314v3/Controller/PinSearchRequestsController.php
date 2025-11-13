<?php
declare(strict_types=1);

namespace App\Controller;

require_once __DIR__ . '/../Entity/requestEntity.php';

use App\Entity\requestEntity;

final class PinSearchRequestsController
{
    public function search(int $userId, string $q, ?string $status, int $page, int $perPage): array {
        $ent = new requestEntity();
        return $ent->searchRequests($userId, $q, $status, $page, $perPage);
    }

}