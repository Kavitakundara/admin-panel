<?php
include '../auth.php';
session_start();
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
if (!isset($_SESSION['user_id'])) {
die("Access denied. Please log in first.");
}
// Get the logged-in user's ID
$created_by = $_SESSION['user_id'];
// Initialize an empty error or success message
$message = '';
$isDuplicate = false; // To track if the username is a duplicate


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// Get the form data
$name = trim($_POST['name']);
$user = trim($_POST['username']);
$phn = trim($_POST['phone']);
$add = trim($_POST['address']);
$role = trim($_POST['role']);
$password = trim($_POST['password']);
$cpassword = trim($_POST['cpassword']);

// Validate the input
if (empty($name) || empty($user) || empty($phn) || empty($add) || empty($role) || empty($password) || empty($cpassword
)) {
$message = '<div class="alert alert-danger">All fields are required.</div>';
} elseif ($password !== $cpassword) {
$message = '<div class="alert alert-danger">Passwords do not match.</div>';
} else {
// Check if the username already exists
$checkQuery = "SELECT * FROM create_admin WHERE username = ?";
$stmt = $conn->prepare($checkQuery);
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
$isDuplicate = true;
} else {
// Hash the password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Insert the admin into the database
$insertQuery = "INSERT INTO create_manager (name, username, contact, role, address, password, created_by) VALUES (?, ?,
?, ?, ?,?,?)";
$stmt = $conn->prepare($insertQuery);
$stmt->bind_param("sssssss", $name, $user, $phn, $add, $role, $hashedPassword, $created_by);

if ($stmt->execute()) {
$message = '<div class="alert alert-success">Admin created successfully.</div>';
} else {
$message = '<div class="alert alert-danger">Error creating admin. Please try again.</div>';
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <script src="../js/istocken.js"></script>
    <title>Create Manager</title>
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
                    <a href="#" onclick="logout()"><i class="fas fa-sign-out-alt"></i> &nbsp; Signout</a>
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
                <!-- <li><i class="fas fa-home"></i><a href="./admin-home.php">Home</a></li> -->
                <li><i class="fas fa-user-cog"></i> <a href="./manager-create.php">Create Manager</a></li>
                <li><i class="fas fa-eye"></i><a href="./admin-view-mng.php">View Manager</a></li>
                <li><i class="fab fa-salesforce"></i><a href="./admin-mg-sales.php">View Sales</a></li>
                <li><i class="fab fa-first-order-alt"></i><a href="./admin-mg-orders.php">View Orders</a></li>
            </ul>
        </div>
    </div>

    <div id="content">
        <div class="container form-css mb-3 mt-5">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title"><i class="fas fa-lock-open"></i>
                        <h2>Manager Registration</h2>
                    </div>
                    <form id="ManagerRegistration" class="form_c" method="POST">
                        <div class="user__details">
                            <div class="input__box">
                                <span class="details">Name</span>
                                <input type="text" id="name" name="name" placeholder="E.g: John Smith" required>
                            </div>
                            <div class="input__box">
                                <span class="details">Username</span>
                                <input type="text" id="username" name="username" placeholder="johnWC98" required>
                            </div>
                            <div class="input__box">
                                <span class="details">Password</span>
                                <input type="password" id="password" name="password" placeholder="********" required>
                            </div>
                            <div class="input__box">
                                <span class="details">Confirm Password</span>
                                <input type="password" id="cpassword" name="cpassword" placeholder="********" required>
                            </div>
                            <div class="input__box ">
                                <span class="details">Address</span>
                                <input name="address" id="address" placeholder="Enter Your Address"></input>

                            </div>
                            <div class="input__box ">
                                <span class="details">Contact</span>
                                <input name="phone" id="contact" placeholder="9999XXXXXX"></input>

                            </div>
                        </div>

                        <div class="gender__details gender_d">
                            <span class="gender__title role_b">Role</span>
                            <div class="category manager_m" id="role">
                                <label for="dealer">
                                    <input type="radio" id="dealer" name="role" value="dealer" required>
                                    <!-- <span class="dot one"></span> -->
                                    &nbsp; <span>Dealer</span>
                                </label>
                                <label for="distributor">
                                    <input type="radio" id="distributor" name="role" value="distributor" required>
                                    <!-- <span class="dot two"></span> -->
                                    &nbsp; <span>Distributor</span>
                                </label>
                            </div>
                        </div>
                        <!-- Hidden field to capture the creator's user ID -->
                        <input type="hidden" name="created_by" value="<?php echo $_SESSION['user_id']; ?>">
                        <div class="btn-css">
                            <button type="submit" id="regi-btn" class="button">Register</button>
                            <button type="submit" id="login-btn" class="button"><a
                                    href="../index.php">Login</a></button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    </div>

    <script src="../js/navcss.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>