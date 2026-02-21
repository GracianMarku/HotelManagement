<?php

$host = "localhost";
$user = "root";
$pass = "";
$db = "hoteldb";

$conn = mysqli_connect($host, $user, $pass, $db);

if(!$conn){
    die("Lidhja me databazen deshtoi: " . mysqli_connect_error());
}

?>