<?php
declare(strict_types=1);

// Load Composer autoload
require_once __DIR__ . '/vendor/autoload.php';

// Load .env variables if Dotenv is installed
if (class_exists(\Dotenv\Dotenv::class)) {
    $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}
