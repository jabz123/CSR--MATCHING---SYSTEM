<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\shortlistEntity;

require_once __DIR__ . '/../Entity/shortlistEntity.php';

/**
 * Controller: CSRViewShortlistController
 * ---------------------------------------
 * Handles retrieving all shortlisted requests for a CSR Rep.
 * (User Story #27 - View My Shortlist)
 */
final class CSRViewShortlistController
{
    private shortlistEntity $entity;

    public function __construct()
    {
        $this->entity = new shortlistEntity();
    }

    /**
     * 🧾 Get all shortlist items for this CSR Rep.
     * @param int $csrId
     * @return array
     */
    public function getShortlistByCSR(int $csrId): array
    {
        return $this->entity->getShortlistByCSR($csrId);
    }
}
