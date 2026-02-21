<?php
require_once "../connect.php";

$reservation_id = (int)($_GET['reservation_id'] ?? 0);
if ($reservation_id <= 0) {
  header("Location: ../../payment.php?error=reservation");
  exit();
}

/* ===== Reservation + totals (summary për këtë reservation) ===== */
$infoSql = "
SELECT 
  r.id AS reservation_id,
  c.full_name,
  ro.room_number,
  r.check_in,
  r.check_out,
  DATEDIFF(r.check_out, r.check_in) AS nights,
  ro.price AS price_per_night,
  (DATEDIFF(r.check_out, r.check_in) * ro.price) AS total_due,
  COALESCE(SUM(CASE WHEN p.status='paid' THEN p.amount ELSE 0 END), 0) AS total_paid,

  (
    SELECT p2.method
    FROM payments p2
    WHERE p2.reservation_id = r.id
    ORDER BY p2.id DESC
    LIMIT 1
  ) AS last_method,

  (
    SELECT p2.payment_date
    FROM payments p2
    WHERE p2.reservation_id = r.id
    ORDER BY p2.id DESC
    LIMIT 1
  ) AS last_payment_date

FROM reservations r
JOIN customers c ON c.id = r.customer_id
JOIN rooms ro ON ro.id = r.room_id
LEFT JOIN payments p ON p.reservation_id = r.id
WHERE r.id = ?
GROUP BY r.id, c.full_name, ro.room_number, r.check_in, r.check_out, ro.price
LIMIT 1
";
$infoStmt = $conn->prepare($infoSql);
$infoStmt->bind_param("i", $reservation_id);
$infoStmt->execute();
$info = $infoStmt->get_result()->fetch_assoc();

if (!$info) {
  header("Location: ../../payment.php?error=reservation");
  exit();
}

$due  = (float)$info['total_due'];
$paid = (float)$info['total_paid'];
// $remaining = max(0, $due - $paid);

if ($due > 0 && $paid >= $due) $payStatus = 'paid';
elseif ($paid > 0) $payStatus = 'partial';
else $payStatus = 'unpaid';

/* ===== Payments history për këtë reservation ===== */
$historySql = "
SELECT id, amount, payment_date, method, status
FROM payments
WHERE reservation_id = ?
ORDER BY id DESC
";
$histStmt = $conn->prepare($historySql);
$histStmt->bind_param("i", $reservation_id);
$histStmt->execute();
$history = $histStmt->get_result();

/* Alerts */
$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="../../output.css" rel="stylesheet"/>
  <title>Payment Details</title>
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

  <div class="mb-4">
    <a href="../../payment.php" class="inline-block bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded">
      ← Back to Payments
    </a>
  </div>

  <?php if ($success === '1'): ?>
    <div class="mb-4 p-3 bg-green-100 border border-green-300 text-green-800 rounded">
      Saved successfully.
    </div>
  <?php endif; ?>

  <?php if ($error === 'invalid'): ?>
    <div class="mb-4 p-3 bg-red-100 border border-red-300 text-red-800 rounded">
      Invalid input.
    </div>
  <?php endif; ?>

  <!-- Reservation summary card -->
  <div class="bg-white shadow-xl rounded-2xl p-8 w-full mb-8">
    <h2 class="text-2xl font-bold mb-4">Reservation Payment Details</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div><span class="font-semibold">Reservation ID:</span> #<?= (int)$info['reservation_id'] ?></div>
      <div><span class="font-semibold">Customer:</span> <?= htmlspecialchars($info['full_name']) ?></div>
      <div><span class="font-semibold">Room:</span> Room <?= htmlspecialchars($info['room_number']) ?></div>
      <div><span class="font-semibold">Dates:</span> <?= $info['check_in'] ?> → <?= $info['check_out'] ?></div>
      <div><span class="font-semibold">Nights:</span> <?= (int)$info['nights'] ?></div>
      <div><span class="font-semibold">Price/night:</span> <?= number_format((float)$info['price_per_night'], 2) ?>€</div>
      <div><span class="font-semibold">Total due:</span> <?= number_format($due, 2) ?>€</div>
      <div><span class="font-semibold">Total paid:</span> <?= number_format($paid, 2) ?>€</div>
      <div>
  <span class="font-semibold">Method:</span>
  <?= !empty($info['last_method']) ? ucfirst(htmlspecialchars($info['last_method'])) : '—' ?>
</div>

<div>
  <span class="font-semibold">Last payment date:</span>
  <?= !empty($info['last_payment_date']) ? htmlspecialchars($info['last_payment_date']) : '—' ?>
</div>
      <div><span class="font-semibold">Status:</span> <span class="font-bold"><?= $payStatus ?></span></div>
    </div>
  </div>

  <!-- Payments history -->
  <div class="bg-white shadow-xl rounded-2xl p-8 overflow-x-auto">
    <h3 class="text-xl font-bold mb-4">Payments History</h3>

    <table class="min-w-full table-auto border border-gray-200">
      <thead class="bg-gray-200">
        <tr>
          <th class="border p-2">Payment ID</th>
          <th class="border p-2">Amount</th>
          <th class="border p-2">Method</th>
          <th class="border p-2">Payment Date</th>
          <th class="border p-2">Status</th>
          <th class="border p-2">Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php while($p = $history->fetch_assoc()): ?>
        <tr>
          <td class="border p-2"><?= (int)$p['id'] ?></td>

          <!-- UPDATE form (inline) -->
          <td class="border p-2">
            <form action="./update_payment.php" method="post" class="flex gap-2 items-center">
              <input type="hidden" name="reservation_id" value="<?= (int)$reservation_id ?>">
              <input type="hidden" name="payment_id" value="<?= (int)$p['id'] ?>">

              <input type="number" step="0.01" min="0" name="amount"
                     value="<?= htmlspecialchars($p['amount']) ?>"
                     class="p-1 border rounded w-28" required>
          </td>

          <td class="border p-2">
              <select name="method" class="p-1 border rounded" required>
                <option value="cash" <?= $p['method']==='cash'?'selected':'' ?>>Cash</option>
                <option value="card" <?= $p['method']==='card'?'selected':'' ?>>Card</option>
              </select>
          </td>

          <td class="border p-2">
              <input type="date" name="payment_date"
                     value="<?= htmlspecialchars($p['payment_date']) ?>"
                     class="p-1 border rounded" required>
          </td>

          <td class="border p-2">
              <select name="status" class="p-1 border rounded" required>
                <option value="paid" <?= $p['status']==='paid'?'selected':'' ?>>paid</option>
                <option value="unpaid" <?= $p['status']==='unpaid'?'selected':'' ?>>unpaid</option>
              </select>
          </td>

          <td class="border p-2">
              <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">
                Update
              </button>
            </form>

            <!-- DELETE -->
            <form action="./delete_payment.php" method="post" class="inline-block ml-2"
                  onsubmit="return confirm('Delete this payment?');">
              <input type="hidden" name="reservation_id" value="<?= (int)$reservation_id ?>">
              <input type="hidden" name="payment_id" value="<?= (int)$p['id'] ?>">
              <button type="submit" class="bg-red-600 hover:bg-red-700 text-black px-3 py-1 rounded">
                Delete
              </button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>

</div>

</body>
</html>