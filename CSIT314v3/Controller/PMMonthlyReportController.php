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

    public function handleRequest(): array
    {
        // ✅ Read date filters from GET (or fallback to full range)
        $from = $_GET['from'] ?? null;
        $to   = $_GET['to'] ?? null;

        // ✅ Normalize date format (important for MySQL)
        if (!empty($from)) {
            $from = date('Y-m-d', strtotime($from));
        }
        if (!empty($to)) {
            $to = date('Y-m-d', strtotime($to));
        }

        // ✅ Pass filters to Entity
        $data = $this->entity->getMonthlyReport($from, $to);

        return $data;
    }
}
