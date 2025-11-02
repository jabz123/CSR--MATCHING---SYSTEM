<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\serviceHistoryEntity;

require_once __DIR__ . '/../Entity/serviceHistoryEntity.php';

/**
 * Controller: CSRViewHistoryController
 * ------------------------------------
 * (User Story #29)
 * Allows CSR Reps to view their entire history
 * of completed volunteer services.
 */
final class CSRViewHistoryController
{
    private serviceHistoryEntity $entity;

    public function __construct()
    {
        $this->entity = new serviceHistoryEntity();
    }

    /**
     * ✅ View all completed services for a CSR Rep
     *
     * @param int $csrId CSR Rep ID
     * @return array List of completed services
     */
    public function viewHistory(int $csrId): array
    {
        return $this->entity->searchHistory($csrId);
    }
}
