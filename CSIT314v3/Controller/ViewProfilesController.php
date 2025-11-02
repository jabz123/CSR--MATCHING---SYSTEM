<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\userProfile;
require_once __DIR__ . '/../Entity/userProfile.php';

final class ViewProfilesController
{
    /** Get all profiles for display */
    public function getAllProfiles(): array
    {
        return userProfile::getAllProfiles();
    }

   
}
