<?php
declare(strict_types=1);

namespace App\Controller;

require_once __DIR__ . '/../Entity/shortlistEntity.php';
require_once __DIR__ . '/../Entity/requestEntity.php';

use App\Entity\shortlistEntity;
use App\Entity\requestEntity;

final class CSRSaveShortlistController
{
    private shortlistEntity $entity;
    private requestEntity   $requests;

    public function __construct()
    {
        // Per tutor rule, entities handle DB creation/ownership internally
        $this->entity   = new shortlistEntity();
        $this->requests = new requestEntity();
    }

    /** Returns: 'success' | 'duplicate' | 'insert_failed' */
    public function saveToShortlist(int $csrId, int $requestId): string
    {
        try {
            // Let the entity check duplicates (DB stays in Entity)
            if (method_exists($this->entity, 'existsInShortlist') && $this->entity->existsInShortlist($csrId, $requestId)) {
                return 'duplicate';
            }

            // Correct property! ($this->entity, not $this->shortlist)
            $ok = $this->entity->addToShortlist($csrId, $requestId);

            if ($ok) {
                // keep counter in sync for PIN view
                if (method_exists($this->requests, 'incrementShortlistCount')) {
                    $this->requests->incrementShortlistCount($requestId);
                }
                return 'success';
            }
            return 'insert_failed';
        } catch (\Throwable $e) {
            error_log('CSRSaveShortlistController saveToShortlist fatal: ' . $e->getMessage());
            return 'insert_failed';
        }
    }
}
