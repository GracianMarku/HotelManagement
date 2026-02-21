<?php

    $mysqli = mysqli_connect("localhost", "root", "", "hoteldb");
    
    if(!$mysqli)
    {
        die("Gabim server:" . mysqli_connect_error());
    }

    $user = "admin";
    $pass = md5("123");

    $sql = "INSERT INTO login (user, pass) VALUES ('$user', '$pass')";

    if($mysqli->query($sql) === true)
    {
        echo "Admin u regjistrua";
    }
    else 
    {
        echo "Gabim: " . $mysqli->error;
    }

    $mysqli->close();

?>