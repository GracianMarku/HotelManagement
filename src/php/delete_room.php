<?php

    require_once "connect.php";

    $id = $_GET['id'];

    if(!$id)
    {
        header("Location: ../room.php");
        exit();
    }

    $result = $conn->query("DELETE  FROM rooms WHERE id = $id");
    // $room = $result->fetch_assoc();

       header("Location: ../room.php");
        exit();


    ?>