<?php
declare(strict_types=1);

namespace App\Controller;

require_once __DIR__ . '/../Entity/userProfile.php';
use App\Entity\userProfile;

class ViewProfileDetailsController
{
    private userProfile $profileEntity;

    public function __construct()
    {
        // Pass temporary placeholder values to satisfy constructor requirements
        $this->profileEntity = new userProfile(0, '', '', '');
    }

    /**
     * Messenger function — calls the entity method to get profile details
     */
    public function getProfileDetails(int $profileId): ?array
    {
        return $this->profileEntity->getProfileById($profileId);
    }
}
