<?php
declare(strict_types=1);

namespace App\Controller;

require_once __DIR__ . '/../Entity/userProfile.php';
use App\Entity\userProfile;

final class CreateProfileController
{
    private userProfile $profileEntity;

    public function __construct()
    {
        // Placeholder values to satisfy constructor
        $this->profileEntity = new userProfile(0, '', '', '');
    }

    public function createProfile(string $type, string $status): bool
    {
        return $this->profileEntity->createProfile($type, $status);
    }
}
