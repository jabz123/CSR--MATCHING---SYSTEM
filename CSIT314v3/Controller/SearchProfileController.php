<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\userProfile;
require_once __DIR__ . '/../Entity/userProfile.php';

final class SearchProfileController
{
    /**
     * Search profiles by type or status.
     * Returns all profiles if $term is empty.
     */
    public function searchProfiles(string $term): array {
        return userProfile::searchProfiles($term);
    }
}
