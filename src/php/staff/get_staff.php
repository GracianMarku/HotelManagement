<?php
require_once "../connect.php";

$sql = "SELECT id, full_name, role, phone, email, hire_date, salary
        FROM staff
        ORDER BY id DESC";

$result = $conn->query($sql);

$rows = [];
while ($row = $result->fetch_assoc()) {
  $rows[] = $row;
}

header("Content-Type: application/json");
echo json_encode($rows);

?>