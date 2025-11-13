<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\serviceHistoryEntity;

require_once __DIR__ . '/../Entity/serviceHistoryEntity.php';

/**
 * Controller: CSRSearchHistoryController
 * --------------------------------------
 * (User Story #28)
 * Allows CSR Reps to search completed volunteer services
 * by keyword (service name) or start date.
 */
final class CSRSearchHistoryController
{
    private serviceHistoryEntity $entity;

    public function __construct()
    {
        $this->entity = new serviceHistoryEntity();
    }

    /**
     * Search completed service history
     *
     * @param int $csrId CSR Rep ID
     * @param string|null $keyword Filter by service name
     * @param string|null $startDate Filter from a given date (optional)
     * @return array Matching service records
     */
    public function searchHistory(int $csrId, ?string $keyword = '', ?string $startDate = ''): array
    {
        return $this->entity->searchHistory($csrId, $keyword, $startDate);
    }
}
