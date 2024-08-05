<?php
session_start();
include 'db_connect.php';

$show_success_message = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $year = $_POST['year'];
    $branch = $_POST['branch'];
    $roll_no = $_POST['roll_no'];
    $mobile_no = $_POST['mobile_no'];
    $password = $_POST['password'];

    // Hash the password using Bcrypt
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $picture = '';

    if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
        $picture = 'uploads/' . basename($_FILES['picture']['name']);
        move_uploaded_file($_FILES['picture']['tmp_name'], $picture);
    }

    $stmt = $pdo->prepare("INSERT INTO students (name, year, branch, roll_no, mobile_no, password, picture) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$name, $year, $branch, $roll_no, $mobile_no, $hashed_password, $picture])) {
        // Set a flag to show the success message
        $show_success_message = true;
        header('Location: signup.php?status=success');
        exit();
    } else {
        $error_message = "Error: Could not register.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
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
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        input[type="text"], input[type="password"], input[type="file"], select {
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
        .login-link {
            margin-top: 15px;
            font-size: 14px;
        }
        .login-link a {
            color: #fff;
            text-decoration: underline;
        }
        .login-link a:hover {
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
        .notification {
            display: none;
            background-color: #28a745;
            color: #fff;
            padding: 10px;
            text-align: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            font-size: 16px;
        }
        .notification.show {
            display: block;
        }
    </style>
</head>
<body>
    <div class="notification" id="notification">
        Registration successful! Redirecting to login page...
    </div>

    <div class="container">
        <div class="form-container">
            <h1>Sign Up</h1>
            <?php if (isset($error_message)) : ?>
                <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data">
                Name: <input type="text" name="name" required><br>
                Year: <select name="year" required>
                    <option value="I-I">I-I</option>
                    <option value="I-II">I-II</option>
                    <option value="II-I">II-I</option>
                    <option value="II-II">II-II</option>
                    <option value="III-I">III-I</option>
                    <option value="III-II">III-II</option>
                    <option value="IV-I">IV-I</option>
                    <option value="IV-II">IV-II</option>
                </select><br>
                Branch: <select name="branch" required>
                    <option value="CSE">CSE</option>
                    <option value="CSM">CSM</option>
                    <option value="CSD">CSD</option>
                    <option value="IT">IT</option>
                    <option value="ECE">ECE</option>
                    <option value="EEE">EEE</option>
                    <option value="CIVIL">CIVIL</option>
                    <option value="MECHANICAL">MECHANICAL</option>
                    <option value="CHEMICAL">CHEMICAL</option>
                </select><br>
                Roll No: <input type="text" name="roll_no" required><br>
                Mobile No: <input type="text" name="mobile_no" required><br>
                Password:
                <div class="password-container">
                    <input type="password" name="password" id="password" required>
                    <span class="eye-icon" onclick="togglePasswordVisibility()">
                        👁️
                    </span>
                </div><br>
                Upload Picture: <input type="file" name="picture"><br>
                <input type="submit" value="Sign Up">
            </form>
            <div class="login-link">
                <p>Already have an account? <a href="login.php">Login</a></p>
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

        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('status') && urlParams.get('status') === 'success') {
                const notification = document.getElementById('notification');
                notification.classList.add('show');
                setTimeout(() => {
                    window.location.href = 'login.php';
                }, 2000); // Redirect after 2 seconds
            }
        }
    </script>
</body>
</html>
