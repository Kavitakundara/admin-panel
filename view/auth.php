<?php
session_start();

// Check if the user is logged in and has a role
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header('Location: ../index.php'); // Redirect to login page if not authenticated
    exit();
}

$currentRole = $_SESSION['role']; 
$currentFile = basename($_SERVER['PHP_SELF']); 
$currentDirectory = basename(dirname(__FILE__)); 

// Define the role-to-directory mapping with specific paths
$roleDirectories = [
    'super-admin' => 'view/super-admin/home.php', 
    'admin' => 'view/admin/admin-home.php',             
    'manager' => 'view/manager/order-page.php',         
];

// Check if the role is valid
if (!isset($roleDirectories[$currentRole])) {
    header('Location: ../index.php'); // Redirect to login page if the role is invalid
    exit();
}

// Check if the user is in the correct directory for their role
if (strpos($roleDirectories[$currentRole], $currentDirectory) === false) {
    // Redirect to the appropriate dashboard based on the role
    header('Location: ../' . $roleDirectories[$currentRole]);
    exit();
}

// Get the user's name from the session
$userName = isset($_SESSION['name']) ? $_SESSION['name'] : 'User';
?>