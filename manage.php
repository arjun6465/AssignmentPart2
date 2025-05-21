<?php
require_once('settings.php');
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
                <label>Last Name: <input type="text" name="lastName"></label>
                <input type="submit" value="Search">
            </fieldset>
        </form>

        <hr>

        <?php
        if (!$conn) {
            echo "<p class='error'>Database connection failed: " . mysqli_connect_error() . "</p>";
        } else {
            // Build query
            $searchQuery = "SELECT * FROM eoi";
            $conditions = [];

            if (!empty($_GET['jobRef'])) {
                $jobRef = mysqli_real_escape_string($conn, $_GET['jobRef']);
                $conditions[] = "JobReferenceNumber = '$jobRef'";
            }

            if (!empty($_GET['firstName'])) {
                $firstName = mysqli_real_escape_string($conn, $_GET['firstName']);
                $conditions[] = "FirstName LIKE '%$firstName%'";
            }

            if (!empty($_GET['lastName'])) {
                $lastName = mysqli_real_escape_string($conn, $_GET['lastName']);
                $conditions[] = "LastName LIKE '%$lastName%'";
            }

            if (!empty($conditions)) {
                $searchQuery .= " WHERE " . implode(" AND ", $conditions);
            }

            $result = mysqli_query($conn, $searchQuery);

            // Show results
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
                        <td>{$row['JobReferenceNumber']}</td>
                        <td>{$row['FirstName']} {$row['LastName']}</td>
                        <td>{$row['Status']}</td>
                        <td>
                            <form method='post' action='manage.php' style='display:inline;'>
                                <input type='hidden' name='deleteRef' value='{$row['JobReferenceNumber']}'>
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

            // Handle Deletion
            if (!empty($_POST['deleteRef'])) {
                $delRef = mysqli_real_escape_string($conn, $_POST['deleteRef']);
                $delSQL = "DELETE FROM eoi WHERE JobReferenceNumber = '$delRef'";
                if (mysqli_query($conn, $delSQL)) {
                    echo "<p>All EOIs for job ref <strong>$delRef</strong> deleted.</p>";
                } else {
                    echo "<p>Delete error: " . mysqli_error($conn) . "</p>";
                }
            }

            // Handle Status Update
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

            mysqli_close($conn);
        }
        ?>
    </main>

    <?php include("footer.inc"); ?>
</body>
</html>
