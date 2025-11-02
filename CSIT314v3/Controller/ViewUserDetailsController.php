<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\userAccount;
require_once __DIR__ . '/../Entity/userAccount.php';

final class ViewUserDetailsController
{
    public function viewUserDetails(int $id): ?array
    {
        return userAccount::getUserById($id);
    }
}
