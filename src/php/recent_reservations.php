<?php
header('Content-Type: application/json; charset=utf-8');
require_once "./connect.php";

// 5 rezervimet e fundit (sipas id)
$sql = "
      SELECT r.id,
           r.check_in,
           r.check_out,
           r.status,
           ro.room_number,
           c.full_name
    FROM reservations r
    JOIN rooms ro ON ro.id = r.room_id
    JOIN customers c ON c.id = r.customer_id
    WHERE r.status IN ('reserved')
    ORDER BY r.id DESC
    LIMIT 5
";

$result = $conn->query($sql);

$data = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);