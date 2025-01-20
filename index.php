<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$superAdminDB = "super_admin"; // super-admin database name

// Create connection for super_admin database
$conn = new mysqli($servername, $username, $password, $superAdminDB);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Redirect to dashboard if session is already active
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'super-admin') {
        header('Location: super-admin/home.php'); // Redirect to super-admin dashboard
        exit();
    } elseif ($_SESSION['role'] === 'admin') {
        header('Location: admin/admin-home.php'); // Redirect to admin dashboard
        exit();
    } elseif ($_SESSION['role'] === 'manager') {
        header('Location: manager/manager-home.php'); // Redirect to manager dashboard
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check in super-admin's 'user' table
    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $superAdminUser = $result->fetch_assoc();

    if ($superAdminUser && password_verify($password, $superAdminUser['password'])) {
        // If super-admin is found, set session and redirect to super-admin dashboard
        $_SESSION['user_id'] = $superAdminUser['id'];
        $_SESSION['username'] = $superAdminUser['username'];
        $_SESSION['role'] = 'super-admin';
        header('Location: super-admin/home.php');
        exit();
    }

    // Check in admin's 'create_admin' table
    $stmt = $conn->prepare("SELECT * FROM create_admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $adminUser = $result->fetch_assoc();

    if ($adminUser && password_verify($password, $adminUser['password'])) {
        // If admin is found, set session and redirect to admin dashboard
        $_SESSION['user_id'] = $adminUser['id'];
        $_SESSION['username'] = $adminUser['username'];
        $_SESSION['role'] = 'admin';
        header('Location: admin/admin-home.php');
        exit();
    }

    // Check in manager's 'create_manager' table
    $stmt = $conn->prepare("SELECT * FROM create_manager WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $managerUser = $result->fetch_assoc();

    if ($managerUser && password_verify($password, $managerUser['password'])) {
        // If manager is found, set session and redirect to manager dashboard
        $_SESSION['user_id'] = $managerUser['id'];
        $_SESSION['username'] = $managerUser['username'];
        $_SESSION['role'] = 'manager';
        header('Location: manager/manager-home.php');
        exit();
    }

    // If no match is found
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Invalid Credentials',
            text: 'Please check your username or password and try again.'
        });
    </script>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/style.css" />
    <title>Home</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body style="background-color: #2c323a">
    <div id="preloader">
        <div id="loader">
            <img src="https://www.rayonengineers.com/assets/img/logo.png" alt="Loading..." />
        </div>
    </div>

    <!-- Main Content -->
    <div id="main-content" style="display: none">
        <!-- Your main content goes here -->
        <div class="form-sec">
            <img src="https://www.rayonengineers.com/assets/img/logo.png" alt="" class="login-img" />
            <form class="container-2" id="login-form" method="POST" novalidate>
                <div>
                    <h3>Welcome Back</h3>
                </div>
                <div>
                    <label for="username">Username or Email</label>
                    <input id="username" type="text" name="username" required minlength="4" />
                    <span class="error" aria-live="polite"></span>
                </div>
                <div>
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required minlength="8" />
                    <a href="#">Forgot your password?</a>
                </div>
                <button type="submit" id="submit-btn">Login</button>
                <span aria-live="assertive" id="js-loadingMsg" class="sr-only">
                    <!-- Use JavaScript to inject the loading message -->
                </span>
            </form>
        </div>
    </div>

    <script>
    // Function to simulate loading time and hide preloader
    window.addEventListener("load", function() {
        setTimeout(function() {
            document.getElementById("preloader").style.display = "none";
            document.getElementById("main-content").style.display = "block";
        }, 2000); // Simulate a 2-second loading time
    });
    </script>

</body>

</html>