<?php
include '../auth.php';
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "super_admin";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}

// Initialize an empty error or success message
$message = '';
$isDuplicate = false; // To track if the username is a duplicate


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// Get the form data
$username = trim($_POST['username']);
$password = trim($_POST['password']);
// Validate the input
if (empty($username) || empty($password)) {
$message = '<div class="alert alert-danger">All fields are required.</div>';
}else {
// Check if the username already exists
$checkQuery = "SELECT * FROM user WHERE username = ?";
$stmt = $conn->prepare($checkQuery);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
$isDuplicate = True;
} else {
// Hash the password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Insert the admin into the database
$insertQuery = "INSERT INTO user (username, password) VALUES (?, ?)";
$stmt = $conn->prepare($insertQuery);
$stmt->bind_param("ss", $username, $hashedPassword);

if ($stmt->execute()) {
$message = 'Admin created successfully.';
} else {
$message = 'Error creating admin. Please try again.';
}
}
}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="../js/istocken.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert -->
    <title>Admin Create</title>
</head>

<body style="background: linear-gradient(135deg, #71b7e6, #9b59b6);">
    <div id="main-container">
        <!-- Top Navigation Bar -->
        <div id="topNav">

            <div id="imageClick">
                <img id="profileImg" class="dropbtn" src="https://www.rayonengineers.com/assets/img/logo.png"
                    alt="Profile Image" />
            </div>
        </div>
        <!-- Side Navigation Bar -->
    </div>
    </div>
    <div id="content">
        <div class="container form-css mb-3 mt-5">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title"><i class="fas fa-lock-open"></i>
                        <h2>Super Admin Registration</h2>
                    </div>
                    <form id="createAdminData" method="POST" onsubmit="return validateForm(event);">
                        <div class=" user__details">
                            <div class="input__box">
                                <span class="details">Username</span>
                                <input type="text" name="username" id="username" placeholder="johnWC98" required>
                            </div>

                            <div class="input__box">
                                <span class="details">Password</span>
                                <input type="password" name="password" id="password" placeholder="********" required>
                            </div>
                        </div>
                        <div class="btn-css btn-b">
                            <button type="submit" id="regi-btn" class="button">Register</button>
                            <button type="button" id="login-btn" class="button"><a
                                    href="../index.php">Login</a></button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
    </div>
    <script>
    function validateForm(event) {
        const password = document.getElementById('password').value;
        const cpassword = document.getElementById('cpassword').value;

        if (password !== cpassword) {
            event.preventDefault(); // Prevent the form from being submitted
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Passwords do not match!',
            });
            return false;
        }
        return true;
    }
    </script>
    <script>
    // Display an alert if the username already exists
    const isDuplicate = <?php echo json_encode($isDuplicate); ?>;
    if (isDuplicate) {
        Swal.fire({
            icon: 'error',
            title: 'Duplicate Username',
            text: 'The username already exists. Please choose another one.',
        });
    }

    // Display an alert for server-side error messages
    const serverMessage = <?php echo json_encode($message); ?>;
    if (serverMessage) {
        Swal.fire({
            icon: serverMessage.includes('success') ? 'success' : 'error',
            title: serverMessage.includes('success') ? 'Success' : 'Error',
            text: serverMessage,
        });
    }
    </script>
    <script src="../js/navcss.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>