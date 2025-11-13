<?php
declare(strict_types=1);

namespace App\Controller;

require_once __DIR__ . '/../Entity/PMReportEntity.php';

use App\Entity\PMReportEntity;

final class PMMonthlyReportController
{
    private PMReportEntity $entity;

    public function __construct()
    {
        $this->entity = new PMReportEntity();
    }

    public function handleRequest(?string $from = null, ?string $to = null): array
    {
        return $this->entity->getMonthlyReport($from, $to);
    }
}
