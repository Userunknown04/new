<?php
session_start();
include 'db_connect.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $roll_no = $_POST['roll_no'];
    $password = $_POST['password'];

    // Fetch user from the database
    $stmt = $pdo->prepare("SELECT * FROM students WHERE roll_no = ?");
    $stmt->execute([$roll_no]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user exists and password is correct
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['roll_no'] = $roll_no;
        header('Location: dashboard.php');
        exit();
    } else {
        $error_message = "Invalid login credentials.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            background-image: url('logo.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            margin: 0;
            font-family: Arial, sans-serif;
            color: #fff;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 8px;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        input[type="text"], input[type="password"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 4px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            text-align: center;
        }
        .signup-link {
            margin-top: 15px;
            font-size: 14px;
        }
        .signup-link a {
            color: #fff;
            text-decoration: underline;
        }
        .signup-link a:hover {
            text-decoration: none;
        }
        .password-container {
            position: relative;
            width: 100%;
        }
        .password-container input[type="password"] {
            padding-right: 30px;
        }
        .password-container .eye-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>Login</h1>
            <?php if (isset($error_message)) : ?>
                <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
            <form method="POST">
                Roll No: <input type="text" name="roll_no" required><br>
                Password:
                <div class="password-container">
                    <input type="password" name="password" id="password" required>
                    <span class="eye-icon" onclick="togglePasswordVisibility()">
                        👁️
                    </span>
                </div><br>
                <input type="submit" value="Login">
            </form>
            <div class="signup-link">
                <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
            </div>
        </div>
    </div>
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.querySelector('.eye-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.textContent = '🙈'; // Eye closed icon
            } else {
                passwordInput.type = 'password';
                eyeIcon.textContent = '👁️'; // Eye open icon
            }
        }
    </script>
</body>
</html>
