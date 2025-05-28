<?php
//the code below is the settings.php file, it basically connects
// to the database and checks if the connection is successful
$host = 'localhost';
$user = 'root';
$pwd = '';
$sql_db = 'assignmentPart2';


$conn = new mysqli($host, $user, $pwd, $sql_db);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>