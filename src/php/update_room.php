<?php
require_once "connect.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../room.php");
    exit();
}

$id          = (int) $_POST['id'];
$room_number = $_POST['room_number'];
$double_bed  = $_POST['double_bed'];
$single_bed  = $_POST['single_bed'];
$price       = $_POST['price'];
$category    = $_POST['category'];

$sql = "UPDATE rooms SET
        room_number = ?,
        double_bed = ?,
        single_bed = ?,
        price = ?,
        category = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "siidsi",
    $room_number,
    $double_bed,
    $single_bed,
    $price,
    $category,
    $id
);

$stmt->execute();

header("Location: ../room.php");
exit();