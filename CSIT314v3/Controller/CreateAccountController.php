<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\userAccount;
use App\Entity\userProfile;

require_once dirname(__DIR__) . '/Entity/userAccount.php';
require_once dirname(__DIR__) . '/Entity/userProfile.php';

final class CreateAccountController
{
    private userAccount $entity;

    public function __construct()
    {
        $this->entity = new userAccount();
    }

    public function handleCreateAccount(string $name, string $passwordHash, string $profileType): bool
    {
        return $this->entity->createAccount($name, $passwordHash, $profileType);
    }

    public function getActiveProfiles(): array
    {
        return userProfile::getActiveProfiles();
    }
}
