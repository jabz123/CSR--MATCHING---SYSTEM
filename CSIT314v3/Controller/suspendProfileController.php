<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\userProfile;
require_once __DIR__ . '/../Entity/userProfile.php';

final class suspendProfileController
{
    /**
     * Controller acts as a messenger — it delegates to the Entity.
     * @param int $id
     * @param string $action ('suspend' or 'activate')
     * @return bool
     */
    public function handleSuspendAction(int $id, string $action): bool
    {
        // Controller only translates the "action" into a status and calls Entity
        $newStatus = ($action === 'suspend') ? 'suspended' : 'active';

        // Pass the request to the Entity layer
        return userProfile::updateProfileStatus($id, $newStatus);
    }
}
