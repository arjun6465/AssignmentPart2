<?php
require_once("settings.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = trim($_POST["username"]);
  $password = $_POST["password"];

  if (!preg_match("/^[a-zA-Z0-9_]{5,20}$/", $username)) {
    die("Username must be 5–20 letters/numbers.");
  }

  if (!preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$/", $password)) {
    die("Password must be 8+ chars, with upper/lowercase and a number.");
  }

  $hashed = password_hash($password, PASSWORD_DEFAULT);

  $conn = mysqli_connect($host, $user, $pwd, $sql_db);
  if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
  }

  $check = mysqli_query($conn, "SELECT * FROM user WHERE username='$username'");
  if (mysqli_num_rows($check) > 0) {
    die("Username already exists.");
  }

  $insert = mysqli_query($conn, "INSERT INTO user (username, password) VALUES ('$username', '$hashed')");
  if ($insert) {
    echo "<p style='color: green; text-align: center;'>Manager registered successfully.</p>";
  } else {
    echo "<p style='color: red; text-align: center;'>Error: " . mysqli_error($conn) . "</p>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manager Registration</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <?php include("header.inc"); ?>
  <?php include("nav.inc"); ?>

  <main class="job-page">
    <h1>Register New Manager</h1>
    <form method="post" action="register.php" novalidate="novalidate" style="max-width: 500px; margin: auto;">
      <label>Username:<br>
        <input type="text" name="username" required style="width: 100%; padding: 8px;">
      </label><br><br>

      <label>Password:<br>
        <input type="password" name="password" required style="width: 100%; padding: 8px;">
      </label><br><br>

      <input type="submit" value="Register" class="apply-button">
    </form>
  </main>

  <?php include("footer.inc"); ?>
</body>
</html>
