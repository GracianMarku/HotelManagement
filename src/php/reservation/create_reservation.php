<?php
require_once "../connect.php";


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../reservation.php");
    exit();
}

$room_id     = (int)($_POST['room_id'] ?? 0);
$customer_id = (int)($_POST['customer_id'] ?? 0);
$check_in    = $_POST['check_in'] ?? null;
$check_out   = $_POST['check_out'] ?? null;
$status      = 'reserved';

if (!$room_id || !$customer_id || !$check_in || !$check_out) {
    header("Location: ../../reservation.php?error=missing");
    exit();
}

if ($check_in >= $check_out) {
    header("Location: ../../reservation.php?error=dates");
    exit();
}

/*
  Overlap rule (e saktë):
  ekziston konflikt nëse:
  existing.check_in < new.check_out
  AND existing.check_out > new.check_in

  Kjo lejon bookings back-to-back:
  p.sh. (24-25) dhe (25-26) -> JO konflikt
*/
$conflictStmt = $conn->prepare("
    SELECT id
    FROM reservations
    WHERE room_id = ?
      AND status IN ('reserved','occupied')
      AND (check_in < ? AND check_out > ?)
    LIMIT 1
");
$conflictStmt->bind_param("iss", $room_id, $check_out, $check_in);
$conflictStmt->execute();
$conflictResult = $conflictStmt->get_result();

if ($conflictResult->num_rows > 0) {
    header("Location: ../../reservation.php?error=occupied");
    exit();
}

$stmt = $conn->prepare("
    INSERT INTO reservations (room_id, customer_id, check_in, check_out, status)
    VALUES (?, ?, ?, ?, ?)
");
$stmt->bind_param("iisss", $room_id, $customer_id, $check_in, $check_out, $status);
$stmt->execute();

header("Location: ../../reservation.php?success=1");
exit();


?>