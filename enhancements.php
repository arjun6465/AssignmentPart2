<?php
  include("header.inc");
  include("nav.inc");
?>

<main class="job-page">
  <h1>Enhancements</h1>

  <section>
    <h2>1. Sort EOIs by Selected Field</h2>
    <p>
      The <code>manage.php</code> page allows the manager to sort Expression of Interest (EOI) records by any selectable field, such as job reference number, applicant name.
    </p>
  </section>

  <section>
    <h2>2. Manager Registration with Validation</h2>
    <p>
      A dedicated manager registration page allows new managers to create accounts. Server-side validation ensures that usernames are unique and passwords meet complexity rules (minimum length, includes uppercase, lowercase, and a number). Validated accounts are stored in a <code>user</code> table with hashed passwords using <code>password_hash()</code>.
    </p>
  </section>

  <section>
    <h2>3. Protected Access to <code>manage.php</code></h2>
    <p>
      Access to <code>manage.php</code> is restricted to logged-in users. The script checks for a valid session containing the manager’s username. If the session is not active, the user is redirected to the login page, ensuring that only authenticated managers can view or manage EOIs.
    </p>
  </section>
</main>

<?php
  include("footer.inc");
?>
