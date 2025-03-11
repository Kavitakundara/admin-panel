<?php
include '../auth.php';
include "../conn.php"; 
include  "header.php"; 

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
<!-- Main Content Area -->
<div id="content" style="overflow-x: auto;">
    <div class="container my-3">
        <input type="text" id="searchBox" class="form-control" placeholder="Search for dealers...">
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col" style="width:80px;">S NO.</th>
                <th scope="col" style="width:80px;">Order Id</th>
                <th scope="col" style="width:80px;">Dealer Id</th>
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
    $productPrice = isset($_POST['product_price']) ? floatval($_POST['product_price']) : 0;
    // Update the status if changed
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['toggle_status'])) {
        $orderId = intval($_POST['order_id']);
        $toggleStatus = $_POST['toggle_status'] == '1' ? 'Done' : 'Pending';
    
        $statusUpdateQuery = "UPDATE place_order SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($statusUpdateQuery);
        $stmt->bind_param("si", $toggleStatus, $orderId);
        if ($stmt->execute()) {
            echo "<script>alert('Status updated successfully');</script>";
        } else {
            echo "<script>alert('Failed to update status');</script>";
        }
        $stmt->close();
    }
    

    // Update the product price if it's changed
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_price']) && isset($_POST['order_id'])) {
        $orderId = intval($_POST['order_id']);
        $productPrice = floatval($_POST['product_price']);
    
        $priceUpdateQuery = "UPDATE place_order SET pro_price = ? WHERE id = ?";
        $stmt = $conn->prepare($priceUpdateQuery);
        $stmt->bind_param("di", $productPrice, $orderId);
        if ($stmt->execute()) {
            echo "<script>alert('Product price updated successfully');</script>";
        } else {
            echo "<script>alert('Failed to update price');</script>";
        }
        $stmt->close();
    }
    
}

?>

        <!-- Main Content -->
        <tbody id="orderData">
            <?php
   $sql = "SELECT place_order.*, create_manager.name AS manager_name
   FROM place_order 
   INNER JOIN create_manager 
   ON place_order.manager_id = create_manager.id 
   WHERE place_order.is_placed = 1 -- Only show records with status = true
   ORDER BY place_order.id DESC";
                
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
<!-- Modal for editing admin -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Admin Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST" action="">
                    <input type="hidden" id="edit-id" name="order_id">
                    <input type="hidden" name="action" value="edit">
                    <div class="mb-2">
                        <label for="edit-Name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="edit-Name" name="pro_name">
                    </div>
                    <div class="mb-2">
                        <label for="edit-contact" class="form-label">Product Price</label>
                        <input type="number" class="form-control" id="edit-contact" name="product_price">
                    </div>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>

            </div>
        </div>
    </div>
</div>
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
function populateEditForm(id, name, price) {
    document.getElementById('edit-id').value = id;
    document.getElementById('edit-Name').value = name;
    document.getElementById('edit-contact').value = price;
}
</script>
<script src="../js/navcss.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>