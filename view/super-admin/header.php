<?php
include '../conn.php'; // Database connection

// Count unread notifications
$sql = "SELECT COUNT(*) AS unread_count FROM notifications WHERE is_read = 0";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$unread_count = $row['unread_count'];
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" />
    <script src="../js/istocken.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
    <title>View Orders</title>
</head>

<body>
    <div id="main-container">
        <!-- Top Navigation Bar -->
        <div id="topNav">
            <div id="hamburger">&#9776;</div>
            <div>
                <!-- <h1 id="welcomeName">Welcome, <?php echo htmlspecialchars($userName); ?></h1> -->
            </div>

            <div id="imageClick">
                <img id="profileImg" class="dropbtn" src="../images/heading-logo.png"
                    alt="Profile Image" />
                <div id="dropdownContent" class="dropdown-content">
                    <!-- <a href="#"><i class="fas fa-user"></i> &nbsp; My Profile</a> -->
                    <a href="https://rayonengineers.com//rayon-admin/index.php" onclick="logout()"><i
                            class="fas fa-sign-out-alt"></i> &nbsp;
                        Signout</a>
                </div>
            </div>
        </div>

        <!-- Side Navigation Bar -->
        <div id="sideNav">
            <button class="closeBtn">&times;</button>

            <ul>
                <li><i class="fas fa-home"></i><a href="./super-admin-home.php">Home</a></li>
                <li><i class="fas fa-user"></i> <a href="./admin-create.php">Create Admin</a></li>
                <li><i class="fas fa-user"></i> <a href="./view-admin.php">View Admin</a></li>
                <li><i class="fas fa-user-cog"></i> <a href="./dealer-create.php">Create Dealer</a></li>
                <li><i class="fas fa-eye"></i> <a href="./dealer-view.php">View Dealer</a></li>
                <li><i class="fab fa-salesforce"></i><a href="./view-sales.php">View Sales</a></li>
                <li><i class="fab fa-first-order-alt"></i><a href="./view-orders.php">View Orders</a></li>
                <li><i class="fas fa-eye"></i><a href="./product-create.php">Create Product</a></li>
                <li><i class="fas fa-upload"></i><a href="./product-view.php">View Product</a></li>
                <li><i class="fas fa-upload"></i><a href="./LOI.php">LOI Form</a></li>
                <li><a href="notification.php" class="nav-link">Notifications<?php if ($unread_count > 0): ?>
                <span style="color: red;">●</span>
                <?php endif; ?>
    </a>
</li>
                <!--<li><i class="fas fa-upload"></i> <a href="./notification.php">Notifications</a></li>-->
            </ul>
        </div>
    </div>