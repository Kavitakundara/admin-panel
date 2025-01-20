<?php
include '../auth.php';
include '../conn.php'; // Include the database connection

// Initialize filters
$startDate = $_GET['start_date'] ?? null;
$endDate = $_GET['end_date'] ?? null;

// Fetch the total quantity of orders with date filters
$orderQuery = "SELECT SUM(quantity) AS total_quantity FROM place_order";
if ($startDate && $endDate) {
    $orderQuery .= " WHERE date BETWEEN '$startDate' AND '$endDate'";
}
$orderResult = $conn->query($orderQuery);

// Check if the order query executed successfully
if ($orderResult === false) {
    die("Error executing order query: " . $conn->error);
}

$totalQuantity = 0;
if ($orderResult->num_rows > 0) {
    $orderData = $orderResult->fetch_assoc();
    $totalQuantity = $orderData['total_quantity'] ?? 0;
}

// Fetch the total sales quantity (sum of qty) and total sales amount with date filters
$salesQuery = "SELECT SUM(qty) AS total_sales_quantity, SUM(price) AS total_amount FROM create_sales";
if ($startDate && $endDate) {
    $salesQuery .= " WHERE sale_date BETWEEN '$startDate' AND '$endDate'";
}
$salesResult = $conn->query($salesQuery);

// Check if the sales query executed successfully
if ($salesResult === false) {
    die("Error executing sales query: " . $conn->error);
}

$salesData = ['total_sales_quantity' => 0, 'total_amount' => 0];

// Check if any rows are returned from the query
if ($salesResult->num_rows > 0) {
    $salesData = $salesResult->fetch_assoc();
}

// Safely accessing the data
$totalSalesQuantity = $salesData['total_sales_quantity'] ?? 0;
$totalSalesAmount = $salesData['total_amount'] ?? 0;

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
    <script src="../js/istocken.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <title>View Manager</title>
    <style>
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: 0.4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: 0.4s;
    }

    input:checked+.slider {
        background-color: #2196f3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196f3;
    }

    input:checked+.slider:before {
        transform: translateX(26px);
    }

    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert -->
</head>

<body>

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

    </div>

    <body>
        <div id="main-container">
            <!-- Filter Form -->
            <form method="GET" class="row g-3 my-3 mx-3">
                <div class="col-auto">
                    <label for="start_date" class="form-label">Start Date:</label>
                    <input type="date" id="start_date" name="start_date" class="form-control" value="<?= $startDate ?>">
                </div>
                <div class="col-auto">
                    <label for="end_date" class="form-label">End Date:</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" value="<?= $endDate ?>">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary mt-4">Apply Filter</button>
                </div>
            </form>

            <div id="content">
                <div class="row data-box">
                    <div class="col-lg-4 col-md-3 col-sm-12">
                        <div id="totalOrdersBox" class="mg-box">
                            <i class="fas fa-cart-arrow-down"></i>
                            <h3>Total Orders Count</h3>
                            <div class="data">
                                <h4 id="totalOrders"><?= $totalQuantity ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-3 col-sm-12">
                        <div id="totalSalesBox" class="mg-box">
                            <i class="fas fa-chart-line"></i>
                            <h3>Total Sales Count</h3>
                            <div class="data">
                                <h4 id="totalSales"><?= $totalSalesQuantity ?></h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-3 col-sm-12">
                        <div id="totalSalesAmount" class="mg-box">
                            <i class="fas fa-rupee-sign"></i>
                            <h3>Total Amount Sales</h3>
                            <div class="data">
                                <h4 id="totalSalesDigits">Rs. <?= number_format($totalSalesAmount, 2) ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="../js/navcss.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>

</html>