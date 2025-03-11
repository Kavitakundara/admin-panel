<?php
session_start();
include "../conn.php"; 
$user_name = $_SESSION['username'];

// Function to delete a record
if (isset($_POST['delete_id'])) {
    $deleteId = intval($_POST['delete_id']);
    $deleteQuery = "DELETE FROM place_order WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $deleteId);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Record deleted successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error deleting record"]);
    }
    $stmt->close();
    $conn->close();
    exit;
}
?>
<?php include 'header.php'; ?>

<div id="content" style="overflow-x: auto;">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col" style="width:80px;">S NO.</th>
                <th scope="col" style="width:80px;">Order Id</th>
                <th scope="col" style="width:80px;">Manager Id</th>
                <th scope="col" style="width:150px;">Dealer Name</th>
                <th scope="col" style="width:130px;">Product Price</th>
                <th scope="col" style="width:150px;">Product Name</th>
                <th scope="col" style=" width:150px;">Date</th>
                <th scope="col" style="width:130px;">Quantity</th>
                <th scope="col" style=" width:150px;">Color</th>
                <th scope="col" style=" width:150px;">Actions</th>
                <th scope="col" style="width:130px;">Order Status</th>
                <th scope="col" style="width:130px;">Order Recived</th>

            </tr>
        </thead>
        <?php
// Handle the toggle status form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id'])) {
    $orderId = intval($_POST['order_id']);
    $toggleStatus = isset($_POST['toggle_status']) && $_POST['toggle_status'] == '1' ? 'Done' : 'Pending';

    $updateQuery = "UPDATE place_order SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("si", $toggleStatus, $orderId);

    if ($stmt->execute()) {
        echo "<script>alert('Status updated successfully');</script>";
    } else {
        echo "<script>alert('Failed to update status');</script>";
    }

    $stmt->close();
}
?>

        <!-- Main Content -->
        <tbody id="orderData">
            <?php
  $sql = "SELECT place_order.*, create_manager.name AS manager_name
        FROM place_order
        INNER JOIN create_manager ON place_order.manager_id = create_manager.id
        WHERE place_order.is_placed = 1 
        AND create_manager.created_by = '$admin_username'
        ORDER BY place_order.id DESC";


                // Fetch managers from the database

        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $serialNumber = 1; // Initialize serial number
            while ($row = $result->fetch_assoc()) {
        $total_price = ($row['pro_price'] * $row['quantity']);

                echo "<tr>";
                echo "<td>" . $serialNumber . "</td>"; // Serial number column
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['manager_id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['manager_name']) . "</td>";
                echo "<td>" . htmlspecialchars($total_price) . "</td>";
                echo "<td>" . htmlspecialchars($row['pro_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                echo "<td>" . htmlspecialchars($row['color']) . "</td>";
                
                echo "<td>
                        <button class='btn btn-danger btn-sm delete-btn' data-id='" . $row['id'] . "'><i
                                class='fas fa-trash'></i></button>
                       <button class='btn btn-success' data-bs-toggle='modal' data-bs-target='#exampleModal' 
        onclick='populateEditForm(" . $row['id'] . ", \"" . $row['pro_name'] . "\", \"" . $row['pro_price'] . "\")'>
        <i
                                    class='fas fa-edit'></i>
</button>

                      </td>";
                      
                // Toggle button for status
                echo "<td>
                <form method='post'>
                    <input type='hidden' name='order_id' value='" . $row['id'] . "'>
                    <input type='checkbox' name='toggle_status' value='1' " . ($row['status'] == 'Done' ? 'checked' : '') . " onchange='this.form.submit()'>
                </form>
            </td>";
            echo "<td>" . htmlspecialchars($row['recived']) . "</td>";
            
            echo "</tr>";

            $serialNumber++; // Increment serial number
            }
            }
    ?>
        </tbody>


    </table>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
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
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `delete_id=${deleteId}`,
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire(
                                    'Deleted!',
                                    data.message,
                                    'success'
                                ).then(() => {
                                    location
                                        .reload(); // Reload the page
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    data.message,
                                    'error'
                                );
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error!', 'Failed to delete the record.',
                                'error');
                        });
                }
            });
        });
    });
});
</script>
<script>
document.getElementById("searchBox").addEventListener("input", function() {
    const searchValue = this.value.toLowerCase();
    const tableRows = document.querySelectorAll("tbody tr");

    tableRows.forEach(row => {
        const rowText = row.textContent.toLowerCase();
        if (rowText.includes(searchValue)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
});
</script>
<script src="../js/navcss.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>