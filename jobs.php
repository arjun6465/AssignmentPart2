<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/styles.css">
    
    <meta charset="UTF-8">
    <?php include("nav.inc"); ?>
  
      
    <title>Open Positions at <bold><em>Gettin It Done</em></bold></title>
</head>
<body>
    <!--By putting the jib page in a DIV we can use the ID of the div to allow us to specifically style the jobs.html page via CSS-->
    <div class="job-page">
    <header>
        <h1>Positions we're Hiring for</h1>
    </header>

    

<!--// This part loads extra job listings from the database
// and displays them below the original hardcoded ones.
// It's done using PHP and MySQL as required in Part 2.
-->

        <?php
require_once('settings.php');
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);

if ($conn) {
    $query = "SELECT * FROM jobs";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        echo "<hr><h2>More Job Openings</h2>";

        while ($job = mysqli_fetch_assoc($result)) {
            echo "<section class='dynamic-job'>";
            echo "<h2>{$job['JobTitle']}</h2>";
            echo "<p><strong>Ref Code:</strong> {$job['JobRef']}</p>";
            echo "<p><strong>Description:</strong> {$job['JobDescription']}</p>";
            echo "<p><strong>Salary Range:</strong> {$job['SalaryRange']}</p>";
            echo "<p><strong>Reports To:</strong> {$job['ReportsTo']}</p>";
            echo "<a href='apply.php?jobRef={$job['JobRef']}' class='apply-button'>Apply Now</a>";
            echo "</section>";
        }
    }

    mysqli_close($conn);
} else {
    echo "<p class='error'>Could not load additional jobs from database: " . mysqli_connect_error() . "</p>";
}
?>


    </main>

    <!--In the footer we use the href and mailto in order to actually be able to send an email when clicking the companys email in the footer.-->
    <footer>
        <p> <a href="mailto:info@GettingItDone.com.au" id="email">info@GettingItDone.com.au</a> | © 2025 Tech Careers</p>
    </footer>
</div>
</body>
</html>