<?php
declare(strict_types=1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/CSIT314v3/Entity/requestEntity.php';
use App\Entity\requestEntity;

final class CSRReadRequestsController {
    private requestEntity $entity;

    public function __construct() {
        $this->entity = new requestEntity();
    }

    /** ✅ Retrieve all requests (no filter) */
    public function readAllRequests(): array {
        return $this->entity->readAllRequests();
    }
}
