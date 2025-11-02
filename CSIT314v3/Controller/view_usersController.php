<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\userAccount;

require_once __DIR__ . '/../Entity/userAccount.php';

final class view_usersController
{
    /** Fetch all users */
    public function getAllUsers(): array {
        return userAccount::getAllUsers();
    }

    
}
