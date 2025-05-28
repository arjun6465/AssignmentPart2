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

        <?php
        // Show error if connection failed
        if (!$conn) {
            echo "<p class='error'>Database connection failed: " . mysqli_connect_error() . "</p>";
        } else {
            // Start building the SQL query
            $searchQuery = "SELECT * FROM eoi";
            $conditions = [];

            // If filters are provided, add conditions to the query
            if (!empty($_GET['jobRef'])) {
                $jobRef = mysqli_real_escape_string($conn, $_GET['jobRef']);
                $conditions[] = "job_ref = '$jobRef'";
            }

            if (!empty($_GET['firstName'])) {
                $firstName = mysqli_real_escape_string($conn, $_GET['firstName']);
                $conditions[] = "first_name LIKE '%$firstName%'";
            }


            // Add WHERE clause if any conditions are set
            if (!empty($conditions)) {
                $searchQuery .= " WHERE " . implode(" AND ", $conditions);
            }

            // Run the search query
            $result = mysqli_query($conn, $searchQuery);

            // Display results in a table if any rows are found
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
                            <!-- Form to delete EOIs by job reference -->
                            <form method='post' action='manage.php' style='display:inline;'>
                            <input type='hidden' name='deleteRef' value='{$row['job_ref']}'>
                                <input type='submit' value='Delete All for Job'>
                            </form>
                            <!-- Form to update the status of a specific EOI -->
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
                echo "<p>No matching EOIs found.</p>"; // Message if no EOIs match the search
            }

            // Handle POST request to delete EOIs by job reference
            if (!empty($_POST['deleteRef'])) {
                $delRef = mysqli_real_escape_string($conn, $_POST['deleteRef']);
                $delSQL = "DELETE FROM eoi WHERE job_ref = '$delRef'";
                if (mysqli_query($conn, $delSQL)) {
                    echo "<p>All EOIs for job ref <strong>$delRef</strong> deleted.</p>";
                } else {
                    echo "<p>Delete error: " . mysqli_error($conn) . "</p>";
                }
            }

            // Handle POST request to update EOI status
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

            // Close DB connection
            mysqli_close($conn);
        }
        ?>
    </main>

    <?php include("footer.inc"); ?> 
</body>
</html>
