<?php
$host = "localhost";
$username = "root";
$password = "harsimran";
$database = "discuss";
// $port = 3307;

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("not connected with DB " . $conn->connect_error);
}

?>