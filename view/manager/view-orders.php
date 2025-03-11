<?php
session_start();
include "../conn.php";
$managerId = $_SESSION['user_id']; 

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to delete a record
if (isset($_POST['delete_id'])) {
    $deleteId = intval($_POST['delete_id']);
    $stmt = $conn->prepare("DELETE FROM place_order WHERE id = ?");
    $stmt->bind_param("i", $deleteId);

    echo json_encode(["success" => $stmt->execute(), "message" => $stmt->execute() ? "Record deleted successfully" : "Error deleting record"]);
    $stmt->close();
    exit;
}

// Fetch combined data
$sql = "SELECT 
po.*, 
cm.name AS manager_name, 
cp.color AS product_color, 
cp.motor_no AS motor_number, 
cp.battery_no AS battery_number, 
cp.controller_no AS controller_number, 
cp.date AS product_date, 
cp.charger_no AS charger_no, 
cp.hsn_code AS hsn_code, 
(SELECT SUM(grand_total) FROM place_order WHERE manager_id = po.manager_id) AS total_sum,
GROUP_CONCAT(DISTINCT pcn.chassis_no ORDER BY pcn.chassis_no ASC SEPARATOR ', ') AS chassis_numbers
FROM place_order po
INNER JOIN create_manager cm ON po.manager_id = cm.id
LEFT JOIN create_products cp ON po.id = cp.order_id 
LEFT JOIN product_chassis_numbers pcn ON cp.id = pcn.product_id  
WHERE po.is_placed = 1
AND po.manager_id = ?
GROUP BY po.id, cp.id  
ORDER BY po.id DESC";


            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $managerId);
            $stmt->execute();
            $result = $stmt->get_result();
            ?>

<?php include "header.php" ?>

<div id="content" style="overflow-x: auto;">
    <table class="responsive-table">
        <thead class="thead-dark">
            <tr>
                <th style="width:163px;">Product Name</th>
                <th style="width:100px;">Order Id</th>
                <th style="width:150px;">Single Product Price</th>
                <th style="width:100px;">Total Price</th>
                <th style="width:100px;">Date</th>
                <th style="width:100px;">Quantity</th>
                <th style="width:100px;">Color</th>
                <th style="width:180px;">Chassis Number</th>
                <th style="width:122px;">Motor Number</th>
                <th style="width:122px;">Battery Number </th>
                <th style="width:131px;">Available Colors</th>
                <th style="width:140px;">Charger Number</th>
                <th style="width:140px;">Controller Number</th>
                <th style="width:110px;">Actions</th>
                <th style="width:100px;">Order Recived</th>
                <th style="width:100px;">Order Status</th>

            </tr>
        </thead>
        <?php // Handle the toggle status form submission
                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['toggle_status'])) {
                    $toggleStatus = $_POST['toggle_status'] == '1' ? 'Done' : 'Pending';

                    $statusUpdateQuery = "UPDATE place_order SET recived = ?";
                    $stmt = $conn->prepare($statusUpdateQuery);
                    $stmt->bind_param("s", $toggleStatus);

                    if ($stmt->execute()) {
                        echo "<script>alert('Status updated successfully');</script>";
                    } else {
                        echo "<script>alert('Failed to update status');</script>";
                    }
                    $stmt->close();
                }
                ?>
        <tbody>
            <?php 
    $totalSum = 0; // Default value
    while ($row = $result->fetch_assoc()) : 
        $total_price = $row['pro_price'] * $row['quantity']; 
        $totalSum = $row['total_sum']; // Get total sum
    ?>
            <tr>
                <td><?= htmlspecialchars($row['pro_name']) ?></td>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['pro_price']) ?></td>
                <td><?= htmlspecialchars($total_price) ?></td>
                <td><?= htmlspecialchars($row['date']) ?></td>
                <td><?= htmlspecialchars($row['quantity']) ?></td>
                <td><?= htmlspecialchars($row['color']) ?></td>
                <td  style="max-height: 80px; overflow-y: auto; display: block; width: 180px;"><?= htmlspecialchars($row['chassis_numbers']) ?></td>
                <td><?= htmlspecialchars($row['motor_number']) ?></td>
                <td><?= htmlspecialchars($row['battery_number']) ?></td>
                <td><?= htmlspecialchars($row['product_color']) ?></td>
                <td><?= htmlspecialchars($row['charger_no']) ?></td>
                <td><?= htmlspecialchars($row['controller_number']) ?></td>
                

                <td class="d-flex justify-content-between">
                    <button class='btn btn-danger btn-sm delete-btn' data-id='<?= $row['id'] ?>'><i
                            class="fas fa-trash"></i></button>
                    <form method='post'>
                        <input type='hidden' name='edit_id' value='<?= $row['id'] ?>'>
                        <button type='submit' class='btn btn-primary btn-sm' name='edit_order'><i
                                class="fas fa-edit"></i></button>
                    </form>
                </td>
                <td>
                    <form method="post">
                        <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                        <input type="checkbox" name="toggle_status" value="1"
                            <?= $row['recived'] == 'Done' ? 'checked' : '' ?> onchange="this.form.submit()">
                    </form>
                </td>
                <td><?= htmlspecialchars($row['status']) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>

        <!-- Subtotal Row -->
        <tfoot>
            <tr>
                <td colspan="1"><strong>Subtotal:</strong></td>
                <td colspan="2">
                    <?php echo "<h5>₹" . number_format($totalSum, 2) . "</h5>"; ?></a>
                </td>

                <td colspan="10"></td>
            </tr>
        </tfoot>

    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const deleteId = this.getAttribute('data-id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: `delete_id=${deleteId}`
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.fire(data.success ? 'Deleted!' : 'Error!', data
                                    .message, data.success ? 'success' : 'error'
                                )
                                .then(() => location.reload());
                        });
                }
            });
        });
    });
});
</script>

<script src="../js/navcss.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>