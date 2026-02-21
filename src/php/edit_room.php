<?php

    require_once "connect.php";

    $id = $_GET['id'];

    if(!$id)
    {
        header("Location: room.php");
        exit();
    }

    $result = $conn->query("SELECT * FROM rooms WHERE id = $id");
    $room = $result->fetch_assoc();

    if(!$room){
        die("Room not found");
    }


    ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../output.css">
</head>
<body>
    
<!-- Sidebar -->

    <aside class="w-64 h-screen bg-[#0b0b3b] text-white flex flex-col justify-between fixed">

        <div>
            <h1 class="text-2x1 font-bold text-center py-6 border-b border-gray-600">Hotel Admin</h1>

            <nav class="mt-6 flex flex-col gap-10 px-4 ">
                <a href="../dashboard.html" class="text-gray-200 font-bold hover:bg-indigo-900 px-4 py-2 rounded-lg shadow-md">Dashboard</a>
                <a href="../room.php" class="text-gray-200 font-bold hover:bg-indigo-900 px-4 py-2 rounded-lg transition-colors duration-200">Rooms</a>
                <a href="" class="text-gray-200 font-bold hover:bg-indigo-900 px-4 py-2 rounded-lg">Reservations</a>
                <a href="./customer.php" class="text-gray-200 font-bold hover:bg-indigo-900 px-4 py-2 rounded-lg">Customers</a>
                <a href="#" class="text-gray-200 font-bold hover:bg-indigo-900 px-4 py-2 rounded-lg">Payments</a>
                <a href="#" class="text-gray-200 font-bold hover:bg-indigo-900 px-4 py-2 rounded-lg">Staff</a>

            </nav>
        </div>


          <!-- Logout -->
        <form action="./exit.php" method="post">
        <div class="px-4 mb-4">
            <button class="w-full bg-red-700 hover:bg-red-800 py-2 rounded-lg font-semibold">Logoutiiii</button>
        </div>
        </form>

    </aside>


    <main class="flex p-10 ml-64">
    <div class="w-96 bg-gray-200 p-6 rounded-xl shadow">
    <h2 class="text-xl font-semibold mb-4 text-amber-700">Edit Room</h2>

    <form action="update_room.php" method="POST" class="space-y-3">

      <input type="hidden" name="id" value="<?= $room['id'] ?>">

      <div>
        <label>Room number</label>
        <input type="number" name="room_number"
               value="<?= $room['room_number'] ?>"
               class="w-full p-2 rounded border">
      </div>

      <div>
        <label>Double bed</label>
        <input type="number" name="double_bed"
               value="<?= $room['double_bed'] ?>"
               class="w-full p-2 rounded border">
      </div>

      <div>
        <label>Single bed</label>
        <input type="number" name="single_bed"
               value="<?= $room['single_bed'] ?>"
               class="w-full p-2 rounded border">
      </div>

      <div>
        <label>Price</label>
        <input type="number" name="price"
               value="<?= $room['price'] ?>"
               class="w-full p-2 rounded border">
      </div>

      <div>
        <label>Category</label>
        <input type="text" name="category"
               value="<?= $room['category'] ?>"
               class="w-full p-2 rounded border">
      </div>

      <button class="w-full mt-3 bg-blue-600 text-white p-2 rounded">
        Update Room
      </button>

    </form>
  </div>
</main>

</body>
</html>


