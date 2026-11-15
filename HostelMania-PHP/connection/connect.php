<?php

// main connection file for both admin & front end
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "onlinefoodphp";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$db = mysqli_connect($servername, $username, $password, $dbname);

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($db, "utf8mb4");

?>