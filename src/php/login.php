<?php

    session_start();

    $conn = mysqli_connect("localhost", "root", "", "hoteldb");
    if(!$conn)
    {
        die("Gabim server" . mysqli_connect_error());
    }

    $user = mysqli_real_escape_string($conn, $_POST['user']);
    $pass = mysqli_real_escape_string($conn, $_POST['pass']);

    // $pass = md5($pass);

    $sql = "SELECT * FROM login WHERE user = '$user' AND password = '$pass'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0)
    {
        $_SESSION['user'] = '$user';
        header("Location: ../dashboard.html");
        exit();
    }

    else 
    {
        header("Location: login.html?error=1");
        exit();
    }

?>