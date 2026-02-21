<?php
require_once "connect.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../customer.php");
    exit();
}

$id = (int) $_POST['id'];

$sql = "UPDATE customers SET
        full_name = ?,
        phone = ?,
        email = ?,
        city = ?,
        country = ?,
        document_type = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssssssi",
    $_POST['full_name'],
    $_POST['phone'],
    $_POST['email'],
    $_POST['city'],
    $_POST['country'],
    $_POST['document_type'],
    $id
);

$stmt->execute();

header("Location: ../customer.php");
exit();