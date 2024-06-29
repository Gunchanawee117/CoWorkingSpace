<?php
$hostname = "localhost";
$username = "root";
$password = "";
$database = "tsu_commu";

// Create a database connection
$connection = new mysqli($hostname, $username, $password, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
?>
