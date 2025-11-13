<?php
declare(strict_types=1);

namespace App\Controller;

require_once __DIR__ . '/../Entity/PMReportEntity.php';

use App\Entity\PMReportEntity;

final class PMDailyReportController
{
    private PMReportEntity $entity;

    public function __construct()
    {
        $this->entity = new PMReportEntity();
    }

    public function handleRequest(?string $from, ?string $to): array
    {
        return $this->entity->getDailyReport($from, $to);
    }
}
