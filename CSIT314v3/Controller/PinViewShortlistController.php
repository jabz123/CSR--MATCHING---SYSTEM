<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\shortlistEntity;

require_once __DIR__ . '/../Entity/shortlistEntity.php';

final class PinViewShortlistController
{
    private shortlistEntity $entity;

    public function __construct()
    {
        $this->entity = new shortlistEntity();
    }

    public function add(int $csrId, int $requestId): bool
    {
        return $this->entity->addShortlist($csrId, $requestId);
    }

    public function remove(int $csrId, int $requestId): bool
    {
        return $this->entity->removeShortlist($csrId, $requestId);
    }
}
