<?php
declare(strict_types=1);

namespace App\Controller;

require_once __DIR__ . '/../Entity/requestEntity.php';

use App\Entity\requestEntity;

final class PinViewRequestsController
{
    /** List requests for a PIN with optional status/q filters and paging */
    public function list(
        int $userId,
        ?string $status = null, // 'open' | 'in_progress' | 'closed' | null (=all)
        ?string $q = null,
        int $page = 1,
        int $perPage = 10
    ): array {
        $ent = new requestEntity();
        return $ent->listByUser($userId, $status, $q, $page, $perPage);
    }

}