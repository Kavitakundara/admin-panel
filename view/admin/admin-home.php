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
        (SELECT SUM(qty) FROM create_sales WHERE manager_id = m.id) AS total_sales
    FROM create_admin a
    LEFT JOIN create_manager m ON a.username = m.created_by
    WHERE a.username = ?;
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$managers = []; // Store multiple dealers
while ($row = $result->fetch_assoc()) {
    $managers[] = $row;
}

// Fetch ledger details separately
$sql_ledger = "
    SELECT 
        (SELECT SUM(grand_total) FROM place_order) AS total_sum,  
        (SELECT SUM(amount) FROM payment) AS paid_amount
";
$ledger_result = $conn->query($sql_ledger);
$ledger = $ledger_result->fetch_assoc();

$stmt->close();
$conn->close();

?>

<?php include 'header.php'; ?>

<div id="content">
    <div class="mng-div">
        <div class="">
            <p><span>Admin Name:</span> <?php echo htmlspecialchars($managers[0]['admin_name'] ?? ''); ?></p>
            <p><span>Contact:</span> <?php echo htmlspecialchars($managers[0]['admin_phone'] ?? ''); ?></p>
            <p><span>Email Address:</span> <?php echo htmlspecialchars($managers[0]['admin_email'] ?? ''); ?></p>
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
                        <?php if (!empty($managers)): ?>
                        <?php foreach ($managers as $manager): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($manager['manager_name']); ?></td>
                            <td><?php echo htmlspecialchars($manager['manager_contact']); ?></td>
                            <td><?php echo htmlspecialchars($manager['manager_address']); ?></td>
                            <td><?php echo htmlspecialchars(number_format($manager['total_qty'], 2)); ?></td>
                            <td><?php echo htmlspecialchars(number_format($manager['total_sales'], 2)); ?></td>
                        </tr>
                        <?php endforeach; ?>
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
                        <tr>
                            <td><?php echo htmlspecialchars($ledger['total_sum'] ?? 0); ?></td>
                            <td><?php echo htmlspecialchars($ledger['paid_amount'] ?? 0); ?></td>
                            <td><?php echo htmlspecialchars(($ledger['total_sum'] ?? 0) - ($ledger['paid_amount'] ?? 0)); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
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