<?php
session_start();
include "../conn.php";
include "header.php";

// Ensure session is set
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access. Please log in.");
}

$subtotal = $_GET['subtotal'] ?? 0;
$manager_id = $_SESSION['user_id'];

// Optimized SQL query
$sql = "SELECT 
            pay.transaction_id,
            pay.amount,
            pay.transaction_date,
            pay.payment_screenshot,
            pay.status,
            COALESCE(total_orders.total_sum, 0) AS total_sum,
            COALESCE(payments.total_paid, 0) AS total_paid,
            (COALESCE(total_orders.total_sum, 0) - COALESCE(payments.total_paid, 0)) AS balance
        FROM payment pay
        LEFT JOIN 
            (SELECT manager_id, SUM(grand_total) AS total_sum FROM place_order WHERE manager_id = ?) AS total_orders 
            ON pay.manager_id = total_orders.manager_id
        LEFT JOIN 
            (SELECT manager_id, SUM(amount) AS total_paid FROM payment WHERE manager_id = ? AND status = 'Done') AS payments 
            ON pay.manager_id = payments.manager_id
        WHERE pay.manager_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $manager_id, $manager_id, $manager_id);
$stmt->execute();
$result = $stmt->get_result();

// Initialize variables
$totalSum = 0;
$paidAmount = 0;
$balance = 0;
$transactions = [];

// Fetch result
while ($row = $result->fetch_assoc()) {
    $totalSum = $row['total_sum'];    // Total Grand Total
    $paidAmount = $row['total_paid']; // Total Paid Amount
    $balance = $row['balance'];       // Remaining Balance
    $transactions[] = $row;           // Store transactions for table display
}

// Initialize running balance
$runningBalance = $totalSum;
?>

<!-- Main Content Area -->
<div id="content">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Payment Date</th>
                <th scope="col">Total Price</th>
                <th scope="col">Paid Amount</th>
                <th scope="col">Balance Amount</th>
                <th scope="col">Payment Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
<tbody>
    <?php
    foreach ($transactions as $index => $row) {
        $paymentAmount = $row['amount'] ?? 0;
        $status = $row['status'];

        // Check if payment amount is greater than balance
        if ($paymentAmount > $runningBalance) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Payment Error',
                    text: 'Payment can\'t be done due to insufficient balance!',
                });
            </script>";
        }

        $runningBalance -= ($status === "Done") ? $paymentAmount : 0;

        echo "<tr id='row_$index'>";
        echo "<td>" . htmlspecialchars($row['transaction_date'] ?? "N/A", ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td>" . number_format($totalSum, 2) . "</td>";
        echo "<td>" . number_format($paymentAmount, 2) . "</td>";

        if ($status === "Done") {
            echo "<td>" . number_format($runningBalance, 2) . "</td>";
        } else {
            echo "<td>Pending</td>";
        }

        echo "<td>" . htmlspecialchars($status) . "</td>";
        echo "<td><button class='btn btn-danger btn-sm' onclick='deleteRow($index)'>Delete</button></td>"; 
        echo "</tr>";
    }
    ?>
</tbody>


    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function deleteRow(index) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById("row_" + index).remove(); // Remove row from UI
            Swal.fire("Deleted!", "The record has been removed from the screen.", "success");
        }
    });
}
</script>

<script src="../js/navcss.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
