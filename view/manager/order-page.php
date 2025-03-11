<?php
// include '../auth.php';
session_start();
include "../conn.php";
$managerId = $_SESSION['user_id']; 
$dealerName = $_SESSION['name']; 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);

    // Update the order's status to "placed"
    $sql = "UPDATE place_order SET is_placed = 1 WHERE id = $order_id";

    if ($conn->query($sql) === TRUE) {
         $message = "New order received";
        addOrderNotification($conn, $managerId, $message, $dealerName);
        echo "<script>alert('Order placed successfully!');</script>"
         ;
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

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
 $message = "New order received";
function addOrderNotification($conn, $managerId, $message, $dealerName) {
    $stmt = $conn->prepare("INSERT INTO notifications (manager_id, message, dealername) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $managerId, $message, $dealerName);
    $stmt->execute();
    $stmt->close();
}
?>
<?php include "header.php" ?>

<!-- Main Content Area -->
<div id="content">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Product Name</th>
                <th scope="col">Product Price</th>
                <th scope="col">Date</th>
                <th scope="col">Quantity</th>
                <th scope="col">Color</th>
                <th scope="col">Total Price</th>
                <th scope="col">Actions</th>
                <th scope="col">Add Product</th>
            </tr>
        </thead>
        <tbody id="orderData">
            <?php
              $sql = "SELECT place_order.*, create_manager.name AS manager_name
              FROM place_order 
              INNER JOIN create_manager 
              ON place_order.manager_id = create_manager.id 
              WHERE place_order.manager_id = $managerId
              ORDER BY place_order.id DESC";
      

                $stmt = $conn->prepare($sql); // Prepare the SQL statement
                $stmt->execute(); // Execute the statement
                $result = $stmt->get_result(); // Get the result
                    if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $total_price = ($row['pro_price'] * $row['quantity']);
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['pro_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['pro_price']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['color']) . "</td>";
                        echo "<td>" . htmlspecialchars( $total_price) . "</td>";
                        
                        echo "<td style='display:flex'>
                                <button class='btn btn-danger btn-sm delete-btn' data-id='" . $row['id'] . "'>Delete</button>
                                <form method='post' action='view-orders.php'>
                                    <input type='hidden' name='edit_id' value='" . $row['id'] . "'>
                                    <button type='submit' class='btn btn-primary btn-sm edit-btn' name='edit_order'>Edit</button>
                                </form>
                            </td>";
                            
                        echo "<td>";
                        if ($row['is_placed'] == 0) { // If order is NOT placed
                            echo "<form method='post' action='order-page.php'>
                                    <input type='hidden' name='order_id' value='" . $row['id'] . "'>
                                    <button type='submit' class='btn btn-success btn-sm place-order-btn'>Place Order</button>
                                  </form>";
                        } else {
                            echo "<span class='text-success'><b>ORDER PLACED</b></span>"; // Show message if order is placed
                        }
                        echo "</td>";

                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='17'>No records found.</td></tr>";
                }

                ?>
        </tbody>
    </table>
</div>
<?php
        if (isset($_POST['edit_order'])) {
            $edit_id = $_POST['edit_id'];

            // Fetch the record to be edited
            $sql = "SELECT * FROM place_order WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $edit_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $order = $result->fetch_assoc();

            // Display the edit form
            if ($order) {
                echo "<form method='post' action='' class='mbg-form'>
                        <h2>Edit Order</h2>
                        <div class='mng-row'>
                        <input type='hidden' name='id' value='" . htmlspecialchars($order['id']) . "'>
                        <label>Product Name:</label>
                        <input type='text' name='pro_name' value='" . htmlspecialchars($order['pro_name']) . "' required><br>
                        <label>Model:</label>
                        <input type='text' name='model' value='" . htmlspecialchars($order['model']) . "' required><br>
                        <label>Date:</label>
                        <input type='date' name='date' value='" . htmlspecialchars($order['date']) . "' required><br>
                        <label>Quantity:</label>
                        <input type='number' name='quantity' value='" . htmlspecialchars($order['quantity']) . "' required><br>
                        <label>Color:</label>
                        <input type='text' name='color' value='" . htmlspecialchars($order['color']) . "' required><br>
                    
                        <button type='submit' class='update-mnger' name='update_order'>Update Order</button>
                        </div.
                    </form>";
            }
        }
        ?>
<?php
        if (isset($_POST['update_order'])) {
            $id = $_POST['id'];
            $pro_name = $_POST['pro_name'];
            $model = $_POST['model'];
            $date = $_POST['date'];
            $quantity = $_POST['quantity'];
            $color = $_POST['color'];
        

            // Update query
            $sql = "UPDATE place_order SET pro_name=?, model=?, date=?, quantity=?, color=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssi", $pro_name, $model, $date, $quantity, $color, $id);

            if ($stmt->execute()) {
                echo "Order updated successfully!";
            } else {
                echo "Error updating order: " . $conn->error;
            }
        }
        ?>

<script>
function placeOrder() {
    // Get product details from the page
    const productName = document.getElementById("product-name").innerText;
    const productPrice = document.getElementById("product-price").innerText;
    const quantity = document.getElementById("quantity").value;

    // Calculate total price
    const totalPrice = parseFloat(productPrice) * parseInt(quantity);

    // Create an order object
    const order = {
        productName: productName,
        productPrice: productPrice,
        quantity: quantity,
        totalPrice: totalPrice,
    };

    // Retrieve existing orders from localStorage
    const orders = JSON.parse(localStorage.getItem("orders")) || [];

    // Add the new order
    orders.push(order);

    // Save back to localStorage
    localStorage.setItem("orders", JSON.stringify(orders));

    // Redirect to "View Orders" page
    window.location.href = "view_orders.html";
}
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

<script src="../js/navcss.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>