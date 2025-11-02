<?php
declare(strict_types=1);

namespace App\Controller;

require_once __DIR__ . '/../Entity/pinHistoryEntity.php';
use App\Entity\pinHistoryEntity;

final class PinViewHistoryController
{
  private pinHistoryEntity $entity;

  public function __construct(?pinHistoryEntity $entity = null)
  {
    $this->entity = $entity ?? new pinHistoryEntity();
  }

  /**
   * Orchestrates the use case for the boundary.
   * No SQL/DB here—delegates to the Entity.
   */
  public function listForPin(
    int $pinId,
    ?string $status = null,
    int $limit = 200,
    int $offset = 0
  ): array {
    // (Optional) validate inputs/transform params here
    $status = $status ? strtolower($status) : null;
    if ($status && !in_array($status, ['completed','cancelled'], true)) {
      $status = null; // or throw
    }
    return $this->entity->findByPin($pinId, $status, $limit, $offset);
  }
}

