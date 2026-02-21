<?php
require_once "../connect.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: ../../staff.php");
  exit();
}

$id        = (int)($_POST['id'] ?? 0);
$full_name = trim($_POST['full_name'] ?? '');
$role      = trim($_POST['role'] ?? '');
$phone     = trim($_POST['phone'] ?? '');
$email     = trim($_POST['email'] ?? '');
$hire_date = $_POST['hire_date'] ?? date('Y-m-d');
$salary    = (float)($_POST['salary'] ?? 0);

if ($id <= 0 || $full_name === '' || $role === '') {
  header("Location: ../../staff.php?error=missing");
  exit();
}

$stmt = $conn->prepare("
  UPDATE staff
  SET full_name=?, role=?, phone=?, email=?, hire_date=?, salary=?
  WHERE id=?
");
$stmt->bind_param("sssssdi", $full_name, $role, $phone, $email, $hire_date, $salary, $id);
$stmt->execute();

header("Location: ../../staff.php?success=1");
exit();

?>