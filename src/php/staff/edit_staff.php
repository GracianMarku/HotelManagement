<?php
require_once "../connect.php";

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
  header("Location: ../../staff.php");
  exit();
}

$stmt = $conn->prepare("SELECT * FROM staff WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$staff = $stmt->get_result()->fetch_assoc();

if (!$staff) {
  header("Location: ../../staff.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Staff</title>
   <link href="../../output.css" rel="stylesheet"/>
</head>
<body>

<aside class="w-64 h-screen bg-[#0b0b3b] text-white flex flex-col justify-between fixed">

        <div>
            <h1 class="text-2x1 font-bold text-center py-6 border-b border-gray-600">Hotel Admin</h1>

            <nav class="mt-6 flex flex-col gap-10 px-4 ">
                <a href="../../dashboard.html" class="text-gray-200 font-bold hover:bg-indigo-900 px-4 py-2 rounded-lg shadow-md">Dashboard</a>
                <a href="../../room.php" class="text-gray-200 font-bold hover:bg-indigo-900 px-4 py-2 rounded-lg transition-colors duration-200">Rooms</a>
                <a href="../../reservation.php" class="text-gray-200 font-bold hover:bg-indigo-900 px-4 py-2 rounded-lg">Reservations</a>
                <a href="../../customer.php" class="text-gray-200 font-bold hover:bg-indigo-900 px-4 py-2 rounded-lg">Customers</a>
                <a href="../../payment.php" class="text-gray-200 font-bold hover:bg-indigo-900 px-4 py-2 rounded-lg">Payments</a>
                <a href="../../staff.php" class="text-gray-200 font-bold hover:bg-indigo-900 px-4 py-2 rounded-lg">Staff</a>

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
  <div class="bg-white shadow-xl rounded-2xl p-10 w-full max-w-lg">

    <h2 class="text-2xl font-bold mb-6">Edit Staff</h2>

    <form action="./update_staff.php" method="post" class="flex flex-col gap-4">
      <input type="hidden" name="id" value="<?= (int)$staff['id'] ?>">

      <input class="p-2 border rounded w-full"
             type="text" name="full_name"
             value="<?= htmlspecialchars($staff['full_name']) ?>" required>

      <input class="p-2 border rounded w-full"
             type="text" name="role"
             value="<?= htmlspecialchars($staff['role']) ?>" required>

      <input class="p-2 border rounded w-full"
             type="text" name="phone"
             value="<?= htmlspecialchars($staff['phone']) ?>">

      <input class="p-2 border rounded w-full"
             type="email" name="email"
             value="<?= htmlspecialchars($staff['email']) ?>">

      <input class="p-2 border rounded w-full"
             type="date" name="hire_date"
             value="<?= htmlspecialchars($staff['hire_date']) ?>">

      <input class="p-2 border rounded w-full"
             type="number" step="0.01" min="0" name="salary"
             value="<?= htmlspecialchars($staff['salary']) ?>">

      <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold p-2 rounded">
        Update Staff
      </button>
    </form>

    <a href="../../staff.php" class="inline-block mt-4 text-blue-600">← Back</a>
  </div>
</div>

</body>
</html>