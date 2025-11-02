<?php
declare(strict_types=1);

namespace App\Controller;   // ✅ Add this

use App\Entity\requestEntity;
require_once __DIR__ . '/../Entity/requestEntity.php';

final class CSRViewRequestDetailsController {
    private requestEntity $entity;

    public function __construct() {
        $this->entity = new requestEntity();
    }

    /** #24: View request details */
    public function viewDetails(int $requestId): ?array {
        return $this->entity->getRequestById($requestId);
    }
}
