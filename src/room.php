<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="./output.css" rel="stylesheet"/>
</head>
<body>

<!-- Sidebar -->

    <aside class="w-64 h-screen bg-[#0b0b3b] text-white flex flex-col justify-between fixed">

        <div>
            <h1 class="text-2x1 font-bold text-center py-6 border-b border-gray-600">Hotel Admin</h1>

            <nav class="mt-6 flex flex-col gap-10 px-4 ">
                <a href="./dashboard.html" class="text-gray-200 font-bold hover:bg-indigo-900 px-4 py-2 rounded-lg shadow-md">Dashboard</a>
                <a href="./room.php" class="text-gray-200 font-bold hover:bg-indigo-900 px-4 py-2 rounded-lg transition-colors duration-200">Rooms</a>
                <a href="./reservation.php" class="text-gray-200 font-bold hover:bg-indigo-900 px-4 py-2 rounded-lg">Reservations</a>
                <a href="./customer.php" class="text-gray-200 font-bold hover:bg-indigo-900 px-4 py-2 rounded-lg">Customers</a>
                <a href="./payment.php" class="text-gray-200 font-bold hover:bg-indigo-900 px-4 py-2 rounded-lg">Payments</a>
                <a href="./staff.php" class="text-gray-200 font-bold hover:bg-indigo-900 px-4 py-2 rounded-lg">Staff</a>
            </nav>
        </div>


        <!-- Logout -->
        <form action="./login.html" method="post">
        <div class="px-4 mb-4">
            <button class="w-full bg-red-700 hover:bg-red-800 py-2 rounded-lg font-semibold">Logout</button>
        </div>
        </form>

    </aside>


    
<main class="flex p-10 ml-64 gap-10 items-start">

  <!-- CREATE ROOM FORM -->
  <div class="w-80 bg-gray-200 p-6 rounded-xl shadow h-fit sticky top-10 self-start">
    <h2 class="text-xl font-semibold mb-4">Create Room</h2>

    <form action="./php/create_room.php" method="POST" class="space-y-3">

      <div>
        <label class="block font-medium">Room number:</label>
        <input type="number" name="room_number" required
               class="w-full p-2 rounded border">
      </div>

      <div>
        <label class="block font-medium">Double bed:</label>
        <input type="number" name="double_bed" required
               class="w-full p-2 rounded border">
      </div>

      <div>
        <label class="block font-medium">Single bed:</label>
        <input type="number" name="single_bed" required
               class="w-full p-2 rounded border">
      </div>

      <div>
        <label class="block font-medium">Price per night:</label>
        <input type="number" name="price" required
               class="w-full p-2 rounded border">
      </div>

      <div>
        <label class="block font-medium">Category:</label>
        <input type="text" name="category" required
               class="w-full p-2 rounded border">
      </div>

      <!-- STATUS nuk e marrim nga forma -->
      <!-- vendoset automatikisht në PHP -->

      <button type="submit"  class="w-full mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 cursor-pointer">
        Create Room
      </button>

    </form>
  </div>

  <!-- Lista dhomave -->
   <div class="flex-1">
    <div class="grid grid-cols-3 gap-6">
      <?php require_once "./php/get_rooms.php" ?>
    </div>

   </div>


</main>

</body>
</html>