<?php
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';
session_start();

/**
 * LogoutPage — handles all logout functionality in one file.
 * This is your Boundary + Controller combined.
 */
final class LogoutPage {

    public function __construct() {
        $this->logoutUser();
    }

    /**
     * Handles session cleanup (Controller behavior)
     */
    private function logoutUser(): void {
        // Clear all session variables
        $_SESSION = [];

        // Delete session cookie (for extra security)
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Destroy the session completely
        session_destroy();

        // Redirect user after logout or show confirmation
        $this->displayLogoutScreen();
    }

    /**
     * Displays logout confirmation page (Boundary behavior)
     */
    private function displayLogoutScreen(): void {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Logout Successful</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>
                body {
                    font-family: "Poppins", sans-serif;
                    background: linear-gradient(135deg, #6a5af9, #7a6cf7, #9278f8);
                    height: 100vh;
                    margin: 0;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }
                .logout-box {
                    background: #fff;
                    border-radius: 20px;
                    padding: 40px;
                    text-align: center;
                    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
                    width: 400px;
                    max-width: 90%;
                    animation: fadeIn 0.6s ease-in-out;
                }
                h2 {
                    color: #5d3fd3;
                    margin-bottom: 10px;
                }
                p {
                    color: #666;
                    margin-bottom: 25px;
                }
                a {
                    background: linear-gradient(90deg, #6e59e7, #8c6cf5);
                    color: white;
                    padding: 12px 25px;
                    border-radius: 10px;
                    text-decoration: none;
                    font-weight: bold;
                    transition: background 0.3s;
                }
                a:hover {
                    background: linear-gradient(90deg, #5d3fd3, #7a5cf3);
                }
                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(20px); }
                    to { opacity: 1; transform: translateY(0); }
                }
            </style>
        </head>
        <body>
            <div class="logout-box">
                <h2>You've been logged out</h2>
                <p>Thank you for using the system.</p>
                <a href="login.php">Return to Login</a>
            </div>
        </body>
        </html>
        <?php
    }
}

// ✅ Entry point
new LogoutPage();
?>
