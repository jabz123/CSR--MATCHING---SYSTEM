<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\requestEntity;

require_once __DIR__ . '/../Entity/requestEntity.php';

final class CSRReadRequestsController
{
    private requestEntity $entity;

    public function __construct()
    {
        $this->entity = new requestEntity();
    }

    public function readAllRequests(): array
    {
        return $this->entity->readAllRequests();
    }
}
