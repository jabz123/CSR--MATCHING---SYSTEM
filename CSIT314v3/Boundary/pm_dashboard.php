<?php
session_start();

require_once __DIR__ . '/../bootstrap.php';

// Check if user is logged in and has platform manager role
if (!isset($_SESSION['profile_type']) || 
    !in_array(strtolower(trim($_SESSION['profile_type'])), ['platform', 'pm'])) {
    header('Location: login.php');
    exit;
}

// Get username from session
$username = $_SESSION['username'] ?? 'Platform Manager';

// Handle logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Platform Manager Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Poppins", sans-serif;
            background: linear-gradient(135deg, #d7e2ff, #e5d9ff, #f1e7ff);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .dashboard-container {
            background: white;
            border-radius: 30px;
            padding: 60px 70px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
            width: 85%;
            max-width: 700px;
        }

        .welcome-text {
            font-size: 1.6rem;
            color: #7c5ce0;
            margin-bottom: 12px;
            font-weight: 600;
        }

        h1 {
            color: #6b4bd1;
            font-size: 2.4rem;
            margin-bottom: 40px;
            font-weight: 700;
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 25px;
            margin-bottom: 40px;
        }

        .btn {
            min-width: 180px;
            padding: 16px 35px;
            border: none;
            border-radius: 35px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s ease;
            text-decoration: none;
            display: inline-block;
            color: white;
        }

        .btn-primary {
            background: linear-gradient(90deg, #7c5ce0, #9b7df2);
            box-shadow: 0 8px 18px rgba(124, 92, 224, 0.25);
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #6a4bc4, #8c6cd8);
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(124, 92, 224, 0.35);
        }

        .btn-logout {
            background: linear-gradient(90deg, #ff6b6b, #ef5a5a);
            box-shadow: 0 8px 18px rgba(255, 107, 107, 0.25);
            width: 50%;
            max-width: 250px;
        }

        .btn-logout:hover {
            background: linear-gradient(90deg, #e94c4c, #d84343);
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(255, 107, 107, 0.35);
        }

        @media (max-width: 768px) {
            .dashboard-container {
                padding: 40px 25px;
            }

            .button-group {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <p class="welcome-text">Welcome, platform manager!</p>
        <h1>Platform Manager Dashboard</h1>
        
        <div class="button-group">
            <a href="pm_viewcategorypg.php" class="btn btn-primary">View categories</a>
            <a href="PMview_report.php" class="btn btn-primary">View report</a>
        </div>

        <a href="?action=logout" class="btn btn-logout">Logout</a>
    </div>
</body>
</html>
