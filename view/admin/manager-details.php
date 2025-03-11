<?php
session_start();
include '../conn.php'; // To ensure the user is authenticated


// Check if 'id' is passed in the URL
if (isset($_GET['id'])) {
    $managerId = $_GET['id'];
$sql = "
    SELECT 
        cm.*, 
        po.*, 
        cs.*, 
        p.*, 
        pr.*, 
        -- Calculate total sum of grand_total from place_order for this manager
        (SELECT IFNULL(SUM(po2.grand_total), 0) 
         FROM place_order po2 
         WHERE po2.manager_id = cm.id) AS total_sum, 
        -- Calculate total paid amount from payment table for this manager
        (SELECT IFNULL(SUM(p2.amount), 0) 
         FROM payment p2 
         WHERE p2.manager_id = cm.id) AS paid, 
        -- Calculate balance (total order price - total paid amount)
        (IFNULL(po.pro_price, 0) - 
         (SELECT IFNULL(SUM(p3.amount), 0) 
          FROM payment p3 
          WHERE p3.manager_id = cm.id)) AS balance,
        -- Calculate total sales from create_sales
        (SELECT IFNULL(SUM(cs2.qty), 0) 
         FROM create_sales cs2 
         WHERE cs2.manager_id = cm.id) AS total_sales,
        -- Calculate total orders from place_order
        (SELECT IFNULL(SUM(po2.quantity), 0) 
         FROM place_order po2 
         WHERE po2.manager_id = cm.id) AS total_orders,
        -- Calculate available stock (total orders - total sales)
        ((SELECT IFNULL(SUM(po2.quantity), 0) 
          FROM place_order po2 
          WHERE po2.manager_id = cm.id) - 
         (SELECT IFNULL(SUM(cs2.qty), 0) 
          FROM create_sales cs2 
          WHERE cs2.manager_id = cm.id)) AS available_stock
    FROM create_manager cm
    LEFT JOIN place_order po ON cm.id = po.manager_id
    LEFT JOIN create_sales cs ON cm.id = cs.manager_id
    LEFT JOIN dealer_product_prices pr ON cm.id = pr.manager_id
    LEFT JOIN payment p ON cm.id = p.manager_id 
    WHERE cm.id = ?
    ORDER BY po.id DESC;
";


  $stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $managerId); // Use "i" for integer, change to "s" if string
if (!$stmt->execute()) {
    echo "Error: " . $stmt->error;
} else {
    $result = $stmt->get_result();
    $product_names = [];
    $product_prices = [];
   
    if ($result->num_rows > 0) {
        $manager = $result->fetch_assoc();
        // Access new fields
        $total_sales = $manager['total_sales'];
        $total_orders = $manager['total_orders'];
        $available_stock = $manager['available_stock'];
    } else {
        echo "Manager not found!";
        exit();
    }
}}
else {
    echo "Invalid ID!";
    exit();
}

if (isset($_POST['submit'])) {
    $productNames = $_POST['product_name'];
    $productPrices = $_POST['product_price'];
    $colors = $_POST['color'];

    if (!empty($productNames) && !empty($productPrices)) {
        $managerId = $_GET['id']; 

        // Prepare and insert each row into the database
        foreach ($productNames as $index => $productName) {
            $productPrice = $productPrices[$index];
            $productColor = $colors[$index];
            
            $sql = "INSERT INTO dealer_product_prices (manager_id, set_name, set_price, colors) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isi", $managerId, $productName, $productPrice, $productColor);
            $stmt->execute();
        }

        $stmt->close();
        $conn->close();

        echo "Products added successfully!";
    } else {
        echo "Please fill out all fields.";
    }
}
// Initialize variables
$totalSum = 0;
$runningBalance = 0;
if ($row = $result->fetch_assoc()) {
    $totalSum = $row['total_sum'] ?? 0;
      $runningBalance = $totalSum;
}

?>
<?php include 'header.php'; ?>

<div id="content">
    <!-- Adding the Dealer/Distributor here -->
    <div>
        <p>Manager Name: <?php echo htmlspecialchars($manager['name']); ?></p>
        <p>Contact: <?php echo htmlspecialchars($manager['contact']); ?></p>
        <p>GST Number: <?php echo htmlspecialchars($manager['gst']); ?></p>
        <p>Manager Id: <?php echo htmlspecialchars($managerId); ?>

        </p>
    </div>
<div class="container">    
<div class="row">
<div class="col-md-4 col-sm-6">
    <div class="card text-center stat-card">
        <h6><i class="fas fa-shopping-cart"></i> Orders</h6>
        <h4><?= $total_orders ?></h4>
    </div>
</div>
<div class="col-md-4 col-sm-6">
    <div class="card text-center stat-card">
        <h6><i class="fas fa-dollar-sign"></i> Sales</h6>
        <h4><?= $total_sales ?></h4>
    </div>
</div>
<div class="col-md-4 col-sm-6">
    <div class="card text-center stat-card">
        <h6><i class="fas fa-box"></i> Stock</h6>
        <h4><?= $available_stock ?></h4>
    </div>
</div>
</div>
</div>
    <!--Product Price -->
    <div id="lastSales">
        <h4>Product Prices</h4>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Product Price</th>
                    </tr>
                </thead>
                <tbody id="salesTableBody">
                    <?php  
                while ($row = $result->fetch_assoc()) {
                    // You can fetch the product name and price from the row and store them into arrays
                    $product_names[] = $row['set_name'];  // Assuming 'set_name' is the product name column
                    $product_prices[] = $row['set_price'];  // Assuming 'set_price' is the price column
                } 
                echo "<table>";
              
                for ($i = 0; $i < count($product_names); $i++) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($product_names[$i]) . "</td>";
                    echo "<td>" . htmlspecialchars($product_prices[$i]) . "</td>";
                    echo "</tr>";
                }

                echo "</table>";
                ?>

            </table>
        </div>
    </div>
    <!-- Amount data -->
    <div id="lastSales">
        <h4>Balance Amount</h4>
        <div class="table-responsive">
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
                        <th>Payment Recived</th>

                    </tr>
                </thead>
                        <?php // Handle the toggle status form submission
                
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['toggle_status'])) {
    $toggleStatus = $_POST['toggle_status'] == '1' ? 'Done' : 'Pending';

    if (isset($managerId) && !empty($managerId)) {
        $statusUpdateQuery = "UPDATE payment SET status = ? WHERE manager_id = ?";
        $stmt = $conn->prepare($statusUpdateQuery);
        $stmt->bind_param("si", $toggleStatus, $managerId);

        if ($stmt->execute()) {
            if ($toggleStatus == "Done") {
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
                $balance = $totalSum - $paidAmount;

                echo "<script>console.log('Total Sum: $totalSum, Paid Amount: $paidAmount, Balance: $balance');</script>";
                echo "<script>alert('Payment Approved and Balance Updated');</script>";
            }
        } else {
            echo "<script>alert('Failed to update status');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Manager ID is missing');</script>";
    }
}

        
                        ?>
                <tbody id="salesTableBody">
                    <?php if ($manager): ?>
                    <?php $runningBalance -= $manager['amount']; ?>
                    <tr>

                       <td><?php echo htmlspecialchars($totalSum ?? 0); ?></td>
                        <td><?php echo htmlspecialchars($manager['amount']); ?></td>
                        <td><?php echo htmlspecialchars($manager['payment_mode']); ?></td>
                        <td><?php echo htmlspecialchars($manager['transaction_id']); ?></td>
                        <td><?php echo htmlspecialchars($manager['transaction_date']); ?></td>
                        <td>
                            <?php if (!empty($manager['payment_screenshot'])): ?>
                            <a href="../manager/uploads/<?php echo htmlspecialchars($manager['payment_screenshot']); ?>"
                                download>
                                <img src="../manager/uploads/<?php echo htmlspecialchars($manager['payment_screenshot']); ?>"
                                    alt="Transaction Image" style="width: 80px; height: 50px;">
                            </a>
                            <?php else: ?>
                            No image available
                            <?php endif; ?>
                        </td>

                        <td><?php echo htmlspecialchars( $balance); ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                                <input type="checkbox" name="toggle_status" value="1"
                                    <?= $row['status'] == 'Done' ? 'checked' : '' ?> onchange="this.form.submit()">
                            </form>
                        </td>
                    </tr>
                    <?php else: ?>
                    <tr>
                        <td colspan="6">No data available.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>


    <!-- Last Sales -->
    <div id="lastSales">
        <h4>Last Sales</h4>
        <div class="table-responsive">
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
                    <td><?php echo htmlspecialchars($manager['model']); ?></td>
                    <td><?php echo htmlspecialchars($manager['qty']); ?></td>
                    <td><?php echo htmlspecialchars($manager['buyer_name']); ?></td>
                    <td><?php echo htmlspecialchars($manager['buyer_no']); ?></td>
                    <td><?php echo htmlspecialchars($manager['buyer_add']); ?></td>
                    <td><?php echo htmlspecialchars($manager['price']); ?></td>
                    <td><?php echo htmlspecialchars($manager['sale_date']); ?></td>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Orders Details -->
    <div id="lastOrders">
        <h4>Last Orders</h4>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Category Name</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody id="ordersTableBody">
                    <td><?php echo htmlspecialchars($manager['pro_name']); ?></td>
                    <td><?php echo htmlspecialchars($manager['model']); ?></td>
                    <td><?php echo htmlspecialchars($manager['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($manager['date']); ?></td>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Set the price -->
    <div id="lastSales">
        <h4>Set The Price</h4>
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