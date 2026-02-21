<?php
require_once "../connect.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: ../../payment.php");
  exit();
}

$reservation_id = (int)($_POST['reservation_id'] ?? 0);
$amount         = (float)($_POST['amount'] ?? 0);
$payment_date   = $_POST['payment_date'] ?? date('Y-m-d');
$method         = $_POST['method'] ?? 'cash';
$status         = $_POST['status'] ?? 'paid';

if ($reservation_id <= 0 || $amount <= 0) {
  header("Location: ../../payment.php?error=missing");
  exit();
}

// kontrollo a ekziston reservation
$check = $conn->prepare("SELECT id FROM reservations WHERE id = ? LIMIT 1");
$check->bind_param("i", $reservation_id);
$check->execute();
$exists = $check->get_result();

if ($exists->num_rows === 0) {
  header("Location: ../../payment.php?error=reservation");
  exit();
}

// insert payment
$stmt = $conn->prepare("
  INSERT INTO payments (reservation_id, amount, payment_date, method, status)
  VALUES (?, ?, ?, ?, ?)
");
$stmt->bind_param("idsss", $reservation_id, $amount, $payment_date, $method, $status);
$stmt->execute();

header("Location: ../../payment.php?success=1");
exit();

?>