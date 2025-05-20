<?php
// settings.php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'assignmentPart2';


$conn = new mysqli($host, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully to the main database.";
?>