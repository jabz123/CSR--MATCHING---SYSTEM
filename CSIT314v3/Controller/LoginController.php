<?php
declare(strict_types=1);

namespace App\Controller;

require_once __DIR__ . '/../Entity/userAccount.php';
use App\Entity\userAccount;

final class LoginController
{
    public function login(string $name): ?array
    {
        return userAccount:: login($name);
    }
}
