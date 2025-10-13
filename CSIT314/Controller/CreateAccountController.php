<?php
declare(strict_types=1);

namespace App\Controller;

final class CreateAccountController {

    
    public function createAccount(string $name, string $passwordHash, string $profileType): bool {
        // Simply pass the data through - boundary handles the logic
        return true;
    }
}