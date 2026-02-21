<?php

require_once "./php/connect.php";

$customerResult = null;
$customerSearch = '';

if (isset($_POST['customer_search']) && $_POST['customer_search'] !== '') {
    $customerSearch = $conn->real_escape_string($_POST['customer_search']);

    $customerResult = $conn->query("
        SELECT id, full_name, email
        FROM customers
        WHERE full_name LIKE '%$customerSearch%'
        ORDER BY full_name ASC
        LIMIT 10
    ");
}

// -----------------------------------
// 2. GET ROOMS
// -----------------------------------
$rooms = $conn->query("SELECT * FROM rooms ORDER BY room_number ASC");

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
     <link href="./output.css" rel="stylesheet"/>
</head>
<body>
    
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
        <form action="./php/exit.php" method="post">
        <div class="px-4 mb-4">
            <button class="w-full bg-red-700 hover:bg-red-800 py-2 rounded-lg font-semibold">Logout</button>
        </div>
        </form>

    </aside>

   
    <div class="ml-64 p-10 px-20 min-h-screen bg-gray-100">
    <div class="bg-white shadow-xl rounded-2xl p-10 w-full">
        <h2 class="text-xl font-bold mb-4">Create Reservation</h2>

        <?php if (isset($_GET['error']) && $_GET['error'] === 'occupied'): ?>
  <div class="mb-4 p-3 rounded bg-red-100 text-red-700 border border-red-300">
    This room is already reserved for the selected dates.
  </div>
<?php endif; ?>

<?php if (isset($_GET['success'])): ?>
  <div class="mb-4 p-3 rounded bg-green-100 text-green-700 border border-green-300">
    Reservation created successfully.
  </div>
<?php endif; ?> 


        <form method="post" action="reservation.php" class="mb-6">
        <label class="font-semibold">Customer Name:</label>
        <input type="text"
           name="customer_search"
           value="<?= htmlspecialchars($customerSearch ?? '') ?>"
           placeholder="Type customer name..."
           class="p-2 border rounded w-full">

    <button type="submit" class="mt-2 bg-gray-300 hover:bg-gray-400 px-3 py-1 rounded"> Search </button>
</form>


        <form action="./php/reservation/create_reservation.php" method="post" class="flex flex-col gap-4">

    <!-- ROOM -->
    <div>
        <label>Room:</label>
        <select name="room_id" required class="p-2 border rounded w-full">
            <?php while ($r = $rooms->fetch_assoc()): ?>
                <option value="<?= $r['id'] ?>">
                    Room <?= $r['room_number'] ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <!-- CUSTOMER RESULTS -->
    <?php if (!empty($customerResult) && $customerResult->num_rows > 0): ?>
        <div class="border rounded max-h-40 overflow-y-auto">
            <?php while ($c = $customerResult->fetch_assoc()): ?>
                <div class="p-2 border-b flex justify-between items-center customer-item">
                    <?= htmlspecialchars($c['full_name']) ?>
                    <button type="button" onclick="selectCustomer(<?= $c['id'] ?>, '<?= htmlspecialchars($c['full_name'], ENT_QUOTES) ?>')"
                    class="bg-blue-500 text-white px-2 py-1 rounded"> Select </button>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>

    <!-- SELECTED CUSTOMER -->
    <input type="hidden" name="customer_id" id="customer_id" required>
    <div id="selectedCustomer"
     class="hidden mt-2 p-2 bg-green-100 border border-green-400 rounded font-semibold">
</div>

    <!-- DATES -->
    <div>
        <label>Check-in:</label>
        <input type="date" name="check_in" required class="p-2 border rounded w-full">
    </div>

    <div>
        <label>Check-out:</label>
        <input type="date" name="check_out" required class="p-2 border rounded w-full">
    </div>

    <button type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold p-2 rounded">
        Create Reservation
    </button>
</form>
    </div>
</div>

  <!-- ================= RESERVATIONS TABLE ================= -->
  <div class="ml-64  bg-white shadow-xl rounded-2xl p-10 overflow-x-auto ">

    <h2 class="text-xl font-bold mb-4">Reservations</h2>

    <table class="min-w-full table-auto  border border-gray-200">
      <thead class="bg-gray-200">
        <tr>
          <th class="border p-2">Room</th>
          <th class="border p-2">Customer</th>
          <th class="border p-2">Check-in</th>
          <th class="border p-2">Check-out</th>
          <th class="border p-2">Status</th>

          <th class="border p-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $reservations = $conn->query("
          SELECT r.*, 
                 ro.room_number, 
                 c.full_name
          FROM reservations r
          JOIN rooms ro ON r.room_id = ro.id
          JOIN customers c ON r.customer_id = c.id
          ORDER BY r.id DESC
        ");

        while ($row = $reservations->fetch_assoc()):
        ?>
          <tr>
            <td class="border p-2">Room <?= $row['room_number'] ?></td>
            <td class="border p-2"><?= $row['full_name'] ?></td>
            <td class="border p-2"><?= $row['check_in'] ?></td>
            <td class="border p-2"><?= $row['check_out'] ?></td>
            <td class="border p-2 font-semibold"><?= $row['status'] ?></td>

            <td class="border p-2">
    <?php if ($row['status'] === 'reserved'): ?>
        <form method="post" action="./php/reservation/update_status.php">
            <input type="hidden" name="reservation_id" value="<?= $row['id'] ?>">
            <input type="hidden" name="new_status" value="occupied">
            <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded">Check In</button>
        </form>
    <?php elseif ($row['status'] === 'occupied'): ?>
        <form method="post" action="./php/reservation/update_status.php">
            <input type="hidden" name="reservation_id" value="<?= $row['id'] ?>">
            <input type="hidden" name="new_status" value="completed">
            <button type="submit" class="bg-gray-500 text-white px-2 py-1 rounded">Check Out</button>
        </form>
    <?php endif; ?>
</td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

  </div>

</div>

        </div>



    </div>

<script>
function selectCustomer(id, name) {
    // vendos id
    document.getElementById('customer_id').value = id;

    // shfaq customer-in e zgjedhur
    const box = document.getElementById('selectedCustomer');
    box.innerText = "Selected customer: " + name;
    box.classList.remove('hidden');

    // fshij listën e rezultateve
    document.querySelectorAll('.customer-item').forEach(el => el.remove());
}
</script>
</body>
</html>