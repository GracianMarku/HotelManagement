<?php
require_once "./php/connect.php";

/* Lexo stafin */
$result = $conn->query("SELECT * FROM staff ORDER BY id DESC");

$success = $_GET['success'] ?? '';
$error   = $_GET['error'] ?? '';
?>






<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Staff</title>
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

  <?php if ($success === '1'): ?>
    <div class="mb-4 p-3 bg-green-100 border border-green-300 text-green-800 rounded">
      Saved successfully.
    </div>
  <?php endif; ?>

  <?php if ($error === 'missing'): ?>
    <div class="mb-4 p-3 bg-red-100 border border-red-300 text-red-800 rounded">
      Please fill required fields.
    </div>
  <?php endif; ?>

  <!-- CREATE STAFF -->
  <div class="bg-white shadow-xl rounded-2xl p-10 w-full mb-10">
    <h2 class="text-xl font-bold mb-4">Register Staff</h2>

    <form action="./php/staff/create_staff.php" method="post" class="flex flex-col gap-4">
      <div>
        <label class="font-semibold">Full name:</label>
        <input type="text" name="full_name" class="p-2 border rounded w-full" required>
      </div>

      <div>
        <label class="font-semibold">Role:</label>
        <input type="text" name="role" class="p-2 border rounded w-full" placeholder="Receptionist, Manager..." required>
      </div>

      <div>
        <label class="font-semibold">Phone:</label>
        <input type="text" name="phone" class="p-2 border rounded w-full">
      </div>

      <div>
        <label class="font-semibold">Email:</label>
        <input type="email" name="email" class="p-2 border rounded w-full">
      </div>

      <div>
        <label class="font-semibold">Hire date:</label>
        <input type="date" name="hire_date" class="p-2 border rounded w-full" value="<?= date('Y-m-d') ?>">
      </div>

      <div>
        <label class="font-semibold">Salary (€):</label>
        <input type="number" step="0.01" min="0" name="salary" class="p-2 border rounded w-full">
      </div>

      <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold p-2 rounded">
        Save Staff
      </button>
    </form>
  </div>

  <!-- STAFF TABLE -->
  <div class="bg-white shadow-xl rounded-2xl p-10 overflow-x-auto">
    <h2 class="text-xl font-bold mb-4">Staff</h2>

    <table class="min-w-full table-auto border border-gray-200">
      <thead class="bg-gray-200">
        <tr>
          <th class="border p-2">Name</th>
          <th class="border p-2">Role</th>
          <th class="border p-2">Phone</th>
          <th class="border p-2">Email</th>
          <th class="border p-2">Hire date</th>
          <th class="border p-2">Salary</th>
          <th class="border p-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while($s = $result->fetch_assoc()): ?>
          <tr>
            <td class="border p-2"><?= htmlspecialchars($s['full_name']) ?></td>
            <td class="border p-2"><?= htmlspecialchars($s['role']) ?></td>
            <td class="border p-2"><?= htmlspecialchars($s['phone']) ?></td>
            <td class="border p-2"><?= htmlspecialchars($s['email']) ?></td>
            <td class="border p-2"><?= htmlspecialchars($s['hire_date']) ?></td>
            <td class="border p-2"><?= number_format((float)$s['salary'], 2) ?>€</td>
            <td class="border p-2">
              <a href="./php/staff/edit_staff.php?id=<?= (int)$s['id'] ?>"
                 class="bg-yellow-500 hover:bg-yellow-600 text-black px-3 py-1 rounded">
                Edit
              </a>

              <a href="./php/staff/delete_staff.php?id=<?= (int)$s['id'] ?>"
                 onclick="return confirm('Delete this staff member?');"
                 class="bg-red-600 hover:bg-red-700 text-black px-3 py-1 rounded ml-2">
                Delete
              </a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

  </div>
</div>

</body>
</html>