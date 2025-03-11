<?php
session_start();
include 'view/conn.php';
if ($conn->connect_error) {
    die("Connection to manager DB failed: " . $conn->connect_error);
}

// Redirect to the respective dashboard if a session is active
// if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
//     switch ($_SESSION['role']) {
//         case 'super-admin':
//             header('Location: view/super-admin/super-admin-home.php');
//             exit();
//         case 'admin':
//             header('Location: view/admin/admin-home.php');
//             exit();
//         case 'manager':
//             header('Location: view/manager/manager-home.php');
//             exit();
//     }
// }

// Handle POST request for login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Array of roles, their respective tables, and database connections
    $roles = [
        'super-admin' => ['table' => 'user', 'conn' => $conn],
        'admin' => ['table' => 'create_admin', 'conn' => $conn],
        'manager' => ['table' => 'create_manager', 'conn' => $conn],
    ];

    foreach ($roles as $role => $details) {
        $conn = $details['conn'];
        $table = $details['table'];

        $stmt = $conn->prepare("SELECT * FROM $table WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];

            // Redirect to the respective dashboard
            header("Location: view/$role/{$role}-home.php");
            exit();
        }
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="view/css/style.css">
    <title>Login</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body style="background-color: #2c323a;">
    <div id="preloader">
        <div id="loader">
            <img src="https://www.rayonengineers.com/assets/img/logo.png" alt="Loading...">
        </div>
    </div>

    <div id="main-content" style="display: none;">
        <div class="container mng-form">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h3>Welcome Back</h3>
                    <p>This portal is exclusively designed for our trusted dealers and partners. Please log in with your
                        credentials to access your dashboard.</p>
                </div>
                <div class="col-lg-6">
                    <div class="form-sec">
                        <form id="login-form" method="POST">
                            <div>
                                <input id="username" type="text" name="username" required minlength="4"
                                    placeholder="Username">
                            </div>
                            <div>
                                <input id="password" type="password" name="password" required minlength="4"
                                    placeholder="Password">
                            </div>
                            <button type="submit">Login</button>
                            <a href="https://www.rayonengineers.com/">Back To The Website</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Hide preloader after loading
    window.addEventListener("load", function() {
        setTimeout(() => {
            document.getElementById("preloader").style.display = "none";
            document.getElementById("main-content").style.display = "block";
        }, 2000);
    });
    </script>
</body>

</html>