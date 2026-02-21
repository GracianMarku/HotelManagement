<?php

    require_once "connect.php";

    $id = $_GET['id'];

    if(!$id) 
    {
        header("Location: ../customer.php");
        exit();
    }

    $result = $conn->query("SELECT * FROM customers WHERE id = $id");
    $customer = $result->fetch_assoc();

    if(!$customer)
    {
        die("Customer not found");
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


    <div class="ml-64 p-10">
    <div class="bg-white p-8 rounded-xl shadow w-96">
        <h2 class="text-xl font-bold mb-4">Edit Customer</h2>

        <form action="update_customer.php" method="POST" class="space-y-3">

            <input type="hidden" name="id" value="<?= $customer['id'] ?>">

            <input type="text" name="full_name" value="<?= $customer['full_name'] ?>" class="w-full p-2 border rounded">
            <input type="text" name="phone" value="<?= $customer['phone'] ?>" class="w-full p-2 border rounded">
            <input type="email" name="email" value="<?= $customer['email'] ?>" class="w-full p-2 border rounded">
            <input type="text" name="city" value="<?= $customer['city'] ?>" class="w-full p-2 border rounded">
            <input type="text" name="country" value="<?= $customer['country'] ?>" class="w-full p-2 border rounded">
            <input type="text" name="document_type" value="<?= $customer['document_type'] ?>" class="w-full p-2 border rounded">

            <button class="w-full bg-blue-600 text-white p-2 rounded">
                Update Customer
            </button>
        </form>
    </div>
</div>

</body>
</html>