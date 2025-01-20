<?php
include '../auth.php';
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

// Initialize variables for SweetAlert
$alertType = '';
$alertMessage = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $inventoryCount = $conn->real_escape_string($_POST['inventory_count']);
    $slug = $conn->real_escape_string($_POST['slug']);
    
    // Handle file upload
    $targetDir = "uploads/";
    $image = $_FILES['image'];
    $imageName = time() . '_' . basename($image['name']); // Add timestamp for uniqueness
    $targetFilePath = $targetDir . $imageName;

    // Validate and move the uploaded file
    if (move_uploaded_file($image['tmp_name'], $targetFilePath)) {
        $sql = "INSERT INTO create_products (name, inventory, slug, img) VALUES ('$name', '$inventoryCount', '$slug', '$imageName')";
        
        if ($conn->query($sql) === TRUE) {
            $alertType = 'success';
            $alertMessage = 'Product added successfully!';
        } else {
            $alertType = 'error';
            $alertMessage = "Database error: " . $conn->error;
        }
    } else {
        $alertType = 'error';
        $alertMessage = 'Failed to upload image. Please try again.';
    }
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
    <link rel="stylesheet" href="../css/style.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="../js/istocken.js"></script>
    <title>Create Products</title>
    <style>
    .input__box input[type="file"] {
        display: block;
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        cursor: pointer;
        background-color: #f9f9f9;
    }

    @media (max-width: 667px) {
        .input__box input[type="file"] {
            display: flex;
            font-size: 16px;
            padding: 10px;
            justify-content: center;
        }
    }
    </style>
</head>

<body style="background: linear-gradient(135deg, #71b7e6, #9b59b6)">
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
    </div>
    <div id="content">
        <div class="container mt-5 form-css">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title"><i class="fas fa-cart-arrow-down"></i>
                        <h2>Create Product!!</h2>
                    </div>
                    <form id="create-prod" method="POST" action="" enctype="multipart/form-data">
                        <div class="user__details">
                            <div class="input__box">
                                <span class="details">Name</span>
                                <input type="text" id="name" name="name" placeholder="Enter Your Name" required />
                            </div>
                            <div class="input__box">
                                <span class="details">Inventory count</span>
                                <input type="text" id="inventory-count" name="inventory_count"
                                    placeholder="Inventory count" required />
                            </div>
                            <div class="input__box">
                                <span class="details">Slug</span>
                                <input type="text" id="slug" placeholder="Slug" name="slug" required />
                            </div>
                            <div class="input__box">
                                <span class="details">Image</span>
                                <input type="file" name="image" id="img" accept="image/*" required />
                            </div>
                        </div>
                        <button type="submit" id="regi-btn" class="button btn_d">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="../js/navcss.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    // SweetAlert based on PHP response
    <?php if (!empty($alertType) && !empty($alertMessage)): ?>
    Swal.fire({
        icon: '<?php echo $alertType; ?>',
        title: '<?php echo ucfirst($alertType); ?>',
        text: '<?php echo $alertMessage; ?>',
        showConfirmButton: true,
    });
    <?php endif; ?>
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>