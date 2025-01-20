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
$name = trim($_POST['name']);
$username = trim($_POST['username']);
$phn = trim($_POST['phone']);
$email = trim($_POST['email']);
$password = trim($_POST['password']);
$cpassword = trim($_POST['cpassword']);

// Validate the input
if (empty($name) || empty($username) || empty($phn) || empty($email) || empty($password) || empty($cpassword)) {
$message = '<div class="alert alert-danger">All fields are required.</div>';
} elseif ($password !== $cpassword) {
$message = '<div class="alert alert-danger">Passwords do not match.</div>';
} else {
// Check if the username already exists
$checkQuery = "SELECT * FROM create_admin WHERE username = ?";
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
$insertQuery = "INSERT INTO create_admin (name, username, phone, email, password) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($insertQuery);
$stmt->bind_param("sssss", $name, $username, $phn, $email, $hashedPassword);

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
            <div id="hamburger">&#9776;</div>
            <div>
                <h1 id="welcomeName"></h1>
            </div>
            <div id="imageClick">
                <img id="profileImg" class="dropbtn" src="http://thememinister.com/crm/assets/dist/img/avatar5.png"
                    alt="Profile Image" />
                <div id="dropdownContent" class="dropdown-content">
                    <a href="#"><i class="fas fa-user"></i> &nbsp; My Profile</a>
                    <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> &nbsp; Signout</a>
                </div>
            </div>
        </div>
        <div id="hiddenFeatures">
            <div>Your Account</div>
            <div id="logOut">Logout</div>
        </div>
        <!-- Side Navigation Bar -->
        <div id="sideNav" style="z-index: 999;">
            <button class="closeBtn">&times;</button>
            <img src="../images/heading-logo.png" alt="">
            <ul>
                <li><i class="fas fa-home"></i><a href="./home.php">Home</a></li>
                <li><i class="fas fa-user"></i> <a href="./admin-create.php">Create Admin</a></li>
                <li><i class="fas fa-user"></i> <a href="./view-admin.php">View Admin</a></li>
                <li><i class="fas fa-user-cog"></i> <a href="./dealer-create.php">Create Dealer</a></li>
                <li><i class="fas fa-eye"></i> <a href="./dealer-view.php">View Dealer</a></li>
                <li><i class="fab fa-salesforce"></i><a href="./view-sales.php">View Sales</a></li>
                <li><i class="fab fa-first-order-alt"></i><a href="./view-orders.php">View Orders</a></li>
                <li><i class="fas fa-eye"></i><a href="./product-create.php">Create Product</a></li>
                <li><i class="fas fa-upload"></i><a href="./product-view.php">View Product</a></li>
            </ul>
        </div>
        <!-- MAIN CONTENT OF THE HOME PAGE -->
        <!-- <div id="content">
            <div class="content-box blu">
                <img src="img/video-posted.png" alt="">
                <h1>Total Video Posted</h1>
                <p id="videoPostedCount"></p>
            </div> -->
    </div>
    </div>
    <div id="content">
        <div class="container form-css mb-3 mt-5">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title"><i class="fas fa-lock-open"></i>
                        <h2>Admin Registration</h2>
                    </div>
                    <form id="createAdminData" method="POST" onsubmit="return validateForm(event);">
                        <div class=" user__details">
                            <div class="input__box">
                                <span class="details">Name</span>
                                <input type="text" name="name" id=" name" placeholder="E.g: John Smith" required>
                            </div>
                            <div class="input__box">
                                <span class="details">Username</span>
                                <input type="text" name="username" id="username" placeholder="johnWC98" required>
                            </div>
                            <div class="input__box">
                                <span class="details">Contact</span>
                                <input type="text" name="phone" id="phone" placeholder="5623XXXXX" required>
                            </div>
                            <div class="input__box">
                                <span class="details">Email</span>
                                <input type="text" name="email" id="email" placeholder="john@gmail.com" required>
                            </div>
                            <div class="input__box">
                                <span class="details">Password</span>
                                <input type="password" name="password" id="password" placeholder="********" required>
                            </div>
                            <div class="input__box">
                                <span class="details">Confirm Password</span>
                                <input type="password" name="cpassword" id="cpassword" placeholder="********" required>
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