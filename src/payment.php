<?php

require_once "./php/connect.php";

$success = $_GET['success'] ?? '';
$error   = $_GET['error'] ?? '';

/* ===== SUMMARY QUERY (KJO MUNGON TE TI) ===== */
 $summarySql = "
 SELECT 
   r.id AS reservation_id,
   c.full_name,
   ro.room_number,
   r.check_in,
   r.check_out,
   (DATEDIFF(r.check_out, r.check_in) * ro.price) AS total_due,
   COALESCE(SUM(CASE WHEN p.status='paid' THEN p.amount ELSE 0 END), 0) AS total_paid
 FROM reservations r
 JOIN customers c ON c.id = r.customer_id
 JOIN rooms ro ON ro.id = r.room_id
 LEFT JOIN payments p ON p.reservation_id = r.id
 GROUP BY r.id, c.full_name, ro.room_number, r.check_in, r.check_out, ro.price
 ORDER BY r.id DESC
 ";

 $summary = $conn->query($summarySql);



$resSelectSql = "
SELECT 
  r.id AS reservation_id,
  c.full_name,
  ro.room_number,
  r.check_in,
  r.check_out,
  (DATEDIFF(r.check_out, r.check_in) * ro.price) AS total_due,
  COALESCE(SUM(CASE WHEN p.status='paid' THEN p.amount ELSE 0 END), 0) AS total_paid
FROM reservations r
JOIN customers c ON c.id = r.customer_id
JOIN rooms ro ON ro.id = r.room_id
LEFT JOIN payments p ON p.reservation_id = r.id
GROUP BY r.id, c.full_name, ro.room_number, r.check_in, r.check_out, ro.price
ORDER BY r.id DESC
";

$reservationsForSelect = $conn->query($resSelectSql);

// Payments table (lista e pagesave)
// -----------------------
$paymentsSql = "
SELECT 
  p.id AS payment_id,
  p.reservation_id,
  p.amount,
  p.payment_date,
  p.method,
  p.status AS payment_status,

  c.full_name,
  ro.room_number,
  r.check_in,
  r.check_out,
  DATEDIFF(r.check_out, r.check_in) AS nights,
  ro.price AS price_per_night,
  (DATEDIFF(r.check_out, r.check_in) * ro.price) AS total_due,

  COALESCE((
      SELECT SUM(p2.amount)
      FROM payments p2
      WHERE p2.reservation_id = r.id AND p2.status = 'paid'
  ), 0) AS total_paid
FROM payments p
JOIN reservations r ON r.id = p.reservation_id
JOIN customers c ON c.id = r.customer_id
JOIN rooms ro ON ro.id = r.room_id
ORDER BY p.id DESC
";
$payments = $conn->query($paymentsSql);

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
        <form action="./php/exit.php" method="post">
        <div class="px-4 mb-4">
            <button class="w-full bg-red-700 hover:bg-red-800 py-2 rounded-lg font-semibold">Logout</button>
        </div>
        </form>

    </aside>


<div class="ml-64 p-10 px-20 min-h-screen bg-gray-100">

  <!-- ALERTS -->
  <?php if ($success === '1'): ?>
    <div class="mb-4 p-3 bg-green-100 border border-green-300 text-green-800 rounded">
      Payment created successfully.
    </div>
  <?php endif; ?>

  <?php if ($error === 'missing'): ?>
    <div class="mb-4 p-3 bg-red-100 border border-red-300 text-red-800 rounded">
      Please select reservation and enter valid amount.
    </div>
  <?php elseif ($error === 'reservation'): ?>
    <div class="mb-4 p-3 bg-red-100 border border-red-300 text-red-800 rounded">
      Reservation not found.
    </div>
  <?php endif; ?>

  <!-- CREATE PAYMENT -->
  <div class="bg-white shadow-xl rounded-2xl p-10 w-full mb-10">
    <h2 class="text-xl font-bold mb-4">Create Payment</h2>

    <form action="./php/payment/create_payment.php" method="post" class="flex flex-col gap-4">

      <div>
        <label class="font-semibold">Reservation:</label>
        <select name="reservation_id" class="p-2 border rounded w-full" required>
          <option value="">-- Select reservation --</option>
          <?php while($r = $reservationsForSelect->fetch_assoc()): 
              $remaining = max(0, (float)$r['total_due'] - (float)$r['total_paid']);
          ?>
            <option value="<?= (int)$r['reservation_id'] ?>">
              #<?= (int)$r['reservation_id'] ?> | Room <?= htmlspecialchars($r['room_number']) ?> |
              <?= htmlspecialchars($r['full_name']) ?> |
              <?= $r['check_in'] ?> → <?= $r['check_out'] ?> |
              Due: <?= number_format((float)$r['total_due'], 2) ?>€ |
              Paid: <?= number_format((float)$r['total_paid'], 2) ?>€ |
             
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <div>
        <label class="font-semibold">Amount (€):</label>
        <input type="number" step="0.01" min="0" name="amount" class="p-2 border rounded w-full" required>
      </div>

      <div>
        <label class="font-semibold">Payment date:</label>
        <input type="date" name="payment_date" class="p-2 border rounded w-full" value="<?= date('Y-m-d') ?>">
      </div>

      <div>
        <label class="font-semibold">Method:</label>
        <select name="method" class="p-2 border rounded w-full" required>
          <option value="cash">Cash</option>
          <option value="card">Credit card</option>
        </select>
      </div>

      <!-- Për thjeshtësi: pagesat që i regjistron admini janë 'paid' -->
      <input type="hidden" name="status" value="paid">

      <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold p-2 rounded">
        Save Payment
      </button>

    </form>
  </div>

  <!-- PAYMENTS TABLE -->
  <div class="bg-white shadow-xl rounded-2xl p-10 overflow-x-auto">
    <h2 class="text-xl font-bold mb-4">Payments</h2>

 <table class="min-w-full table-auto border border-gray-200">
  <thead class="bg-gray-200">
    <tr>
      <th class="border p-2">Customer</th>
      <th class="border p-2">Room</th>
      <th class="border p-2">Dates</th>
      <th class="border p-2">Total Due</th>
      <th class="border p-2">Paid</th>
      <th class="border p-2">Status</th>
      <th class="border p-2">Action</th>
    </tr>
  </thead>

  <tbody>
    <?php while($row = $summary->fetch_assoc()): 
      $due  = (float)$row['total_due'];
      $paid = (float)$row['total_paid'];

      if ($due > 0 && $paid >= $due) $payStatus = 'paid';
      elseif ($paid > 0) $payStatus = 'partial';
      else $payStatus = 'unpaid';
    ?>
      <tr>
        <td class="border p-2"><?= htmlspecialchars($row['full_name']) ?></td>
        <td class="border p-2">Room <?= htmlspecialchars($row['room_number']) ?></td>
        <td class="border p-2"><?= $row['check_in'] ?> → <?= $row['check_out'] ?></td>
        <td class="border p-2"><?= number_format($due, 2) ?>€</td>
        <td class="border p-2"><?= number_format($paid, 2) ?>€</td>
        <td class="border p-2 font-semibold"><?= $payStatus ?></td>
        <td class="border p-2">
          <!-- opsionale: shko në history të pagesave për këtë rezervim -->
          <a class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded"
             href="./php/payment/payment_details.php?reservation_id=<?= (int)$row['reservation_id'] ?>"> Details</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

  </div>

</div>

</body>
</html>
