<?php
require_once "../connect.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: ../../payment.php");
  exit();
}

$reservation_id = (int)($_POST['reservation_id'] ?? 0);
$payment_id     = (int)($_POST['payment_id'] ?? 0);
$amount         = (float)($_POST['amount'] ?? 0);
$method         = $_POST['method'] ?? '';
$payment_date   = $_POST['payment_date'] ?? '';
$status         = $_POST['status'] ?? 'paid';

if ($reservation_id <= 0 || $payment_id <= 0 || $amount <= 0 || $method === '' || $payment_date === '') {
  header("Location: ./payment_details.php?reservation_id=$reservation_id&error=invalid");
  exit();
}

$stmt = $conn->prepare("UPDATE payments SET amount = ?, method = ?, payment_date = ?, status = ? WHERE id = ?");
$stmt->bind_param("dsssi", $amount, $method, $payment_date, $status, $payment_id);
$stmt->execute();

header("Location: ./payment_details.php?reservation_id=$reservation_id&success=1");
exit();

?>