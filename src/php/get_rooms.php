<?php

require_once "connect.php";

$result = $conn->query("SELECT * FROM rooms ORDER BY id DESC");

while ($row = $result->fetch_assoc()) {
    echo "
    <div class='bg-gray-100 p-4 rounded-xl shadow'>
      <h3 class='font-bold text-lg'>Room {$row['room_number']}</h3>
      <p>Double Bed: {$row['double_bed']}</p>
      <p>Single Bed: {$row['single_bed']}</p>
      <p>Price: {$row['price']} €</p>
      <p>Category: {$row['category']}</p>

      <a href='php/edit_room.php?id={$row['id']}'
        class = 'inline-block mt-3 px-4 py-1 bg-yellow-500 rounded hover:bg-yellow-600'> Edit </a>
      <a href='php/delete_room.php?id={$row['id']}'
        class = 'inline-block mt-3 px-4 py-1 bg-red-500 rounded hover:bg-red-600' onclick='return confirm(\"Are you sure you want to delete?\")'> Delete </a>
    </div>
    ";
}








// require_once "connect.php";

// $result = $conn->query("SELECT * FROM rooms ORDER BY id DESC");

// while ($row = $result->fetch_assoc()) {
//     echo "
//     <div class='border rounded p-4'>
//         <h3 class='font-bold'>Room {$row['room_number']}</h3>
//         <p>Double Beds: {$row['double_bed']}</p>
//         <p>Single Beds: {$row['single_bed']}</p>
//         <p>Price: {$row['price']} €</p>
//         <p>Category: {$row['category']}</p>
//         <p>Status: {$row['status']}</p>
//     </div>
//     ";
// }



// ----------------------------------------------------------------
// include "connect.php";

// $sql = "SELECT * FROM rooms ORDER BY id DESC";
// $result = mysqli_query($conn, $sql);

// $rooms = [];

// while($row = mysqli_fetch_assoc($result))
// {
//     $rooms[] = $row;
// }

// echo json_encode($rooms);


?>