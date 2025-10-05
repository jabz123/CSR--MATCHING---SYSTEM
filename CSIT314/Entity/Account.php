<?php
declare(strict_types=1);

namespace App\Entity;

final class Account {
    public string $profileType;
    public string $name;
    public string $passwordHash;

    public function __construct(string $profileType, string $name, string $passwordHash) {
        $this->profileType = $profileType;
        $this->name = $name;
        $this->passwordHash = $passwordHash;
    }
}