<?php
declare(strict_types=1);

namespace App\Controller;

require_once dirname(__DIR__) . '/Entity/userAccount.php';
use App\Entity\userAccount;

final class suspendAccController
{
    public function suspendUser(int $id, bool $suspend_acc): bool
    {
        return userAccount::suspendUser($id, $suspend_acc);
    }

    public function getUserById(int $id): bool
    {
        return userAccount::getUserById($id);
    }

    

}