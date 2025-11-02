<?php
declare(strict_types=1);

namespace App\Controller;

// Import both entities
use App\Entity\userAccount;
use App\Entity\userProfile;

require_once dirname(__DIR__) . '/Entity/userAccount.php';
require_once dirname(__DIR__) . '/Entity/userProfile.php';

final class CreateAccountController
{
    /**
     * Handles account creation.
     * Boundary passes trimmed $name/$profileType and HASHED $password.
     * Returns [bool $ok, string $message]
     */
    public function handleCreateAccount(string $name, string $password, string $profileType): array
    {
        if ($name === '' || $password === '' || $profileType === '') {
            return [false, 'All fields are required.'];
        }

        // Optional normalization
        $role = strtolower($profileType);

        $created = userAccount::createAccount($name, $password, $role);

        return $created ? [true, ''] : [false, 'Error creating account.'];
    }

    /**
     * Fetch all active profile types from user_profiles table
     * for the dropdown in create_accountPg.php
     */
    public function getActiveProfiles(): array
    {
        return userProfile::getActiveProfiles();
    }
}
