<?php
session_start();
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session
header('Location: https://rayonengineers.com/rayon-admin/index.php'); // Redirect to login page
exit();
?>