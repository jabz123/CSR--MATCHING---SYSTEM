<?php
declare(strict_types=1);

namespace App\Controller;

require_once __DIR__ . '/../Entity/pinHistoryEntity.php';
use App\Entity\pinHistoryEntity;

final class PinSearchHistoryController
{
    private pinHistoryEntity $entity;

    public function __construct(?pinHistoryEntity $entity = null)
    {
        $this->entity = $entity ?? new pinHistoryEntity();
    }


    public function search(
        int $pinId,
        ?string $q,
        ?string $from,
        ?string $to,
        int $limit = 100,
        int $offset = 0
    ): array {
        return $this->entity->searchCompleted($pinId, $q, $from, $to, $limit, $offset);
    }
}