<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\requestEntity;

require_once __DIR__ . '/../Entity/requestEntity.php';

/**
 * Controller: PinDeleteRequestController
 * -------------------------------------
 * Hard delete a PIN's own request.
 */
final class PinDeleteRequestController
{
    private requestEntity $repo;

    public function __construct(?requestEntity $repo = null)
    {
        $this->repo = $repo ?? new requestEntity();
    }

    /** Delete and return success */
    public function delete(int $userId, int $requestId): bool
    {
        if ($userId <= 0 || $requestId <= 0) {
            return false;
        }
        return $this->repo->hardDeleteForUser($userId, $requestId);
    }
}