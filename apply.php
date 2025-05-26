<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Basic metadata and responsive scaling -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Application - Getting It Done</title>

    <!-- Link to external CSS stylesheet -->
    <link rel="stylesheet" href="css/styles.css">

    <!-- Logo image (usually better placed in body/header) -->
    <img src="images/logo.png" alt="Description of the image" width="500" height="299" class="Imagelogo">

    <!-- Navigation Menu -->
    <?php include("nav.inc"); ?>
</head>

<body>
    <!-- Main container for the page content -->
    <div class="container">
        
        <!-- Company introduction section -->
        <div class="info-section">
            <h2>Join Our Team at GettingIT Done</h2>
            <p>We are looking for passionate individuals who want to make a difference in the IT industry. 
            Apply now and be part of a company that values innovation and excellence.</p>
            <div class="contact-info">
                <p><strong>Email:</strong> careers@getitdone.com</p>
                <p><strong>Phone:</strong> +04-668-084-443</p>
            </div>
        </div>

        <!-- Job Application Form Section -->
        <div class="form-section">
            <!-- Form submission setup -->
            <form method="post" action="process_eoi.php" novalidate="novalidate">

                <!-- Personal Details Section -->
                <fieldset>
                    <legend>Personal Details</legend>

                    <!-- Dropdown to select job reference -->
                    <label for="job-ref">Job Reference Number:</label>
                    <select name="job-ref" id="job-ref" required>
                        <option value="" disabled selected>Select Job Reference</option>
                        <option value="DEV101">AC130 - Senior Software Developer</option>
                        <option value="CYB202">1738AY - Junior Software Developer</option>
                        <option value="AI303">D1LF0 - Cyber Security Analyst</option>
                        <option value="AI303">403FR - Data Analyst</option>
                    </select>

                    <!-- Name and date of birth -->
                    <label for="name">First & Last Name:</label>
                    <input type="text" id="name" name="name" maxlength="20" required>

                    <label for="dob">Date of Birth:</label>
                    <input type="text" name="DoB" id="Dob" placeholder="dd/mm/yyyy" 
                           pattern="^(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[0-2])/\d{4}$" required>

                    <!-- Gender selection using radio buttons -->
                    <fieldset>
                        <legend>Gender</legend>
                        <input type="radio" id="male" name="gender" value="Male" required>
                        <label for="male">Male</label>

                        <input type="radio" id="female" name="gender" value="Female">
                        <label for="female">Female</label>

                        <input type="radio" id="other" name="gender" value="Other">
                        <label for="other">Other</label>
                    </fieldset>
                </fieldset>

                <!-- Address Details Section -->
                <fieldset>
                    <legend>Address Details</legend>

                    <!-- User's address fields -->
                    <label for="street">Street Address:</label>
                    <input type="text" id="street" name="street" maxlength="40" required>

                    <label for="suburb">Suburb:</label>
                    <input type="text" id="suburb" name="suburb" maxlength="40" required>

                    <!-- State and postcode -->
                    <label for="state">State:</label>
                    <select name="state" id="state" required>
                        <option value="" disabled selected>Select State</option>
                        <option value="VIC">VIC</option>
                        <option value="NSW">NSW</option>
                        <option value="QLD">QLD</option>
                        <option value="NT">NT</option>
                        <option value="WA">WA</option>
                        <option value="SA">SA</option>
                        <option value="TAS">TAS</option>
                        <option value="ACT">ACT</option>
                    </select>

                    <label for="postcode">Postcode:</label>
                    <input type="text" id="postcode" name="postcode" pattern="\d{4}" required>
                </fieldset>

                <!-- Contact & Skills Section -->
                <fieldset>
                    <legend>Contact & Skills</legend>

                    <!-- Contact info -->
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="phone">Phone Number:</label>
                    <input type="text" id="phone" name="phone" pattern="\d{8,12}" required>

                    <!-- Skill checkboxes -->
                    <label>Technical Skills:</label>
                    <input type="checkbox" id="programming" name="skills" value="Programming">
                    <label for="programming">Programming</label>

                    <input type="checkbox" id="cloud" name="skills" value="Cloud Computing">
                    <label for="cloud">Cloud Computing</label>

                    <input type="checkbox" id="security" name="skills" value="Cybersecurity">
                    <label for="security">Cybersecurity</label>

                    <input type="checkbox" id="ai" name="skills" value="AI & Data Science">
                    <label for="ai">AI & Data Science</label>

                    <!-- Text area for other skills -->
                    <label for="other-skills">Other Skills:</label>
                    <textarea id="other-skills" name="other-skills" rows="3"></textarea>
                </fieldset>

                <!-- Submit Button -->
                <button type="submit">Submit Application</button>
            </form>
        </div>
    </div>
</body>
</html>
