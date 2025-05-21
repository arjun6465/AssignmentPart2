<?php
// process_eoi.php

// Block direct access if no POST data
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: apply.php');
    exit();
}

require_once('settings.php'); // DB connection

// Function to sanitize user input
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Collect and sanitize input
$jobRef = sanitize_input($_POST['jobRef'] ?? '');
$firstName = sanitize_input($_POST['firstName'] ?? '');
$lastName = sanitize_input($_POST['lastName'] ?? '');
$dob = sanitize_input($_POST['dob'] ?? '');
$gender = sanitize_input($_POST['gender'] ?? '');
$street = sanitize_input($_POST['street'] ?? '');
$suburb = sanitize_input($_POST['suburb'] ?? '');
$state = sanitize_input($_POST['state'] ?? '');
$postcode = sanitize_input($_POST['postcode'] ?? '');
$email = sanitize_input($_POST['email'] ?? '');
$phone = sanitize_input($_POST['phone'] ?? '');
$skills = $_POST['skills'] ?? [];
$otherSkills = sanitize_input($_POST['otherSkills'] ?? '');

?>
