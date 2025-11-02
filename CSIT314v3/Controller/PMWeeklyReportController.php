<?php
declare(strict_types=1);

namespace App\Controller;

require_once __DIR__ . '/../Entity/PMReportEntity.php';

use App\Entity\PMReportEntity;

final class PMWeeklyReportController
{
    private PMReportEntity $entity;

    public function __construct()
    {
        $this->entity = new PMReportEntity();
    }

    public function handleRequest(): array
    {
        // ✅ Get filters from GET parameters
        $from = $_GET['from'] ?? null;
        $to   = $_GET['to'] ?? null;

        // ✅ Normalize to MySQL date format
        if (!empty($from)) {
            $from = date('Y-m-d', strtotime($from));
        }
        if (!empty($to)) {
            $to = date('Y-m-d', strtotime($to));
        }

        // ✅ Pass filters to entity
        return $this->entity->getWeeklyReport($from, $to);
    }
}
