<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\requestEntity;

require_once __DIR__ . '/../Entity/requestEntity.php';

final class PinViewShortlistController
{
    private requestEntity $entity;

    public function __construct()
    {
        $this->entity = new requestEntity();
    }

    public function getCountForShortlist(int $requestId): int
    {
        return $this->entity->getShortlistCount($requestId);
    }

}
