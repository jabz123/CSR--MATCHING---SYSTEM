<?php
declare(strict_types=1);

use App\Controller\CreateAccountController;
use App\Entity\userAccount;


require_once dirname(__DIR__) . '/Controller/CreateAccountController.php';
require_once dirname(__DIR__) . '/Entity/userAccount.php';

class create_accountPg
{
    private CreateAccountController $controller;
    private array $errors = [];
    private string $success = '';

    public function __construct()
    {
        $this->controller = new CreateAccountController();
    }

    /** Displays the account creation form */
    public function displayAccountForm(): void
    {
        // The form is part of the page design below
    }

    /** Displays success message */
    public function displaySuccess(string $message): void
    {
        echo "<div class='msg success'>✓ " . htmlspecialchars($message) . "</div>";
    }

    /** Displays error messages */
    public function displayErrors(array $errors): void
    {
        echo "<div class='msg error'>";
        foreach ($errors as $e) {
            echo "<div>⚠️ " . htmlspecialchars($e) . "</div>";
        }
        echo "</div>";
    }

    /** Handles form submission and response logic */
    public function handleRequest(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // ✅ Trim input (Boundary responsibility)
            $name = trim($_POST['name'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $profileType = strtolower(trim($_POST['profile_type'] ?? ''));

            // ✅ Basic validation (Boundary responsibility)
            if ($name === '' || $password === '') {
                $this->errors[] = 'Name and password are required.';
            }

            if (strlen($password) < 8) {
                $this->errors[] = 'Password must be at least 8 characters.';
            }

            // ✅ Allowed profile types validation
            $validProfiles = ['admin', 'csr', 'pin', 'platform'];
            if (!in_array($profileType, $validProfiles, true)) {
                $this->errors[] = 'Please select a valid profile type.';
            }

            // Only proceed if validation passes
            if (empty($this->errors)) {
                // ✅ Hash the password (Boundary responsibility)
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // ✅ Boundary handles checkUserExists() and insertUser()
                try {
                    // Check if user already exists
                    if (userAccount::checkUserExists($name)) {
                        $this->errors[] = 'A user with this name already exists.';
                    } else {
                        // Insert user into database
                        if (userAccount::insertUser($name, $hashedPassword, $profileType)) {
                            $this->success = 'Account created successfully!';
                        }
                    }
                } catch (PDOException $e) {
                    // Entity throws database errors, boundary catches them
                    $this->errors[] = 'An internal error occurred. Please try again later.';
                }
            }
        }

        $this->render();
    }

    /** Renders the page (form + messages) */
    private function render(): void
    {
        $errors = $this->errors;
        $success = $this->success;
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create User Account</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      padding: 20px;
      position: relative;
      overflow: hidden;
    }
    body::before {
      content: '';
      position: absolute;
      width: 200%;
      height: 200%;
      background:
        radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(252, 70, 107, 0.3) 0%, transparent 50%);
      animation: gradientShift 15s ease infinite;
    }
    @keyframes gradientShift {
      0%, 100% { transform: translate(0, 0); }
      50% { transform: translate(-50px, -50px); }
    }
    .container {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 24px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      max-width: 500px;
      width: 100%;
      padding: 40px;
      position: relative;
      z-index: 1;
      animation: slideUp 0.6s ease;
    }
    @keyframes slideUp {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }
    h2 {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 10px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      text-align: center;
    }
    .subtitle { text-align: center; color: #666; margin-bottom: 30px; font-size: 0.95rem; }
    form { display: grid; gap: 20px; }
    label { font-weight: 600; color: #333; margin-bottom: 6px; display: block; font-size: 0.9rem; transition: color 0.3s; }
    input, select {
      padding: 14px 16px;
      border: 2px solid #e0e0e0;
      border-radius: 12px;
      width: 100%;
      font-size: 1rem;
      transition: all 0.3s ease;
      background: #f8f9fa;
    }
    input:focus, select:focus {
      outline: none; border-color: #667eea; background: #fff;
      box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1); transform: translateY(-2px);
    }
    button {
      padding: 16px; border: none; border-radius: 12px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: #fff; font-weight: 600; cursor: pointer; font-size: 1.05rem;
      transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4); margin-top: 10px;
    }
    button:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6); }
    .msg { padding: 14px 16px; border-radius: 12px; margin-bottom: 20px; animation: slideIn 0.4s ease; }
    @keyframes slideIn { from { opacity: 0; transform: translateX(-20px); } to { opacity: 1; transform: translateX(0); } }
    .error { background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); color: #b91c1c; border-left: 4px solid #ef4444; }
    .success { background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); color: #166534; border-left: 4px solid #22c55e; }
    .alt-actions { text-align: center; margin-top: 25px; padding-top: 25px; border-top: 1px solid #e0e0e0; }
    .alt-actions p { color: #666; margin-bottom: 12px; font-size: 0.9rem; }
    .link-btn { display: inline-block; padding: 14px 24px; border: 2px solid #667eea; border-radius: 12px;
      background: transparent; color: #667eea; font-weight: 600; text-decoration: none; text-align: center;
      transition: all 0.3s ease;
    }
    .link-btn:hover { background: #667eea; color: #fff; transform: translateY(-2px); box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3); }
  </style>
</head>
<body>
  <div class="container">
    <h2>Create Account</h2>
    <p class="subtitle">Join us and get started today</p>

    <?php
    if (!empty($errors)) {
        $this->displayErrors($errors);
    } elseif ($success) {
        $this->displaySuccess($success);
    }
    ?>

    <form method="post">
      <div class="input-group">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" placeholder="e.g., John Doe" required>
      </div>

      <div class="input-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="At least 8 characters" required>
      </div>

      <div class="input-group">
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
      <p>Already have an account?</p>
      <a class="link-btn" href="login.php">Back to Login</a>
    </div>
  </div>
</body>
</html>
<?php
    }
}

// Instantiate and handle request
$view = new create_accountPg();
$view->handleRequest();
?>