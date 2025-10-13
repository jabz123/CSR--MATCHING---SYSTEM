<?php
declare(strict_types=1);

use App\Controller\LoginController;
require_once dirname(__DIR__) . '/Controller/LoginController.php';

session_start();

$controller = new LoginController();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $password = (string)($_POST['password'] ?? '');

    if ($user = $controller->authenticate($name, $password)) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['profile_type'] = $user['profile_type'];
        $_SESSION['name'] = $user['name'];

        header("Location: view_users.php");
        exit;
    } else {
        $errors = $controller->getErrors();
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #d946ef 100%);
      padding: 20px;
      position: relative;
      overflow: hidden;
    }
    
    body::before {
      content: '';
      position: absolute;
      width: 100%;
      height: 100%;
      background: 
        radial-gradient(circle at 10% 20%, rgba(99, 102, 241, 0.4) 0%, transparent 40%),
        radial-gradient(circle at 90% 80%, rgba(139, 92, 246, 0.4) 0%, transparent 40%);
      animation: pulse 8s ease-in-out infinite;
    }
    
    @keyframes pulse {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.8; }
    }
    
    .container {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 24px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      max-width: 440px;
      width: 100%;
      padding: 50px 40px;
      position: relative;
      z-index: 1;
      animation: fadeInUp 0.6s ease;
    }
    
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(40px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .logo {
      width: 70px;
      height: 70px;
      margin: 0 auto 20px;
      background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
      border-radius: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
      color: white;
      box-shadow: 0 8px 20px rgba(99, 102, 241, 0.4);
    }
    
    h2 {
      text-align: center;
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 8px;
      background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    
    .subtitle {
      text-align: center;
      color: #666;
      margin-bottom: 35px;
      font-size: 0.95rem;
    }
    
    form {
      display: grid;
      gap: 20px;
    }
    
    label {
      font-weight: 600;
      color: #333;
      margin-bottom: 6px;
      display: block;
      font-size: 0.9rem;
    }
    
    input {
      padding: 14px 16px;
      border: 2px solid #e0e0e0;
      border-radius: 12px;
      width: 100%;
      font-size: 1rem;
      transition: all 0.3s ease;
      background: #f8f9fa;
    }
    
    input:focus {
      outline: none;
      border-color: #6366f1;
      background: #fff;
      box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
      transform: translateY(-2px);
    }
    
    input:hover {
      border-color: #b8b8b8;
    }
    
    button {
      padding: 16px;
      border: none;
      border-radius: 12px;
      background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
      color: #fff;
      font-weight: 600;
      cursor: pointer;
      font-size: 1.05rem;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
      margin-top: 10px;
    }
    
    button:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(99, 102, 241, 0.6);
    }
    
    button:active {
      transform: translateY(0);
    }
    
    .msg {
      padding: 14px 16px;
      border-radius: 12px;
      background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
      color: #b91c1c;
      border-left: 4px solid #ef4444;
      margin-bottom: 20px;
      animation: shake 0.5s ease;
    }
    
    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      25% { transform: translateX(-10px); }
      75% { transform: translateX(10px); }
    }
    
    .alt-actions {
      text-align: center;
      margin-top: 30px;
      padding-top: 30px;
      border-top: 1px solid #e0e0e0;
    }
    
    .alt-actions p {
      color: #666;
      margin-bottom: 12px;
      font-size: 0.9rem;
    }
    
    .link-btn {
      display: inline-block;
      padding: 14px 24px;
      border: 2px solid #6366f1;
      border-radius: 12px;
      background: transparent;
      color: #6366f1;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.3s ease;
    }
    
    .link-btn:hover {
      background: #6366f1;
      color: #fff;
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
    }
    
    .input-group {
      position: relative;
    }
    
    .input-group:focus-within label {
      color: #6366f1;
    }
    
    @media (max-width: 600px) {
      .container {
        padding: 40px 30px;
      }
      
      h2 {
        font-size: 1.6rem;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="logo">🔐</div>
    <h2>Welcome Back</h2>
    <p class="subtitle">Please login to your account</p>

    <?php if (!empty($errors)): ?>
      <div class="msg">
        <?php foreach ($errors as $e): ?>
          <div>⚠️ <?= htmlspecialchars($e) ?></div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form method="post">
      <div class="input-group">
        <label>Name</label>
        <input name="name" required placeholder="Enter your name">
      </div>

      <div class="input-group">
        <label>Password</label>
        <input type="password" name="password" required placeholder="Enter your password">
      </div>

      <button type="submit">Sign In</button>
    </form>

    <div class="alt-actions">
      <p>Don't have an account?</p>
      <a class="link-btn" href="create_accountPg.php">Create Account</a>
    </div>
  </div>
</body>
</html>