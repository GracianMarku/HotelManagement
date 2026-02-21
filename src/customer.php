<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./output.css">
</head>
<body class="bg-gray-100">

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
        <form action="./exit.php" method="post">
        <div class="px-4 mb-4">
            <button class="w-full bg-red-700 hover:bg-red-800 py-2 rounded-lg font-semibold">Logoutiiii</button>
        </div>
        </form>

    </aside>
    
    
<div class="ml-64 p-10 px-20 min-h-screen bg-gray-100 ">

    <div class="bg-white shadow-xl rounded-2xl p-10 w-full ">

    <form action="./php/create_customer.php" method="post" class="  flex flex-col justify-between items-center ">

        <div>
            <label class="block  text-gray-700 font-medium p-1">Name:</label>
            <input type="text" name="full_name" placeholder="Full Name" class="w-100 p-3 rounded-xl border border-gray focus:ring-1" required>
        </div>

        <div>
            <label class="block text-gray-700 font-medium pt-4">Phone Number</label>
            <input type="number" name="phone" placeholder="### ### ###"
             class="w-100 p-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none" required>
        </div>      
        
        <div>
            <label class="block text-gray-700 font-medium pt-4"> Email</label>
            <input type="text" name="email" placeholder="name@exp.com" required
             class="w-100 p-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none">
        </div>

        <div>
            <label class="block text-gray-700 font-medium pt-4"> City</label>
            <input type="text" name="city" placeholder="city" required
            class="w-100 p-3  rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none">
        </div>

         <div>
            <label class="block text-gray-700 font-medium pt-4"> Country</label>
            <input type="text" name="country" placeholder="Country" required
            class="w-100 p-3  rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none">
        </div>

        <div>
            <label class="block text-gray-700 font-medium pt-4">Document ID</label>
            <input type="text" name="document_type" placeholder="document" required
            class="w-100 p-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none">
        </div>

        <div>
            <button type="submit" 
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold p-3 rounded-xl shadow-md mt-5 ">Register Customer</button>
        </div>

    </form>

    </div>
</div>

<div class="ml-64  p-5 px-10 rounded shadow mt-10 overflow-x-auto ">

    <h2 class="text-xl font-bold mb-4">Customers</h2>

    <table class=" min-w-full table-auto rounded-xl border border-gray-200">
        <thead class="bg-gray-200" >
            <tr>
                <th class="border p-2">Name</th>
                <th class="border p-2">Phone</th>
                <th class="border p-2">Email</th>
                <th class="border p-2">City</th>
                <th class="border p-2">Country</th>
                <th class="border p-2">Document</th>
                <th class="border p-2">Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php include "php/get_customer.php"; ?>
        </tbody>
    </table>

</div>



</body>
</html>