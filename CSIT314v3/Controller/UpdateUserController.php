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
    public function updateUser(int $id, string $name, string $profileType): array {
        $errors = [];
        $success = '';

        // Basic validation
        if ($name === '') {
            $errors[] = 'Name cannot be empty.';
        }
        if (!in_array($profileType, ['admin', 'csr', 'pin', 'platform'], true)) {
            $errors[] = 'Please select a valid profile type.';
        }

        // If no validation errors, proceed to update
        if (!$errors) {
            $updated = userAccount::updateUser($id, $name, $profileType);
            if ($updated) {
                $success = 'User updated successfully!';
            } else {
                $errors[] = 'Failed to update user or no changes made.';
            }
        }

        return [$errors, $success];
    }
}
