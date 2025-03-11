
<?php
// Database connection details
$servername = "mysql.hostinger.com";
$username = "u138080682_superadmin";
$password = "e7+Fs?O9e+V";
$dbname = "u138080682_super_admin";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
};
?>