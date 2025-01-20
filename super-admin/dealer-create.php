<?php
session_start();
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

// Initialize variables
$message = '';
$alertType = '';
$alertMessage = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $phn = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $role = trim($_POST['role']);
    $gst = trim($_POST['gst']);
    $password = trim($_POST['password']);
    $cpassword = trim($_POST['cpassword']);

    // Password validation
    if ($password !== $cpassword) {
        $alertType = 'error';
        $alertMessage = 'Passwords do not match!';
    } else {
        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Handle file upload
        $uploadOk = true;
        $targetDir = "doc_upload/";
        $image = $_FILES['doc'];
        $imageName = time() . '_' . basename($image['name']);
        $targetFilePath = $targetDir . $imageName;

        // Validate file type and size
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        if (!in_array($fileType, ['jpg', 'jpeg', 'png', 'pdf'])) {
            $alertType = 'error';
            $alertMessage = 'Invalid file type. Only JPG, JPEG, PNG, and PDF are allowed.';
            $uploadOk = false;
        } elseif ($image['size'] > 5 * 1024 * 1024) {
            $alertType = 'error';
            $alertMessage = 'File size exceeds 5MB.';
            $uploadOk = false;
        }

        if ($uploadOk && move_uploaded_file($image['tmp_name'], $targetFilePath)) {
            // Check for duplicate username
            $checkQuery = $conn->prepare("SELECT id FROM create_manager WHERE username = ?");
            $checkQuery->bind_param("s", $username);
            $checkQuery->execute();
            $checkQuery->store_result();

            if ($checkQuery->num_rows > 0) {
                $alertType = 'error';
                $alertMessage = 'Username already exists!';
            } else {
                // Insert data into the database
                $insertQuery = $conn->prepare("INSERT INTO create_manager (name, username, password, address, contact, role, gst, document, document_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $documentType = $fileType; // Document type
                $insertQuery->bind_param("sssssssss", $name, $username, $hashedPassword, $address, $phn, $role, $gst, $imageName, $documentType);

                if ($insertQuery->execute()) {
                    $alertType = 'success';
                    $alertMessage = 'Manager registered successfully!';
                } else {
                    $alertType = 'error';
                    $alertMessage = 'Database error: ' . $conn->error;
                }
            }
            $checkQuery->close();
        } else {
            $alertType = 'error';
            $alertMessage = 'Failed to upload the document.';
        }
    }
}

$conn->close();
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
                        <h2>Dealer Registration</h2>
                    </div>
                    <form id="ManagerRegistration" class="form_c" method="POST" enctype="multipart/form-data" action="">
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
                            <div class="input__box ">
                                <span class="details">GST NO.</span>
                                <input type="text" name="gst" id="contact" placeholder="9999XXXXXX"></input>

                            </div>
                            <div class="input__box ">
                                <span class="details">Document</span>
                                <input type="file" name="doc" id="contact" placeholder="Upload Document"
                                    accept="image/*,application/pdf"></input>

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
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const alertType = '<?= $alertType ?>';
        const alertMessage = '<?= $alertMessage ?>';

        if (alertType && alertMessage) {
            Swal.fire({
                icon: alertType,
                title: alertType === 'success' ? 'Success' : 'Error',
                text: alertMessage,
            });
        }
    });
    </script>
    <script src="../js/navcss.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>