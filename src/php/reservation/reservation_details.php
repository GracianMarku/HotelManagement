<?php

require_once "../connect.php";

if(!$isset($_GET['reservation_id']))
{
    die("Reservation ID mungon");
}

$reservation_id = intval($_GET['reservation_id']);

// 3. Merr rezervimin nga DB
$res_query = $conn->prepare("SELECT * FROM reservations WHERE id = ?");
$res_query->bind_param("i", $reservation_id);
$res_query->execute();
$reservation = $res_query->get_result()->fetch_assoc();
$res_query->close();

if (!$reservation) {
    die("Reservation nuk ekziston.");
}


// 4. Merr dhomen e lidhur
$room_query = $conn->prepare("SELECT * FROM rooms WHERE id = ?");
$room_query->bind_param("i", $reservation['room_id']);
$room_query->execute();
$room = $room_query->get_result()->fetch_assoc();
$room_query->close();

if (!$room) {
    die("Room nuk ekziston.");
}

// 5. Llogarit totalin
$check_in = new DateTime($reservation['check_in']);
$check_out = new DateTime($reservation['check_out']);
$interval = $check_in->diff($check_out);
$nights = $interval->days;
$total = $nights * $room['price'];

// 6. Kontrollon payment
$payment_query = $conn->prepare("SELECT * FROM payments WHERE reservation_id = ?");
$payment_query->bind_param("i", $reservation_id);
$payment_query->execute();
$payment = $payment_query->get_result()->fetch_assoc();
$payment_query->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <h2>Reservation Info</h2>
    <p>Room Number: <?php echo htmlspecialchars($room['room_number']); ?></p>
    <p>Check-in: <?php echo htmlspecialchars($reservation['check_in']); ?></p>
    <p>Check-out: <?php echo htmlspecialchars($reservation['check_out']); ?></p>
    <p>Nights: <?php echo $nights; ?></p>
    <p>Price per night: $<?php echo number_format($room['price'], 2); ?></p>
    <p>Total amount: $<?php echo number_format($total, 2); ?></p>


    <h2>Payment Section</h2>
    <?php if (!$payment): ?>
        <p>Status: <strong>UNPAID</strong></p>
        <form action="process_payment.php" method="POST">
            <input type="hidden" name="reservation_id" value="<?php echo $reservation_id; ?>">
            <label for="method">Payment Method:</label>
            <select name="method" id="method" required>
                <option value="">Select</option>
                <option value="Credit Card">Credit Card</option>
                <option value="Cash">Cash</option>
                <option value="Bank Transfer">Bank Transfer</option>
            </select>
            <button type="submit">Mark as Paid</button>
        </form>
    <?php else: ?>
        <p>Status: <strong>PAID</strong></p>
        <p>Amount: $<?php echo number_format($payment['amount'], 2); ?></p>
        <p>Date: <?php echo $payment['payment_date']; ?></p>
        <p>Method: <?php echo htmlspecialchars($payment['method']); ?></p>
    <?php endif; ?>

    
</body>
</html>