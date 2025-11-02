<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\userAccount;
require_once __DIR__ . '/../Entity/userAccount.php';

final class SearchUserController
{
    /**
     * Search users by name or profile type.
     * Returns all users if $term is empty.
     */
    public function searchUsers(string $term): array {
        return userAccount::searchUsers($term);
    }
}
