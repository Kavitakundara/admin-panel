<?php
session_start();
include '../conn.php';
include 'header.php';// Include the database connection

// Ensure session is active
if (!isset($_SESSION['username'])) {
    die("User not logged in.");
}

$manager_id = $_SESSION['username']; 

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "
    SELECT 
        p.id AS id,
        p.manager_id AS mng_id,
        p.name AS name,
        p.inventory_count,
        IFNULL(SUM(cs.qty), 0) AS sold_quantity,
        (p.inventory_count - IFNULL(SUM(cs.qty), 0)) AS remaining_inventory
    FROM u138080682_super_admin.create_products p
    LEFT JOIN u138080682_super_admin.product_chassis_numbers c ON p.id = c.product_id
    LEFT JOIN create_sales cs ON c.chassis_no = cs.chassis_no
    GROUP BY p.id
    HAVING remaining_inventory > 0";

$result = $conn->query($sql);

$pro_inv = 0; // Initialize to 0
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pro_inv += $row['remaining_inventory']; // Sum all remaining inventory
    }
} else {
    $pro_inv = 0; // No stock available
}
// Initialize filters
$startDate = $_GET['start_date'] ?? null;
$endDate = $_GET['end_date'] ?? null;

// Prepare and execute the order query
$orderQuery = "SELECT SUM(quantity) AS total_quantity FROM place_order";
if ($startDate && $endDate) {
    $orderQuery .= " WHERE date BETWEEN ? AND ?";
}
$stmt = $conn->prepare($orderQuery);
if ($startDate && $endDate) {
    $stmt->bind_param('ss', $startDate, $endDate);
}
$stmt->execute();
$orderResult = $stmt->get_result();
$orderData = $orderResult->fetch_assoc();

// Prepare and execute the sales query
$salesQuery = "SELECT SUM(qty) AS total_sales_quantity, SUM(price) AS total_amount FROM create_sales";
if ($startDate && $endDate) {
    $salesQuery .= " WHERE sale_date BETWEEN ? AND ?";
}
$stmt = $conn->prepare($salesQuery);
if ($startDate && $endDate) {
    $stmt->bind_param('ss', $startDate, $endDate);
}
$stmt->execute();
$salesResult = $stmt->get_result();
$salesData = $salesResult->fetch_assoc();

$totalSalesQuantity = $salesData['total_sales_quantity'] ?? 0;
$totalSalesAmount = $salesData['total_amount'] ?? 0;

// Calculate GST and final amount
$gstAmount = $totalSalesAmount * 0.05; // 5% GST
$finalAmount = $totalSalesAmount - $gstAmount;

// Fetch ledger details separately
$sql_ledger = "
    SELECT 
        (SELECT SUM(grand_total) FROM place_order) AS total_sum,  
        (SELECT SUM(amount) FROM payment) AS paid_amount
";
$ledger_result = $conn->query($sql_ledger);
$ledger = $ledger_result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
</head>

<body>

    <div id="content">
        <div class="container mt-3 mng-form">
            <div class="row">
                <div class="col-lg-10 offset-2">

            <form method="GET" class="row g-3 my-3 mx-3">
                <div class="col-auto d-flex">
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
        </div>
    </div>
</div>
        <div class="row data-box">
            <div class="col-lg-4 col-md-3 col-sm-12">
                <div id="totalOrdersBox" class="mg-box">
                    <i class="fas fa-cart-arrow-down"></i>
                    
                    <div class="data">
                        <h4 id="totalOrders"><?= $pro_inv ?></h4>
                        <h3>Available Stock</h3>
                        
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-3 col-sm-12">
                <div id="totalSalesBox" class="mg-box">
                    <i class="fas fa-chart-line"></i>
                    
                    <div class="data">
                        <h4 id="totalSales"><?= $totalSalesQuantity ?></h4>
                        <h3>Total Sales Count</h3>
                        
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-3 col-sm-12">
                <div id="totalSalesAmount" class="mg-box">
                    <i class="fas fa-rupee-sign"></i>
                    
                    <div class="data">
                        <h4 id="totalSalesDigits">Rs. <?=($ledger['total_sum'] ?? 0) - ($ledger['paid_amount'] ?? 0) ?></h4>
                        <h3>Balance Payment</h3>
                        
                    </div>
                </div>
            </div>
            <!--<div class="col-lg-3 col-md-3 col-sm-12">-->
            <!--    <div id="totalSalesAmount" class="mg-box">-->
            <!--        <i class="fas fa-rupee-sign"></i>-->
            <!--        <h3>Total Amount Sales</h3>-->
            <!--        <div class="data">-->
            <!--            <h4 id="totalSalesDigits">Rs. <?= number_format($finalAmount, 2) ?></h4>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--</div>-->
        </div>
    </div>

    <script src="../js/navcss.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>