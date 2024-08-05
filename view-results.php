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
} else {
    echo "<p class='error'>Invalid request method.</p>";
}
?>
