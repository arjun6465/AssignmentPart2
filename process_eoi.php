


<?php

// Only run if form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: apply.php");
    exit();
}

require_once("settings.php");  // DB credentials



if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Sanitize function
function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Retrieve and sanitize
$job_ref = sanitize($_POST["job-ref"] ?? "");
$first = sanitize($_POST["name"] ?? "");
$dob = sanitize($_POST["DoB"] ?? "");
$gender = sanitize($_POST["gender"] ?? "");
$street = sanitize($_POST["street"] ?? "");
$suburb = sanitize($_POST["suburb"] ?? "");
$state = sanitize($_POST["state"] ?? "");
$postcode = sanitize($_POST["postcode"] ?? "");
$email = sanitize($_POST["email"] ?? "");
$phone = sanitize($_POST["phone"] ?? "");
$skills = $_POST["skills"] ?? [];
$otherskills = sanitize($_POST["other-skills"] ?? "");

// Server-side validation
$errors = [];
if (!preg_match("/^[a-zA-Z ]{1,20}$/", $first)) $errors[] = "Invalid name.";
if (!preg_match("/^\d{2}\/\d{2}\/\d{4}$/", $dob)) $errors[] = "Invalid DOB.";
if (!in_array($gender, ["Male", "Female", "Other"])) $errors[] = "Invalid gender.";
if (!preg_match("/^[a-zA-Z0-9\s]{1,40}$/", $street)) $errors[] = "Invalid street.";
if (!preg_match("/^[a-zA-Z ]{1,40}$/", $suburb)) $errors[] = "Invalid suburb.";
if (!in_array($state, ['VIC','NSW','QLD','NT','WA','SA','TAS','ACT'])) $errors[] = "Invalid state.";
if (!preg_match("/^\d{4}$/", $postcode)) $errors[] = "Invalid postcode.";
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email.";
if (!preg_match("/^\d{8,12}$/", str_replace(' ', '', $phone))) $errors[] = "Invalid phone.";

if (count($errors) > 0) {
    echo "<h2>Error:</h2><ul>";
    foreach ($errors as $e) echo "<li>$e</li>";
    echo "</ul><a href='apply.php'>Go back</a>";
    exit();
}

// Create table if not exists
$create_table_sql = <<<SQL
CREATE TABLE IF NOT EXISTS eoi (
    EOInumber INT AUTO_INCREMENT PRIMARY KEY,
    job_ref VARCHAR(10) NOT NULL,
    first_name VARCHAR(20) NOT NULL,
    last_name VARCHAR(20) NOT NULL,
    street VARCHAR(40) NOT NULL,
    suburb VARCHAR(40) NOT NULL,
    state ENUM('VIC','NSW','QLD','NT','WA','SA','TAS','ACT') NOT NULL,
    postcode CHAR(4) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(12) NOT NULL,
    skill1 VARCHAR(50),
    skill2 VARCHAR(50),
    skill3 VARCHAR(50),
    skill4 VARCHAR(50),
    otherskills TEXT,
    status ENUM('New','Current','Final') DEFAULT 'New'
);
SQL;

mysqli_query($conn, $create_table_sql);

// Assign up to 4 skills
$skill1 = $skills[0] ?? null;
$skill2 = $skills[1] ?? null;
$skill3 = $skills[2] ?? null;
$skill4 = $skills[3] ?? null;

// Insert application
$stmt = $conn->prepare("INSERT INTO eoi (job_ref, first_name, last_name, street, suburb, state, postcode, email, phone, skill1, skill2, skill3, skill4, otherskills, status) VALUES (?, ?, '', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'New')");
$stmt->bind_param("sssssssssssss", $job_ref, $first, $street, $suburb, $state, $postcode, $email, $phone, $skill1, $skill2, $skill3, $skill4, $otherskills);

if ($stmt->execute()) {
    $eoi_id = $stmt->insert_id;
    echo "<h2>Application Successful!</h2><p>Your EOI number is <strong>$eoi_id</strong>.</p><a href='index.php'>Return Home</a>";
} else {
    echo "<h2>Error submitting application.</h2><p>" . $stmt->error . "</p>";
}

$stmt->close();
$conn->close();
?>
