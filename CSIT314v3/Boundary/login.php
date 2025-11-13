<?php
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';
session_start();

require_once __DIR__ . '/../Controller/LoginController.php';
use App\Controller\LoginController;

$error = '';

function processForm(): bool {
    global $error;

    $name     = trim((string)($_POST['name'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    if ($name === '' || $password === '') {
        $error = 'Please enter both username and password.';
        return false;
    }

    try {
        $ctl  = new LoginController();

        $user = $ctl->login($name);
        if (!$user) {
            $error = 'Invalid username or password.';
            return false;
        }

        if (strtolower((string)$user['status']) !== 'active') {
            $error = 'Account is not active.';
            return false;
        }

        if (!password_verify($password, (string)$user['password_hash'])) {
            $error = 'Invalid username or password.';
            return false;
        }

        // success: set session only (no redirect here)
        $_SESSION['user_id']      = (int)$user['id'];
        $_SESSION['name']         = (string)$user['name'];
        $_SESSION['profile_type'] = strtolower((string)$user['profile_type']);
        $_SESSION['status']       = 'active';

        return true;

    } catch (\Throwable $e) {
        $error = 'Login error. Please try again.';
        error_log('Login failed: '.$e->getMessage());
        return false;
    }
}

function loginRedirectUrl(string $ptype): string {
    switch ($ptype) {
        case 'platform': return 'pm_dashboard.php';
        case 'admin':    return 'admin_dashboard.php';
        case 'csr':      return 'csr_dashboard.php';
        case 'pin':      return 'pin_dashboard.php';
        default:         return 'user_dashboard.php';
    }
}

/** 🔹 Display login form */
function displayPage(): void {
    global $error;
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            body {
                font-family: "Poppins", sans-serif;
                background: linear-gradient(135deg, #6a5af9, #7a6cf7, #9278f8);
                margin: 0;
                padding: 0;
                height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .login-container {
                background: #fff;
                border-radius: 20px;
                padding: 40px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
                width: 400px;
                max-width: 90%;
                text-align: center;
            }
            h2 { color: #5d3fd3; margin-bottom: 5px; }
            p.subtitle { color: #666; font-size: 14px; margin-bottom: 25px; }
            label {
                display: block;
                text-align: left;
                font-weight: 600;
                margin-bottom: 5px;
                color: #333;
            }
            input {
                width: 90%;
                height: 45px;
                padding: 0 14px;
                border-radius: 10px;
                border: 1.5px solid #ccc;
                margin-bottom: 20px;
                font-size: 15px;
                background: #f9f9f9;
                transition: all 0.25s ease;
                display: block;
                margin-left: auto;
                margin-right: auto;
            }
            input:focus {
                border-color: #7a5cf3;
                outline: none;
                box-shadow: 0 0 5px rgba(122, 92, 243, 0.4);
            }
            .login-btn {
                width: 90%;
                background: linear-gradient(90deg, #6e59e7, #8c6cf5);
                color: white;
                border: none;
                padding: 12px;
                border-radius: 10px;
                cursor: pointer;
                font-weight: bold;
                transition: background 0.3s;
            }
            .login-btn:hover {
                background: linear-gradient(90deg, #5d3fd3, #7a5cf3);
            }
            .error-message {
                background: #f8d7da;
                color: #842029;
                border: 1px solid #f5c2c7;
                padding: 10px;
                border-radius: 10px;
                margin-bottom: 15px;
                font-size: 14px;
            }
        </style>
    </head>
    <body>
        <div class="login-container">
            <h2>Welcome Back</h2>
            <p class="subtitle">Please log in to continue</p>

            <?php if (!empty($error)): ?>
                <div class="error-message">⚠️ <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <label for="name">Username</label>
                <input type="text" id="name" name="name" placeholder="Enter your username" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>

                <button type="submit" class="login-btn">Login</button>
            </form>
        </div>
    </body>
    </html>
    <?php
}

/* -------- PAGE EXECUTION -------- */
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (processForm()) {
        $ptype = strtolower((string)($_SESSION['profile_type'] ?? ''));
        header('Location: ' . loginRedirectUrl($ptype));
        exit;
    }
}
displayPage();
