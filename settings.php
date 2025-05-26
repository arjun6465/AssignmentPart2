<?php
// settings.php
$host = 'localhost';
$user= 'root';
$pwd = '';
$sql_db = 'assignmentPart2';


$conn = new mysqli($host, $user, $pwd, $sql_db);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>