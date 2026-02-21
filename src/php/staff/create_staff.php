<?php
<?php
require_once "../connect.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: ../../staff.php");
  exit();
}

$full_name = trim($_POST['full_name'] ?? '');
$role      = trim($_POST['role'] ?? '');
$phone     = trim($_POST['phone'] ?? '');
$email     = trim($_POST['email'] ?? '');
$hire_date = $_POST['hire_date'] ?? date('Y-m-d');
$salary    = (float)($_POST['salary'] ?? 0);

if ($full_name === '' || $role === '') {
  header("Location: ../../staff.php?error=missing");
  exit();
}

$stmt = $conn->prepare("
  INSERT INTO staff (full_name, role, phone, email, hire_date, salary)
  VALUES (?, ?, ?, ?, ?, ?)
");
$stmt->bind_param("sssssd", $full_name, $role, $phone, $email, $hire_date, $salary);
$stmt->execute();

header("Location: ../../staff.php?success=1");
exit();

?>