<?php
declare(strict_types=1);

use App\Entity\userAccount;
require_once dirname(__DIR__) . '/Entity/userAccount.php';

class CreateAccountPage
{
    /** Main entry point */
    public function handleRequest(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            [$errors, $success] = $this->processForm();
        } else {
            $errors = [];
            $success = '';
        }

        $this->displayPage($errors, $success);
    }

    /** Handles form validation and database logic */
    private function processForm(): array
    {
        $errors = [];
        $success = '';

        $name        = trim($_POST['name'] ?? '');
        $password    = trim($_POST['password'] ?? '');
        $profileType = strtolower(trim($_POST['profile_type'] ?? ''));

        // ✅ Basic validation
        if ($name === '' || $password === '') {
            $errors[] = 'Name and password are required.';
        }
        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters.';
        }
        if (!in_array($profileType, ['admin','csr','pin','platform'], true)) {
            $errors[] = 'Please select a valid profile type.';
        }

        if (!$errors) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            try {
                if (userAccount::checkUserExists($name)) {
                    $errors[] = 'A user with this name already exists.';
                } elseif (userAccount::insertUser($name, $hashed, $profileType)) {
                    $success = 'Account created successfully!';
                } else {
                    $errors[] = 'Failed to create account. Please try again.';
                }
            } catch (PDOException $e) {
                $errors[] = 'An internal error occurred. Please try again later.';
            }
        }

        return [$errors, $success];
    }

    /** Displays the form and messages */
    private function displayPage(array $errors, string $success): void
    {
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create User Account</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body {
      font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
      min-height:100vh; display:flex; align-items:center; justify-content:center;
      background:linear-gradient(135deg,#667eea 0%,#764ba2 100%); padding:20px;
    }
    .container {
      background:#fff; border-radius:24px; box-shadow:0 20px 60px rgba(0,0,0,.25);
      max-width:500px; width:100%; padding:40px;
    }
    h2 {
      text-align:center; font-size:2rem; margin-bottom:8px;
      background:linear-gradient(135deg,#667eea,#764ba2);
      -webkit-background-clip:text; -webkit-text-fill-color:transparent;
    }
    .subtitle { text-align:center; color:#666; margin-bottom:24px; }

    form {
      display:grid; gap:18px; width:100%;
    }

    label {
      font-weight:600; color:#333; margin-bottom:6px; display:block;
    }

    input, select {
      padding:16px 18px;          /* ⬆️ Bigger height */
      font-size:1.05rem;          /* ⬆️ Slightly larger text */
      border:2px solid #e0e0e0;
      border-radius:12px;
      background:#f8f9fa;
      width:100%;
      transition:all 0.3s ease;
    }

    input:focus, select:focus {
      outline:none; border-color:#667eea; background:#fff;
      box-shadow:0 0 0 4px rgba(102,126,234,.12);
    }

    button {
      padding:16px;
      border:none;
      border-radius:12px;
      background:linear-gradient(135deg,#667eea,#764ba2);
      color:#fff;
      font-weight:700;
      font-size:1rem;
      cursor:pointer;
      width:100%;                  /* full-width button */
      transition:all 0.3s ease;
    }
    button:hover {
      transform:translateY(-2px);
      box-shadow:0 6px 20px rgba(102,126,234,0.4);
    }

    .msg { padding:14px 16px; border-radius:12px; margin-bottom:20px; }
    .error { background:#fee2e2; color:#b91c1c; border-left:4px solid #ef4444; }
    .success { background:#dcfce7; color:#166534; border-left:4px solid #22c55e; }

    .alt-actions { text-align:center; margin-top:25px; }
    .link-btn {
      display:inline-block;
      padding:14px 24px;
      border:2px solid #667eea;
      border-radius:12px;
      color:#667eea;
      font-weight:700;
      text-decoration:none;
      transition:all 0.3s ease;
    }
    .link-btn:hover {
      background:#667eea;
      color:#fff;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Create Account</h2>
    <p class="subtitle">Join us and get started today</p>

    <?php if ($errors): ?>
      <div class="msg error">
        <?php foreach ($errors as $e): ?>
          <div>⚠️ <?= htmlspecialchars($e) ?></div>
        <?php endforeach; ?>
      </div>
    <?php elseif ($success): ?>
      <div class="msg success">✓ <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post" novalidate>
      <div>
        <label for="name">Full Name</label>
        <input id="name" name="name" type="text" placeholder="e.g., John Doe" required />
      </div>

      <div>
        <label for="password">Password</label>
        <input id="password" name="password" type="password" placeholder="At least 8 characters" required />
      </div>

      <div>
        <label for="profile_type">User Profile Type</label>
        <select id="profile_type" name="profile_type" required>
          <option value="">-- Select Profile Type --</option>
          <option value="admin">User Admin</option>
          <option value="csr">CSR Rep</option>
          <option value="pin">PIN (Person In Need)</option>
          <option value="platform">Platform Management</option>
        </select>
      </div>

      <button type="submit">Create Account</button>
    </form>

    <div class="alt-actions">
      <a class="link-btn" href="login.php">Back to Login</a>
    </div>
  </div>
</body>
</html>
<?php
    }
}

// Instantiate and handle request
$page = new CreateAccountPage();
$page->handleRequest();
