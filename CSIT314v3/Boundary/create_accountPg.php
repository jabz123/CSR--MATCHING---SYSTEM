<?php
declare(strict_types=1);
session_start();

require_once dirname(__DIR__) . '/Controller/CreateAccountController.php';
require_once dirname(__DIR__) . '/Entity/userAccount.php';

use App\Controller\CreateAccountController;
use App\Entity\userAccount;
$controller = new CreateAccountController();
$profiles = $controller->getActiveProfiles();
function handleCreateAccountPage(): void
{
    // (Show PHP errors during development)
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    $profiles = userAccount::getAllProfileTypes();
    $errors = [];
    $success = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // 👉 Trim input here
        $name        = trim($_POST['name'] ?? '');
        $passwordRaw = $_POST['password'] ?? '';
        $profileType = trim($_POST['profile_type'] ?? '');

        // 👉 UI validation
        if ($name === '' || $passwordRaw === '' || $profileType === '') {
            $errors[] = 'Please fill in all fields.';
        } elseif (strlen($passwordRaw) < 8) {
            $errors[] = 'Password must be at least 8 characters.';
        } else {
            $passwordHash = password_hash($passwordRaw, PASSWORD_DEFAULT);
            $controller = new CreateAccountController();

            // Use controller logic (returns [bool, message])
            [$ok, $msg] = $controller->handleCreateAccount($name, $passwordHash, strtolower($profileType));

            if ($ok) {
                $success = '✅ Account created successfully!';
            } else {
                $errors[] = $msg ?: '⚠️ Unable to create account.';
            }
        }
    }

    // 👉 Render the HTML form
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Create Account</title>
        <style>
            body {
                font-family: "Poppins", sans-serif;
                background: linear-gradient(135deg, #7a5cf3, #8b6df5, #a589f7);
                margin: 0; padding: 0; height: 100vh;
                display: flex; align-items: center; justify-content: center;
            }
            .form-container {
                background: #fff; border-radius: 20px; padding: 40px 50px;
                box-shadow: 0 10px 30px rgba(0,0,0,.15);
                width: 400px; max-width: 90%; text-align: center;
            }
            h2 { color: #5d3fd3; margin-bottom: 5px; }
            p.subtitle { color: #666; font-size: 14px; margin-bottom: 20px; }
            .alert { padding:10px 12px; border-radius:10px; margin:10px 0 20px; text-align:left; font-size:14px; }
            .alert-error { background:#fde8e8; color:#9b1c1c; border:1px solid #fecaca; }
            .alert-success { background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0; }
            label { display:block; text-align:left; font-weight:600; margin-bottom:5px; color:#333; }
            input, select {
                width:100%; padding:12px 15px; border-radius:10px; border:1px solid #ccc;
                margin-bottom:20px; font-size:14px; background:#f9f9f9;
            }
            input:focus, select:focus { border-color:#7a5cf3; outline:none; box-shadow:0 0 5px rgba(122,92,243,.4); }
            .form-actions { display:flex; justify-content:space-between; align-items:center; margin-top:10px; }
            .create-btn {
                background: linear-gradient(90deg, #6e59e7, #8c6cf5);
                color:#fff; border:none; padding:10px 30px; border-radius:10px; cursor:pointer; font-weight:bold;
            }
            .create-btn:hover { background: linear-gradient(90deg, #5d3fd3, #7a5cf3); }
            .back-btn {
                border:1px solid #8c6cf5; color:#8c6cf5; background:transparent;
                padding:10px 20px; border-radius:10px; text-decoration:none; font-weight:bold;
            }
            .back-btn:hover { background:#8c6cf5; color:#fff; }
        </style>
    </head>
    <body>
        <div class="form-container">
            <h2>Create Account</h2>
            <p class="subtitle">Join us and get started today</p>

            <?php if ($errors): ?>
                <div class="alert alert-error"><?= htmlspecialchars(implode(' ', $errors)) ?></div>
            <?php elseif ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required placeholder="e.g., John Doe">

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="At least 8 characters">

               <label for="profile_type">User Profile Type</label>
<select name="profile_type" id="profile_type" required>
    <option value="">-- Select Profile Type --</option>
    <?php foreach ($profiles as $profile): ?>
        <option value="<?= htmlspecialchars($profile['profile_type']) ?>">
            <?= htmlspecialchars($profile['profile_type']) ?>
        </option>
    <?php endforeach; ?>
</select>

                <div class="form-actions">
                    <button type="submit" class="create-btn">Create Account</button>
                    <a href="login.php" class="back-btn">Back to Login</a>
                </div>
            </form>
        </div>
    </body>
    </html>
    <?php
}

// 
handleCreateAccountPage();
