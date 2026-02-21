<?php
require_once "../connect.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: ../../payment.php");
  exit();
}

$reservation_id = (int)($_POST['reservation_id'] ?? 0);
$payment_id     = (int)($_POST['payment_id'] ?? 0);

if ($reservation_id <= 0 || $payment_id <= 0) {
  header("Location: ../../payment.php");
  exit();
}

$stmt = $conn->prepare("DELETE FROM payments WHERE id = ?");
$stmt->bind_param("i", $payment_id);
$stmt->execute();

header("Location: ./payment_details.php?reservation_id=$reservation_id&success=1");
exit();

?>