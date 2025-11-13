<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\userAccount;

require_once __DIR__ . '/../Entity/userAccount.php';

final class UpdateUserController
{
    /** ✅ Get a user by ID */
    public function getUser(int $id): ?array {
        return userAccount::getUserById($id);
    }

    /** ✅ Update user logic */
    public function updateUser(int $id, string $name, string $profileType): bool {
        return userAccount::updateUser($id, $name, $profileType);
    }
}
