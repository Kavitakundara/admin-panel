<?php
session_start();
include  "header.php"; 
include '../conn.php'; // To ensure the user is authenticated

if (isset($_GET['id'])) {
    $adminId = $_GET['id'];
  

    $sql = "
    SELECT 
        a.id AS admin_id,
        a.name AS admin_name,
        a.email AS admin_email,
        a.phone AS admin_phone,
        m.id AS manager_id,
        m.name AS manager_name,
        m.contact AS manager_contact,
        m.username AS manager_username,
        m.address AS manager_address,
        m.created_by AS manager_created_at,
        (SELECT SUM(quantity) FROM place_order WHERE manager_id = m.id) AS total_qty,
        (SELECT SUM(qty) FROM create_sales WHERE manager_id = m.id) AS total_sales,
        (SELECT SUM(grand_total) FROM place_order) AS total_sum,  
        (SELECT SUM(amount) FROM payment) AS paid_amount
        FROM create_admin a
        LEFT JOIN create_manager m ON a.username = m.created_by
        WHERE a.id = ?;
    ";


    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("i", $adminId);
    $stmt->execute();
    if (!$stmt->execute()) {
        echo "Error: " . $stmt->error;
    }
    $result = $stmt->get_result();

    $product_names = [];
    $product_prices = [];
    if ($result->num_rows > 0) {
        $manager = $result->fetch_assoc();
    } else {
        // Handle error if no manager is found with the given ID
        echo "Manager not found!";
        exit();
    }
} else {
    echo "Invalid ID!";
    exit();
}


if (isset($_POST['submit'])) {
    $productNames = $_POST['product_name'];
    $productPrices = $_POST['product_price'];

    if (!empty($productNames) && !empty($productPrices)) {
        $managerId = $_GET['id']; 

        // Prepare and insert each row into the database
        foreach ($productNames as $index => $productName) {
            $productPrice = $productPrices[$index];
            
            $sql = "INSERT INTO dealer_product_prices (manager_id, set_name, set_price) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isi", $managerId, $productName, $productPrice);
            $stmt->execute();
        }

        $stmt->close();
        $conn->close();

        echo "Products added successfully!";
    } else {
        echo "Please fill out all fields.";
    }
}
?>
</head>


<div id="content">
    <!-- Adding the Dealer/Distributor here -->
    <div>
        <p>Admin Name: <?php echo htmlspecialchars($manager['admin_name']); ?></p>
        <p>Contact: <?php echo htmlspecialchars($manager['admin_phone']); ?></p>
        <p>Email Address: <?php echo htmlspecialchars($manager['admin_email']); ?></p>

        </p>
    </div>

    <!--Product Price -->
    <div id="lastSales">
        <h4>Dealer Details</h4>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Dealers Name </th>
                        <th>Contact</th>
                        <th>Location</th>
                        <th>Total Orders</th>
                        <th>Total Sales</th>

                    </tr>
                </thead>
                <tbody id="salesTableBody">
                    <?php if ($manager):
                         $totalQty = $manager['total_qty']; 
                         $totalSale = $manager['total_sales']; ?>

                    <?php 
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($manager['manager_name']); ?></td>
                        <td><?php echo htmlspecialchars($manager['manager_contact']); ?></td>
                        <td><?php echo htmlspecialchars($manager['manager_address']); ?></td>
                        <td><?php echo htmlspecialchars(number_format($totalQty, 2) ) ?></td>
                        <td><?php echo htmlspecialchars(number_format($totalSale, 2) ) ?></td>


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
    <!-- Amount data -->
    <div id="lastSales">
        <h4>Ledger Data</h4>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>

                        <th>Total Amount</th>
                        <th>Paid Amount</th>
                        <th>Balance Amount</th>
                    </tr>
                </thead>
                <tbody id="salesTableBody">
                    <?php if ($manager): ?>
                    <?php 
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($manager['total_sum'] ?? 0); ?></td>
                        <td><?php echo htmlspecialchars($manager['paid_amount'] ?? 0); ?></td>
                        <td><?php echo htmlspecialchars(($manager['total_sum'] ?? 0) - ($manager['paid_amount'] ?? 0)); ?>
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
<script src="../js/script.js"></script>
<script src="../js/navcss.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>