<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['roll_no'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $roll_no = $_SESSION['roll_no'];
    $year = $_POST['year'];

    if (!in_array($year, ['I', 'II', 'III', 'IV'])) {
        echo "<p class='error'>Invalid year selected.</p>";
        exit();
    }

    $stmt = $pdo->prepare("UPDATE students SET year = ? WHERE roll_no = ?");
    $stmt->execute([$year, $roll_no]);

    header('Location: dashboard.php');
    exit();
} else {
    echo "<p class='error'>Invalid request method.</p>";
}
?>
