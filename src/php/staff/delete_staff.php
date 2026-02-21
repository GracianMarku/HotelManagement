<?php
require_once "../connect.php";

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
  header("Location: ../../staff.php");
  exit();
}

$stmt = $conn->prepare("DELETE FROM staff WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: ../../staff.php?success=1");
exit();

?>