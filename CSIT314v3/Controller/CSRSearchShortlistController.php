<?php
declare(strict_types=1);

namespace App\Controller; // ✅ REQUIRED for PSR-4 autoloading

require_once $_SERVER['DOCUMENT_ROOT'] . '/CSIT314v3/Entity/shortlistEntity.php';

use App\Entity\shortlistEntity;

final class CSRSearchShortlistController
{
    private shortlistEntity $entity;

    public function __construct()
    {
        $this->entity = new shortlistEntity();
    }

    /** #26: Search shortlist by keyword */
    public function searchShortlist(int $csrId, string $keyword = ''): array
    {
        return $this->entity->searchShortlist($csrId, $keyword);
    }
}
