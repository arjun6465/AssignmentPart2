<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- nav.inc -->
<nav>
  <ul>
    <li><a href="index.php">Home</a></li>
    <li><a href="jobs.php">Job Descriptions</a></li>
    <li><a href="apply.php">Apply</a></li>
    <li><a href="about.php">About Us</a></li>


    <?php if (isset($_SESSION['username'])): ?>
      <li><a href="manage.php">Admin</a></li>
      <li><a href="logout.php">Logout</a></li>
    <?php else: ?>
      <li><a href="login.php">Login</a></li>
    <?php endif; ?>

    <li><a href="enhancements.php">Enhancements</a></li>
  </ul>
</nav>
