<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\userProfile;

require_once __DIR__ . '/../Entity/userProfile.php';

final class UpdateProfileController
{
    /** Retrieve a single profile by its ID */
    public function getProfileById(int $id): ?array
    {
        return userProfile::getProfileById($id);
    }

    /** Handle profile updates */
    public function updateProfile(int $id, string $profileType, string $status): bool
    {
        return userProfile::updateProfile($id, $profileType, $status);
    }
}
?>
