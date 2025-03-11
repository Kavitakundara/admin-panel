<?php
session_start();
include '../conn.php';
$user_id = $_SESSION['user_id']; 


// Query to fetch manager details
$query_manager = "SELECT * FROM create_manager WHERE id = $user_id";

$query_orders = "SELECT * FROM place_order 
                 WHERE manager_id = $user_id 
                 AND date >= CURDATE() - INTERVAL 30 DAY 
                 ORDER BY date DESC";



// Query to fetch related payment
$query_payment = "SELECT * FROM payment WHERE manager_id = $user_id";

// Query to fetch sales details
$query_sales = "SELECT * FROM create_sales WHERE manager_id = $user_id";

// Query to fetch sales details
$query_products = "SELECT * FROM create_products WHERE id = $user_id";

// Execute queries
$result_manager = $conn->query($query_manager);
$result_orders = $conn->query($query_orders);
$result_sales = $conn->query($query_sales);
$result_payment = $conn->query($query_payment);
$result_products = $conn->query($query_products);


?>
<?php include "header.php" ?>

<div id="content">
    <div class="mng-field">
    <!-- Manager Details -->
    <h2>Your Details</h2>
    <?php if ($result_manager->num_rows > 0): ?>
    <?php $manager = $result_manager->fetch_assoc(); ?>
    <ul>
        <li>Name: <?= htmlspecialchars($manager['name']); ?></li>
         <li>AdminName: <?= htmlspecialchars($manager['created_by']); ?></li>
          <li>User Name: <?= htmlspecialchars($manager['username']); ?></li>
           <li>Contact: <?= htmlspecialchars($manager['contact']); ?></li>
            <li>Address: <?= htmlspecialchars($manager['address']); ?></li>
            
    </ul>
     <?php else: ?>
    <p>No details found for this manager.</p>
    <?php endif; ?>
    </div>
    <!-- Orders -->
    <h2>Your Orders</h2>
    <?php if ($result_orders->num_rows > 0): ?>
    <table class="responsive-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Single Product Price</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($order = $result_orders->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($order['pro_name']); ?></td>
                <td><?= htmlspecialchars($order['quantity']); ?></td>
                <td><?= htmlspecialchars($order['pro_price']); ?></td>
                <td><?= htmlspecialchars($order['date']); ?></td>
                <td><?= htmlspecialchars($order['status']); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p>No orders found.</p>
    <?php endif; ?>

    <!-- Sales -->
    <h2>Your Sales</h2>
    <?php if ($result_sales->num_rows > 0): ?>
    <table class="responsive-table">
        <thead>
            <tr>
                <th>Product</th>
                
                <th>Price</th>
                <th>Date</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($sale = $result_sales->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($sale['product_name']); ?></td>
                
                <td><?= htmlspecialchars($sale['price']); ?></td>
                <td><?= htmlspecialchars($sale['sale_date']); ?></td>
                <td><?= htmlspecialchars($sale['qty']); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p>No sales found.</p>
    <?php endif; ?>
</div>


<script src="../js/navcss.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>