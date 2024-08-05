<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['roll_no'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $roll_no = $_SESSION['roll_no'];
    $semester = $_POST['semester'];

    $stmt = $pdo->prepare("SELECT * FROM attendance WHERE roll_no = ? AND semester = ?");
    $stmt->execute([$roll_no, $semester]);
    $attendance = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h3>Attendance for $semester</h3>";
    echo "<table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>";
    foreach ($attendance as $entry) {
        echo "<tr>
                <td>" . htmlspecialchars($entry['date']) . "</td>
                <td>" . htmlspecialchars($entry['status']) . "</td>
              </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<p class='error'>Invalid request method.</p>";
}
?>
