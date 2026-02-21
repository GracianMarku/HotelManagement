<?php
require_once "../connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reservation_id = (int)$_POST['reservation_id'];
    $new_status = $_POST['new_status'];

    $stmt = $conn->prepare("UPDATE reservations SET status=? WHERE id=?");
    $stmt->bind_param("si", $new_status, $reservation_id);
    $stmt->execute();

    header("Location: ../../reservation.php");
    exit();
}
?>