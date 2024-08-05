<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['roll_no'])) {
    header('Location: login.php');
    exit();
}

$roll_no = $_SESSION['roll_no'];
$stmt = $pdo->prepare("SELECT * FROM students WHERE roll_no = ?");
$stmt->execute([$roll_no]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    echo "<p class='error'>No student data found.</p>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_year'])) {
        $year = $_POST['year'];
        $stmt = $pdo->prepare("UPDATE students SET year = ? WHERE roll_no = ?");
        $stmt->execute([$year, $roll_no]);
        header('Location: dashboard.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Student Management System</title>
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
            padding: 20px;
        }
        .header {
            background: rgba(0, 0, 0, 0.7);
            padding: 10px;
            text-align: center;
        }
        .profile-card, .form-card {
            background: rgba(0, 0, 0, 0.7);
            margin: 10px auto;
            padding: 20px;
            border-radius: 8px;
            max-width: 800px;
            width: 100%;
        }
        img.profile-pic {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #fff;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #333;
        }
        .form-card form {
            display: flex;
            flex-direction: column;
        }
        .form-card input, .form-card select {
            margin: 10px 0;
            padding: 10px;
            border: none;
            border-radius: 4px;
        }
        .form-card input[type="submit"] {
            background-color: #28a745;
            color: #fff;
            cursor: pointer;
        }
        .form-card input[type="submit"]:hover {
            background-color: #218838;
        }
        .form-card h2 {
            margin-top: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>Student Dashboard</h1>
            <nav>
                <a href="logout.php" style="color: #d4edda;">Logout</a>
            </nav>
        </header>

        <div class="profile-card">
            <img src="<?php echo htmlspecialchars($student['picture']); ?>" alt="Profile Picture" class="profile-pic">
            <div class="profile-info">
                <p><strong>Roll No:</strong> <?php echo htmlspecialchars($student['roll_no']); ?></p>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($student['name']); ?></p>
                <p><strong>Year:</strong> <?php echo htmlspecialchars($student['year']); ?></p>
                <p><strong>Branch:</strong> <?php echo htmlspecialchars($student['branch']); ?></p>
                <p><strong>Mobile No:</strong> <?php echo htmlspecialchars($student['mobile_no']); ?></p>
            </div>
        </div>

        <div class="form-card">
            <h2>Update Year</h2>
            <form method="POST">
                <label for="year">Year:</label>
                <select id="year" name="year" required>
                    <option value="I-I" <?php echo $student['year'] == 'I-I' ? 'selected' : ''; ?>>I-I</option>
                    <option value="I-II" <?php echo $student['year'] == 'I-II' ? 'selected' : ''; ?>>I-II</option>
                    <option value="II-I" <?php echo $student['year'] == 'II-I' ? 'selected' : ''; ?>>II-I</option>
                    <option value="II-II" <?php echo $student['year'] == 'II-II' ? 'selected' : ''; ?>>II-II</option>
                    <option value="III-I" <?php echo $student['year'] == 'III-I' ? 'selected' : ''; ?>>III-I</option>
                    <option value="III-II" <?php echo $student['year'] == 'III-II' ? 'selected' : ''; ?>>III-II</option>
                    <option value="IV-I" <?php echo $student['year'] == 'IV-I' ? 'selected' : ''; ?>>IV-I</option>
                    <option value="IV-II" <?php echo $student['year'] == 'IV-II' ? 'selected' : ''; ?>>IV-II</option>
                </select>
                <input type="submit" name="update_year" value="Update Year">
            </form>
        </div>

        <div class="form-card">
            <h2>Results</h2>
            <form method="POST" action="dashboard.php">
                <label for="results-semester">Semester:</label>
                <select id="results-semester" name="semester" required>
                    <option value="I-I">I-I</option>
                    <option value="I-II">I-II</option>
                    <option value="II-I">II-I</option>
                    <option value="II-II">II-II</option>
                    <option value="III-I">III-I</option>
                    <option value="III-II">III-II</option>
                    <option value="IV-I">IV-I</option>
                    <option value="IV-II">IV-II</option>
                </select>
                <input type="submit" name="action" value="View Results">
            </form>

            <?php
            if (isset($_POST['action']) && $_POST['action'] == 'View Results') {
                $semester = $_POST['semester'];
                $stmt = $pdo->prepare("SELECT * FROM results WHERE roll_no = ? AND semester = ?");
                $stmt->execute([$roll_no, $semester]);
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                echo "<h3>Results for $semester</h3>";
                echo "<table>
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Grade</th>
                            </tr>
                        </thead>
                        <tbody>";
                foreach ($results as $result) {
                    echo "<tr>
                            <td>" . htmlspecialchars($result['subject']) . "</td>
                            <td>" . htmlspecialchars($result['grade']) . "</td>
                          </tr>";
                }
                echo "</tbody></table>";
            }
            ?>
        </div>

        <div class="form-card">
            <h2>Attendance</h2>
            <form method="POST" action="dashboard.php">
                <label for="attendance-semester">Semester:</label>
                <select id="attendance-semester" name="semester" required>
                    <option value="I-I">I-I</option>
                    <option value="I-II">I-II</option>
                    <option value="II-I">II-I</option>
                    <option value="II-II">II-II</option>
                    <option value="III-I">III-I</option>
                    <option value="III-II">III-II</option>
                    <option value="IV-I">IV-I</option>
                    <option value="IV-II">IV-II</option>
                </select>
                <input type="submit" name="action" value="View Attendance">
            </form>

            <?php
            if (isset($_POST['action']) && $_POST['action'] == 'View Attendance') {
                $semester = $_POST['semester'];
                $stmt = $pdo->prepare("SELECT COUNT(*) AS total_days, SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) AS present_days FROM attendance WHERE roll_no = ? AND semester = ?");
                $stmt->execute([$roll_no, $semester]);
                $attendance = $stmt->fetch(PDO::FETCH_ASSOC);

                echo "<h3>Attendance for $semester</h3>";
                echo "<p>Total Days: " . htmlspecialchars($attendance['total_days']) . "</p>";
                echo "<p>Present Days: " . htmlspecialchars($attendance['present_days']) . "</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
