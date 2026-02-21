<?php

require_once "connect.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../room.php");
    exit();
}

$room_number = ($_POST['room_number']);
$double_bed  = (int) $_POST['double_bed'];
$single_bed  = (int) $_POST['single_bed'];
$price       = (float) $_POST['price'];
$category    = trim($_POST['category']);



/* Statusi vendoset këtu */
$status = "available";

$sql = "INSERT INTO rooms
        (room_number, double_bed, single_bed, price, category, status)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "siidss",
    $room_number,
    $double_bed,
    $single_bed,
    $price,
    $category,
    $status
);

$stmt->execute();

/* Kthehu te room.html */
header("Location: ../room.php");
exit();


// ---------------------------------------------------------------------------
    // include "connect.php";

    // $data = json_decode(file_get_contents("php://input"), true);
    

    //         $roomNumber = $data["roomNumber"];
    //         $doubleBed = $data["doubleBed"];
    //         $singleBed = $data["singleBed"];
    //         $price = $data["price"];
    //         $category = $data["category"];
    //         $status = $data["status"];


    //         $sql = "INSERT INTO rooms (room_number, double_bed, single_bed, price, category, status)
    //                 VALUES('$roomNumber', '$doubleBed', '$singleBed', '$price', '$category', '$status')";


    //         if(mysqli_query($conn, $sql)){
    //             echo json_encode(["message" => "Dhoma u krijua me sukses"]);
    //         }        
    //         else {
    //             echo json_encode(["message" => "Gabim: " . mysqli_error($conn)]);
    //         }
    

?>