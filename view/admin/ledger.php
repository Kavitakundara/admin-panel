<?php
session_start();
include '../conn.php'; // Ensure database connection is established

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    echo "Access denied! Please log in.";
    exit();
}

$username = $_SESSION['username']; // Store session username

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
        (SELECT SUM(grand_total) FROM place_order) AS total_sum,  
        (SELECT SUM(amount) FROM payment) AS paid_amount,  
        (SELECT SUM(qty) FROM create_sales WHERE manager_id = m.id) AS total_sales
    FROM create_admin a
    LEFT JOIN create_manager m ON a.username = m.created_by
    WHERE a.username = ?;
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $manager = $result->fetch_assoc();
} else {
    echo "No data found!";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $productNames = $_POST['product_name'];
    $productPrices = $_POST['product_price'];

    if (!empty($productNames) && !empty($productPrices)) {
        $managerId = $manager['manager_id']; // Fetch manager_id from the query result

        // Insert each product price into the database
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
<?php include 'header.php'; ?>

<div id="content">
    <div>
        <p>Admin Name: <?php echo htmlspecialchars($manager['admin_name']); ?></p>
        <p>Contact: <?php echo htmlspecialchars($manager['admin_phone']); ?></p>
        <p>Email Address: <?php echo htmlspecialchars($manager['admin_email']); ?></p>
    </div>

    <div id="lastSales">
        <h4>Dealer Details</h4>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Dealer Name</th>
                        <th>Contact</th>
                        <th>Location</th>
                        <th>Total Orders</th>
                        <th>Total Sales</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($manager): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($manager['manager_name']); ?></td>
                        <td><?php echo htmlspecialchars($manager['manager_contact']); ?></td>
                        <td><?php echo htmlspecialchars($manager['manager_address']); ?></td>
                        <td><?php echo htmlspecialchars(number_format($manager['total_qty'], 2)); ?></td>
                        <td><?php echo htmlspecialchars(number_format($manager['total_sales'], 2)); ?></td>
                    </tr>
                    <?php else: ?>
                    <tr>
                        <td colspan="5">No data available.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div id="lastSales">
        <h4>Ledger Details</h4>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Total Amount</th>
                        <th>Paid Amount</th>
                        <th>Balance Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($manager): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($manager['total_sum']); ?></td>
                        <td><?php echo htmlspecialchars($manager['paid_amount']); ?></td>
                        <td>
                            <?php 
                // Calculate balance amount
                $balance = $manager['total_sum'] - $manager['paid_amount']; 
                echo htmlspecialchars($balance);
            ?>
                        </td>
                    </tr>
                    <?php else: ?>
                    <tr>
                        <td colspan="5">No data available.</td>
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
    newRow.innerHTML = <
        input type = "text"
    name = "product_name[]"
    placeholder = "Product Name"
    required >
        <
        input type = "number"
    name = "product_price[]"
    placeholder = "Price"
    required >
        <
        button type = "button"
    onclick = "removeRow(this)" > Remove < /button>;
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