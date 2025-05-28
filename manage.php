<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}
require_once("settings.php");
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage EOIs</title>
    <link rel="stylesheet" href="css/styles.css"> 
</head>
<body>
    <?php include("header.inc"); ?> 
    <?php include("nav.inc"); ?>    

    <main>

        <h2>Manage Expressions of Interest</h2>

        <form method="get" action="manage.php">
            <fieldset>
                <legend>Search EOIs</legend>
                <label>Job Ref: <input type="text" name="jobRef"></label>
                <label>First Name: <input type="text" name="firstName"></label>
                <input type="submit" value="Search">
            </fieldset>
        </form>

        <hr>

        <h2>Admin: Manage Job Listings</h2>

        <form method="post" action="manage.php">
            <fieldset>
                <legend>Add or Update Job</legend>
                <label>Job Ref: <input type="text" name="job_ref" required></label><br>
                <label>Job Title: <input type="text" name="job_title" required></label><br>
                <label>Description:<br>
                    <textarea name="job_description" rows="4" cols="50" required></textarea>
                </label><br>
                <label>Salary Range: <input type="text" name="salary_range" required></label><br>
                <label>Reports To: <input type="text" name="reports_to" required></label><br>
                <input type="submit" name="save_job" value="Add / Update Job">
            </fieldset>
        </form>

        <form method="post" action="manage.php">
            <fieldset>
                <legend>Delete Job</legend>
                <label>Job Ref: <input type="text" name="delete_job_ref" required></label>
                <input type="submit" value="Delete Job">
            </fieldset>
        </form>


         <hr>
<h2>Register New Manager</h2>
<form method="post" action="manage.php" class="left-aligned-form">

  <label>Username:<br>
    <input type="text" name="new_username" required style="width: 25%; padding: 8px;">
  </label><br><br>

  <label>Password:<br>
    <input type="password" name="new_password" required style="width: 25%; padding: 8px;">
  </label><br><br>

  <input type="submit" name="register_manager" value="Register" class="apply-button">
</form>
        <hr>

        <?php
        if (!$conn) {
            echo "<p class='error'>Database connection failed: " . mysqli_connect_error() . "</p>";
        } else {
            // Show EOIs
            $searchQuery = "SELECT * FROM eoi";
            $conditions = [];

            if (!empty($_GET['jobRef'])) {
                $jobRef = mysqli_real_escape_string($conn, $_GET['jobRef']);
                $conditions[] = "job_ref = '$jobRef'";
            }

            if (!empty($_GET['firstName'])) {
                $firstName = mysqli_real_escape_string($conn, $_GET['firstName']);
                $conditions[] = "first_name LIKE '%$firstName%'";
            }

            if (!empty($conditions)) {
                $searchQuery .= " WHERE " . implode(" AND ", $conditions);
            }

            $result = mysqli_query($conn, $searchQuery);

            if ($result && mysqli_num_rows($result) > 0) {
                echo "<table>
                    <thead>
                        <tr>
                            <th>EOI Number</th><th>Job Ref</th><th>Name</th><th>Status</th><th>Actions</th>
                        </tr>
                    </thead><tbody>";

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>{$row['EOInumber']}</td>
                        <td>{$row['job_ref']}</td>
                        <td>{$row['first_name']}</td>
                        <td>{$row['status']}</td>
                        <td>
                            <form method='post' action='manage.php' style='display:inline;'>
                                <input type='hidden' name='deleteRef' value='{$row['job_ref']}'>
                                <input type='submit' value='Delete All for Job'>
                            </form>
                            <form method='post' action='manage.php' style='display:inline;'>
                                <input type='hidden' name='eoiID' value='{$row['EOInumber']}'>
                                <select name='newStatus'>
                                    <option value='New'>New</option>
                                    <option value='Current'>Current</option>
                                    <option value='Final'>Final</option>
                                </select>
                                <input type='submit' name='updateStatus' value='Update'>
                            </form>
                        </td>
                    </tr>";
                }

                echo "</tbody></table>";
            } else {
                echo "<p>No matching EOIs found.</p>";
            }

            // Delete EOIs
            if (!empty($_POST['deleteRef'])) {
                $delRef = mysqli_real_escape_string($conn, $_POST['deleteRef']);
                $delSQL = "DELETE FROM eoi WHERE job_ref = '$delRef'";
                if (mysqli_query($conn, $delSQL)) {
                    echo "<p>All EOIs for job ref <strong>$delRef</strong> deleted.</p>";
                } else {
                    echo "<p>Delete error: " . mysqli_error($conn) . "</p>";
                }
            }

            // Update EOI status
            if (!empty($_POST['updateStatus']) && !empty($_POST['eoiID']) && !empty($_POST['newStatus'])) {
                $eoiID = mysqli_real_escape_string($conn, $_POST['eoiID']);
                $newStatus = mysqli_real_escape_string($conn, $_POST['newStatus']);
                $updateSQL = "UPDATE eoi SET Status = '$newStatus' WHERE EOInumber = '$eoiID'";
                if (mysqli_query($conn, $updateSQL)) {
                    echo "<p>Status updated for EOI #$eoiID</p>";
                } else {
                    echo "<p>Status update failed: " . mysqli_error($conn) . "</p>";
                }
            }

            // Add / Update Job
            if (isset($_POST['save_job'])) {
                $ref = mysqli_real_escape_string($conn, $_POST['job_ref']);
                $title = mysqli_real_escape_string($conn, $_POST['job_title']);
                $desc = mysqli_real_escape_string($conn, $_POST['job_description']);
                $salary = mysqli_real_escape_string($conn, $_POST['salary_range']);
                $reports = mysqli_real_escape_string($conn, $_POST['reports_to']);

                $check = mysqli_query($conn, "SELECT * FROM jobs WHERE JobRef = '$ref'");
                if (mysqli_num_rows($check) > 0) {
                    $update = "UPDATE jobs SET JobTitle='$title', JobDescription='$desc', SalaryRange='$salary', ReportsTo='$reports' WHERE JobRef='$ref'";
                    if (mysqli_query($conn, $update)) {
                        echo "<p>Job updated: $ref</p>";
                    } else {
                        echo "<p>Error updating job: " . mysqli_error($conn) . "</p>";
                    }
                } else {
                    $insert = "INSERT INTO jobs (JobRef, JobTitle, JobDescription, SalaryRange, ReportsTo)
                            VALUES ('$ref', '$title', '$desc', '$salary', '$reports')";
                    if (mysqli_query($conn, $insert)) {
                        echo "<p>New job added: $ref</p>";
                    } else {
                        echo "<p>Error adding job: " . mysqli_error($conn) . "</p>";
                    }
                }
            }

            // Delete Job
            if (!empty($_POST['delete_job_ref'])) {
                $delRef = mysqli_real_escape_string($conn, $_POST['delete_job_ref']);
                $delJob = "DELETE FROM jobs WHERE JobRef = '$delRef'";
                if (mysqli_query($conn, $delJob)) {
                    echo "<p>Job $delRef deleted.</p>";
                } else {
                    echo "<p>Error deleting job: " . mysqli_error($conn) . "</p>";
                }
            }


            if (isset($_POST['register_manager'])) {
    $new_username = trim($_POST['new_username']);
    $new_password = $_POST['new_password'];

    if (!preg_match("/^[a-zA-Z0-9_]{5,20}$/", $new_username)) {
        echo "<p style='color: red; text-align: center;'>Username must be 5–20 letters/numbers.</p>";
    } elseif (!preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$/", $new_password)) {
        echo "<p style='color: red; text-align: center;'>Password must be 8+ characters with upper/lowercase and a number.</p>";
    } else {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $check = mysqli_query($conn, "SELECT * FROM user WHERE username = '$new_username'");
        
        if (mysqli_num_rows($check) > 0) {
            echo "<p style='color: red; text-align: center;'>Username already exists.</p>";
        } else {
            $insert = mysqli_query($conn, "INSERT INTO user (username, password) VALUES ('$new_username', '$hashed')");
            if ($insert) {
                echo "<p style='color: green; text-align: center;'>New manager registered successfully.</p>";
            } else {
                echo "<p style='color: red; text-align: center;'>Error: " . mysqli_error($conn) . "</p>";
            }
        }
    }
}

            mysqli_close($conn);
        }
        ?>
    </main>

    <?php include("footer.inc"); ?> 
</body>
</html>
