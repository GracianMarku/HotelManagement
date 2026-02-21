

<?php
header('Content-Type: application/json; charset=utf-8');

require_once "./connect.php"; 

// 1) Total rooms
$totalRoomsRow = $conn->query("SELECT COUNT(*) AS total_rooms FROM rooms")->fetch_assoc();
$totalRooms = (int)($totalRoomsRow['total_rooms'] ?? 0);

// 2) Occupied rooms 
$occupiedRow = $conn->query("
    SELECT COUNT(DISTINCT room_id) AS occupied_rooms
    FROM reservations
    WHERE status = 'occupied'
")->fetch_assoc();
$occupiedRooms = (int)($occupiedRow['occupied_rooms'] ?? 0);

// 3) Available rooms
$availableRooms = $totalRooms - $occupiedRooms;
if ($availableRooms < 0) $availableRooms = 0;

// 4) Reservations count (vetëm reserved)
$reservedRow = $conn->query("
    SELECT COUNT(*) AS reserved_count
    FROM reservations
    WHERE status = 'reserved'
")->fetch_assoc();
$reservations = (int)($reservedRow['reserved_count'] ?? 0);

echo json_encode([
    "totalRooms" => $totalRooms,
    "occupiedRooms" => $occupiedRooms,
    "availableRooms" => $availableRooms,
    "reservations" => $reservations
]);
