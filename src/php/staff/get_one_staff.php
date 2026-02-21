<?php
require_once "../connect.php";

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
  http_response_code(400);
  echo json_encode(["error" => "Invalid id"]);
  exit();
}

$stmt = $conn->prepare("SELECT id, full_name, role, phone, email, hire_date, salary FROM staff WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
  http_response_code(404);
  echo json_encode(["error" => "Not found"]);
  exit();
}

header("Content-Type: application/json");
echo json_encode($res->fetch_assoc());

?>