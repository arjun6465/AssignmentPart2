


<?php

    header("Location: apply.php");
    exit();
}

require_once("settings.php");


function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// 🧼 Sanitize input fields
$jobRef = sanitize($_POST['jobRef'] ?? '');
$firstName = sanitize($_POST['firstName'] ?? '');
$lastName = sanitize($_POST['lastName'] ?? '');
$dob = sanitize($_POST['dob'] ?? '');
$gender = sanitize($_POST['gender'] ?? '');
$street = sanitize($_POST['street'] ?? '');
$suburb = sanitize($_POST['suburb'] ?? '');
$state = sanitize($_POST['state'] ?? '');
$postcode = sanitize($_POST['postcode'] ?? '');
$email = sanitize($_POST['email'] ?? '');
$phone = sanitize($_POST['phone'] ?? '');
$skills = $_POST['skills'] ?? [];
$otherSkills = sanitize($_POST['otherSkills'] ?? '');

// Server-side Validation
$errors = [];

if (!preg_match("/^[A-Za-z]{1,20}$/", $firstName)) $errors[] = "Invalid First Name";
if (!preg_match("/^[A-Za-z]{1,20}$/", $lastName)) $errors[] = "Invalid Last Name";
if (!preg_match("/^\d{2}\/\d{2}\/\d{4}$/", $dob)) $errors[] = "Invalid Date of Birth";
if (!in_array($gender, ['Male', 'Female', 'Other'])) $errors[] = "Invalid Gender";
if (!preg_match("/^.{1,40}$/", $street)) $errors[] = "Invalid Street";
if (!preg_match("/^.{1,40}$/", $suburb)) $errors[] = "Invalid Suburb";
if (!in_array($state, ['VIC','NSW','QLD','NT','WA','SA','TAS','ACT'])) $errors[] = "Invalid State";
if (!preg_match("/^\d{4}$/", $postcode)) $errors[] = "Postcode must be 4 digits";
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid Email";
if (!preg_match("/^[0-9 ]{8,12}$/", $phone)) $errors[] = "Invalid Phone Number";

if (!empty($errors)) {
    echo "<h2>Form Validation Failed</h2><ul>";
    foreach ($errors as $err) echo "<li>$err</li>";
    echo "</ul><a href='apply.php'>Return to form</a>";
    exit();
}

// Connect to DB
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("<p>Connection failed: " . mysqli_connect_error() . "</p>");
}

// Create Table If Not Exists
$createTableSQL = "
CREATE TABLE IF NOT EXISTS eoi (
    EOInumber INT AUTO_INCREMENT PRIMARY KEY,
    JobReferenceNumber VARCHAR(10),
    FirstName VARCHAR(20),
    LastName VARCHAR(20),
    DOB DATE,
    Gender VARCHAR(10),
    StreetAddress VARCHAR(40),
    Suburb VARCHAR(40),
    State VARCHAR(3),
    Postcode VARCHAR(4),
    Email VARCHAR(100),
    Phone VARCHAR(12),
    Skill1 VARCHAR(50),
    Skill2 VARCHAR(50),
    Skill3 VARCHAR(50),
    Skill4 VARCHAR(50),
    OtherSkills TEXT,
    Status ENUM('New','Current','Final') DEFAULT 'New'
)";
mysqli_query($conn, $createTableSQL);

// Insert sanitized data
$skill1 = $skills[0] ?? '';
$skill2 = $skills[1] ?? '';
$skill3 = $skills[2] ?? '';
$skill4 = $skills[3] ?? '';

$dobFormatted = date('Y-m-d', strtotime(str_replace('/', '-', $dob))); // convert dd/mm/yyyy to yyyy-mm-dd

$insertSQL = "
INSERT INTO eoi 
(JobReferenceNumber, FirstName, LastName, DOB, Gender, StreetAddress, Suburb, State, Postcode, Email, Phone, Skill1, Skill2, Skill3, Skill4, OtherSkills)
VALUES 
('$jobRef', '$firstName', '$lastName', '$dobFormatted', '$gender', '$street', '$suburb', '$state', '$postcode', '$email', '$phone', '$skill1', '$skill2', '$skill3', '$skill4', '$otherSkills')
";

if (mysqli_query($conn, $insertSQL)) {
    $eoiID = mysqli_insert_id($conn);
    echo "<h2>Application Submitted Successfully!</h2>";
    echo "<p>Your EOI Number is: <strong>$eoiID</strong></p>";
    echo "<a href='index.php'>Back to Home</a>";
} else {
    echo "<p>Error inserting record: " . mysqli_error($conn) . "</p>";
}

mysqli_close($conn);
?>
