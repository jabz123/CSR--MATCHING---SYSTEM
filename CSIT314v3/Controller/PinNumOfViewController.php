<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\requestEntity;

require_once __DIR__ . '/../Entity/requestEntity.php';

final class PinNumOfViewController
{
    public function __construct(private ?requestEntity $repo = null)
    {
        $this->repo = $repo ?? new requestEntity();
    }

    /** Thin connector: pass through to Entity */
    public function increment(int $requestId): bool
    {
        return $this->repo->incrementViewCount($requestId);
    }
}
