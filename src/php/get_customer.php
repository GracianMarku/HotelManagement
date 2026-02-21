<?php

    require_once "connect.php";

    $result = $conn->query("SELECT * FROM customers ORDER BY id DESC");

    while($row = $result->fetch_assoc())
    {
        echo "<tr>";
        echo "<td class='border p-2'> {$row['full_name']}</td>";
        echo "<td class='border p-2'> {$row['phone']}</td>";
        echo "<td class='border p-2'> {$row['email']}</td>";
        echo "<td class='border p-2'> {$row['city']}</td>";
        echo "<td class='border p-2'> {$row['country']}</td>";
        echo "<td class='border p-2'> {$row['document_type']}</td>";
        

       echo "
    <td class='border p-2 flex justify-around'>
        <a href='php/edit_customer.php?id={$row['id']}'
           class='px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600'>
           Edit
        </a>

        <a href='php/delete_customer.php?id={$row['id']}'
           class='px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600'
           onclick='return confirm(\"Are you sure you want to delete this customer?\")'>
           Delete
        </a>
    </td>";

        echo "</tr>";
    }


?>