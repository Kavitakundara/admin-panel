<?php
session_start();
include "header.php"; 
include '../conn.php'; 

// Check if 'id' is passed in the URL
if (isset($_GET['id'])) {
    $managerId = $_GET['id'];
$sql = "
    SELECT 
        cm.*, 
        po.*, 
        cs.*, 
        p.*,
        (SELECT IFNULL(SUM(p2.amount), 0) 
         FROM payment p2 
         WHERE p2.manager_id = cs.id) AS paid, 
        (IFNULL(po.pro_price, 0) - 
         (SELECT IFNULL(SUM(p2.amount), 0) 
          FROM payment p2 
          WHERE p2.manager_id = cs.id)) AS balance,
        (SELECT IFNULL(SUM(cs2.qty), 0) 
         FROM create_sales cs2 
         WHERE cs2.manager_id = cm.id) AS total_sales,  -- Total sales from create_sales
        (SELECT IFNULL(SUM(po2.quantity), 0) 
         FROM place_order po2 
         WHERE po2.manager_id = cm.id) AS total_orders,  -- Total orders from place_order
        ((SELECT IFNULL(SUM(po2.quantity), 0) 
          FROM place_order po2 
          WHERE po2.manager_id = cm.id) - (SELECT IFNULL(SUM(cs2.qty), 0) 
          FROM create_sales cs2 
          WHERE cs2.manager_id = cm.id)) AS available_stock  -- Available stock
    FROM create_manager cm
    LEFT JOIN place_order po ON cm.id = po.manager_id
    LEFT JOIN create_sales cs ON cm.id = cs.manager_id
    LEFT JOIN payment p ON cm.id = p.manager_id 
    WHERE cm.id = ?
    ORDER BY po.id DESC
";
// Fetch manager details with aggregated data
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $managerId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $manager = $result->fetch_assoc();
    $total_sales = $manager['total_sales'];
    $total_orders = $manager['total_orders'];
    $available_stock = $manager['available_stock'];
} else {
    echo "Manager not found!";
    exit();
}

// Fetch all sales records for the manager
$salesSql = "SELECT * FROM create_sales WHERE manager_id = ?";
$salesStmt = $conn->prepare($salesSql);
$salesStmt->bind_param("s", $managerId);
$salesStmt->execute();
$salesResult = $salesStmt->get_result();
$sales = [];
while ($row = $salesResult->fetch_assoc()) {
    $sales[] = $row;
}

// Fetch all orders for the manager
$orderSql = "SELECT * FROM place_order WHERE manager_id = ?";
$orderStmt = $conn->prepare($orderSql);
$orderStmt->bind_param("s", $managerId);
$orderStmt->execute();
$orderResult = $orderStmt->get_result();
$orders = [];
while ($row = $orderResult->fetch_assoc()) {
    $orders[] = $row;
}

// Fetch all payments for the manager
$paymentSql = "SELECT * FROM payment WHERE manager_id = ?";
$paymentStmt = $conn->prepare($paymentSql);
$paymentStmt->bind_param("s", $managerId);
$paymentStmt->execute();
$paymentResult = $paymentStmt->get_result();
$payments = [];
while ($row = $paymentResult->fetch_assoc()) {
    $payments[] = $row;
}

// Output the results
$response = [
    "manager" => $manager,
    "sales" => $sales,
    "orders" => $orders,
    "payments" => $payments
];


    // Fetch Dealer Product Prices Separately
    $priceQuery = "SELECT set_name, set_price, colors FROM dealer_product_prices WHERE manager_id = ? ORDER BY id DESC";
    $priceStmt = $conn->prepare($priceQuery);
    $priceStmt->bind_param("i", $managerId);
    $priceStmt->execute();
    $priceResult = $priceStmt->get_result();
} else {
    echo "Invalid ID!";
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $productNames = $_POST['product_name'] ?? [];
    $productPrices = $_POST['product_price'] ?? [];
    $colors = $_POST['color'] ?? [];

    // Validate if all fields have values
    if (!empty($productNames) && !empty($productPrices) && !empty($colors)) {
        foreach ($productNames as $index => $productName) {
            $productPrice = $productPrices[$index] ?? null;
            $productColor = $colors[$index] ?? null;

            // Ensure none of the fields are empty before inserting
            if (!empty($productName) && !empty($productPrice) && !empty($productColor)) {
                // Check if the product already exists for this manager
                $checkSql = "SELECT * FROM dealer_product_prices WHERE manager_id = ? AND set_name = ?";
                $checkStmt = $conn->prepare($checkSql);
                $checkStmt->bind_param("is", $managerId, $productName);
                $checkStmt->execute();
                $checkResult = $checkStmt->get_result();

                if ($checkResult->num_rows == 0) { // Only insert if it doesn't exist
                    $sql = "INSERT INTO dealer_product_prices (manager_id, set_name, set_price, colors) VALUES (?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("isis", $managerId, $productName, $productPrice, $productColor);
                    $stmt->execute();
                }
            }
        }

        // Redirect to avoid duplicate insertions on page refresh
        echo "<script>
                alert('Products added successfully!');
                window.location.href = window.location.pathname + '?id={$managerId}';
              </script>";
        exit();
    } else {
        echo "Please fill out all fields.";
    }
}

?>

</head>


<div id="content">
    <div class="container mt-4">
        <div class="card p-3">
            <h4 class="mb-3">Dealer Details</h4>
            <div class="mng-field">
            <div class="row g-2">
                <div class="col-md-3 col-sm-6">
                    <button class="btn detail-btn w-100">Dealer Name: <?php echo htmlspecialchars($manager['name']); ?></button>
                </div>
                <div class="col-md-3 col-sm-6">
                    <button class="btn detail-btn w-100">Contact: <?php echo htmlspecialchars($manager['contact']); ?></button>
                </div>
                <div class="col-md-3 col-sm-6">
                    <button class="btn detail-btn w-100">GST No: <?php echo htmlspecialchars($manager['gst']); ?></button>
                </div>
                <div class="col-md-3 col-sm-6">
                    <button class="btn detail-btn w-100">Dealer ID: <?php echo htmlspecialchars($managerId); ?></button>
                </div>
            </div>
            </div>
            <h4 class="mt-4 mb-3">Stats</h4>
            <div class="row g-2">
                <div class="col-md-4 col-sm-6">
                    <div class="card text-center stat-card">
                        <h6><i class="fas fa-dollar-sign"></i> Total Sales</h6>
                        <h4><?php echo htmlspecialchars($total_sales); ?></h4>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="card text-center stat-card">
                        <h6><i class="fas fa-shopping-cart"></i>Total Orders Placed</h6>
                        <h4><?php echo htmlspecialchars( $total_orders); ?></h4>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="card text-center stat-card">
                        <h6><i class="fas fa-box"></i>Available  Stock</h6>
                        <h4><?php echo htmlspecialchars($available_stock); ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Product Price -->
    <div id="lastSales">
        <h4>Product Prices</h4>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Product Price</th>
                        <th>Product Colors</th>
                    </tr>
                </thead>
    <tbody id="salesTableBody">
        <?php  
        if (!$priceResult) {
            echo "<tr><td colspan='2' style='color:red;'>Error fetching product prices: " . $conn->error . "</td></tr>";
        } elseif ($priceResult->num_rows == 0) {
            echo "<tr><td colspan='2' style='color:red;'>No product prices available.</td></tr>";
        } else {
            while ($row = $priceResult->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['set_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['set_price']) . "</td>";
                echo "<td>" . htmlspecialchars($row['colors']) . "</td>";
                echo "</tr>";
            }
        }
        ?>
    </tbody>
            </table>
        </div>
    </div>

    <!-- Amount data -->
  <!-- Amount data -->
    <div id="lastSales">
        <h4>Balance Amount</h4>
        <div class="table-responsive" style="max-height: 200px; overflow-y: auto; display: block; width: 100%;">
          <?php
// Fetch Total Sum and Total Paid before the loop starts
if (isset($managerId) && !empty($managerId)) {
    $fetchBalanceQuery = "
        SELECT 
            (SELECT COALESCE(SUM(grand_total), 0) FROM place_order WHERE manager_id = ?) AS total_sum,
            (SELECT COALESCE(SUM(amount), 0) FROM payment WHERE manager_id = ? AND status = 'Done') AS total_paid
    ";
    $stmt = $conn->prepare($fetchBalanceQuery);
    $stmt->bind_param("ii", $managerId, $managerId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $totalSum = isset($row['total_sum']) ? $row['total_sum'] : 0;
    $paidAmount = isset($row['total_paid']) ? $row['total_paid'] : 0;
    $runningBalance = $totalSum; // Initialize Running Balance
    $stmt->close();
}
?>

<table>
    <thead>
        <tr>
            <th>Total Amount</th>
            <th>Paid</th>
            <th>Payment Mode</th>
            <th>Transaction Id</th>
            <th>Transaction Date</th>
            <th>Image</th>
            <th>Balance Amount</th>
            <th>Payment Received</th>
        </tr>
    </thead>
    <tbody id="salesTableBody">
        <?php if (!empty($payments)) : ?>
            <?php foreach ($payments as $payment) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($totalSum); ?></td> <!-- Display Total Sum Once -->
                    <td><?php echo htmlspecialchars($payment['amount']); ?></td>
                    <td><?php echo htmlspecialchars($payment['payment_mode']); ?></td>
                    <td><?php echo htmlspecialchars($payment['transaction_id']); ?></td>
                    <td><?php echo htmlspecialchars($payment['transaction_date']); ?></td>
                    <td>
                        <?php if (!empty($payment['payment_screenshot'])): ?>
                            <a href="../manager/uploads/<?php echo htmlspecialchars($payment['payment_screenshot']); ?>" download>
                                <img src="../manager/uploads/<?php echo htmlspecialchars($payment['payment_screenshot']); ?>"
                                    alt="Transaction Image" style="width: 80px; height: 50px;">
                            </a>
                        <?php else: ?>
                            No image available
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($runningBalance); ?></td> <!-- Show Running Balance First -->
                    <td>
                        <form method="post">
                            <input type="hidden" name="order_id" value="<?= $payment['id'] ?>">
                            <input type="checkbox" name="toggle_status" value="1"
                                <?= $payment['status'] == 'Done' ? 'checked' : '' ?> onchange="this.form.submit()">
                        </form>
                    </td>
                </tr>
                <?php $runningBalance -= $payment['amount']; ?> <!-- Deduct Balance AFTER Displaying -->
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="8" style="text-align: center;">No sales records found</td>
            </tr>
        <?php endif; ?>
    </tbody>
  </table>
        </div>
    </div>


<!-- Last Sales -->
<div id="lastSales">
    <h4>Last Sales</h4>
    <div class="table-responsive" style="max-height: 200px; overflow-y: auto; display: block; width: 100%;">
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Buyer Name</th>
                    <th>Buyer Phone Number</th>
                    <th>Buyer Address</th>
                    <th>Total Amount</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody id="salesTableBody">
                <?php if (!empty($sales)) : ?>
                    <?php foreach ($sales as $sale) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($sale['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($sale['qty']); ?></td>
                            <td><?php echo htmlspecialchars($sale['buyer_name']); ?></td>
                            <td><?php echo htmlspecialchars($sale['buyer_no']); ?></td>
                            <td><?php echo htmlspecialchars($sale['buyer_add']); ?></td>
                            <td><?php echo htmlspecialchars($sale['price']); ?></td>
                            <td><?php echo htmlspecialchars($sale['sale_date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">No sales records found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

    <!-- Orders Details -->
    <div id="lastOrders">
        <h4>Last Orders</h4>
        <div class="table-responsive" style="max-height: 200px; overflow-y: auto; display: block; width: 100%;">
            <table>
                <thead>
                    <tr>
                       
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody id="ordersTableBody">
                    <?php if (!empty($orders)) : ?>
                    <?php foreach ($orders as $order) : ?>
                    <tr>
                    <td><?php echo htmlspecialchars($order['pro_name']); ?></td>
                    <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($order['date']); ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">No sales records found</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Set the price -->
    <div id="lastSales">
        <h4>Set The Price/Color</h4>
        <div class="form-container">
            <form id="productForm" action="" method="POST">
                <div id="productRows">
                    <!-- Initial Product Row -->
                    <div class="product-row">
                        <input type="text" name="product_name[]" placeholder="Product Name" required>
                        <input type="number" name="product_price[]" placeholder="Price" required>
                        <input type="text" name="color[]" placeholder="Add Color Name" required>
                        <button type="button" onclick="removeRow(this)">Remove</button>
                    </div>
                </div>
                <button type="button" onclick="addRow()">Add More</button>
                <button type="submit" name="submit" class="submit-btn">Submit</button>
            </form>
        </div>
    </div>

</div>

<script>
// Function to add a new row
function addRow() {
    const productRows = document.getElementById('productRows');
    const newRow = document.createElement('div');
    newRow.classList.add('product-row');
    newRow.innerHTML = `
        <input type="text" name="product_name[]" placeholder="Product Name" required>
        <input type="number" name="product_price[]" placeholder="Price" required>
        <input type="text" name="color[]" placeholder="Add Color Name" required>
        <button type="button" onclick="removeRow(this)">Remove</button>
      `;
    productRows.appendChild(newRow);
}

// Function to remove a row
function removeRow(button) {
    const row = button.parentElement;
    row.remove();
}
</script>
<script src="../js/navcss.js"></script>
<script src="../js/script.js"></script>
</body>