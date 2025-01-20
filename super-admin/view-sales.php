<?php
include '../auth.php';
include "../conn.php"; // Replace with your DB connection file

// Function to delete a record
if (isset($_POST['delete_id'])) {
    $deleteId = intval($_POST['delete_id']);
    $deleteQuery = "DELETE FROM create_sales WHERE id = ?";
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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" />
    <script src="../js/istocken.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert -->
    <title>View Orders</title>
</head>

<body>
    <div id="main-container">
        <!-- Top Navigation Bar -->
        <div id="topNav">
            <div id="hamburger">&#9776;</div>
            <div>
                <h1 id="welcomeName"></h1>
            </div>
            <div id="imageClick">
                <img id="profileImg" class="dropbtn" src="http://thememinister.com/crm/assets/dist/img/avatar5.png"
                    alt="Profile Image" />
                <div id="dropdownContent" class="dropdown-content">
                    <a href="#"><i class="fas fa-user"></i> &nbsp; My Profile</a>
                    <a href="#" onclick="logout()"><i class="fas fa-sign-out-alt"></i> &nbsp; Signout</a>
                </div>
            </div>
        </div>
        <div id="hiddenFeatures">
            <div>Your Account</div>
            <div id="logOut">Logout</div>
        </div>
        <!-- Side Navigation Bar -->
        <div id="sideNav">
            <button class="closeBtn">&times;</button>
            <ul>
                <li><i class="fas fa-home"></i><a href="./home.php">Home</a></li>
                <li><i class="fas fa-user"></i> <a href="./admin-create.php">Create Admin</a></li>
                <li><i class="fas fa-user"></i> <a href="./view-admin.php">View Admin</a></li>
                <li><i class="fas fa-user-cog"></i> <a href="./dealer-create.php">Create Dealer</a></li>
                <li><i class="fas fa-eye"></i> <a href="./dealer-view.php">View Dealer</a></li>
                <li><i class="fab fa-salesforce"></i><a href="./view-sales.php">View Sales</a></li>
                <li><i class="fab fa-first-order-alt"></i><a href="./view-orders.php">View Orders</a></li>
                <li><i class="fas fa-eye"></i><a href="./product-create.php">Create Product</a></li>
                <li><i class="fas fa-upload"></i><a href="./product-view.php">View Product</a></li>

            </ul>
        </div>
    </div>
    </div>

    <div id="content">
        <table class="responsive-table">
            <!-- Responsive Table Header Section -->
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Product Name</th>
                    <th scope="col">Model</th>
                    <th scope="col">Colour</th>
                    <th scope="col">Motor No.</th>
                    <th scope="col">Chassis No.</th>
                    <th scope="col">Controller No.</th>
                    <th scope="col">Battery No.</th>
                    <th scope="col">Charger</th>
                    <th scope="col">RTO Charge</th>
                    <th scope="col">Other Charge</th>
                    <th scope="col">Total Price</th>
                    <th scope="col">Sale Mode</th>
                    <th scope="col">Buyer Name</th>
                    <th scope="col">Buyer Phone Number</th>
                    <th scope="col">Buyer Address</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <!-- Responsive Table Body Section -->
            <tbody id="orderData">
            <tbody id="orderData">
                <?php
                $sql = "SELECT * FROM create_sales ORDER BY id DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['model']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['color']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['motor_no']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['chassis_no']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['controller_no']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['battery_no']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['charger']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['rto']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['oth_chrg']) . "</td>";
                        echo "<td>" . htmlspecialchars(number_format($row['price'] / 1.05, 2)) . "</td>";
                        echo "<td>" . htmlspecialchars($row['sale_mode']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['buyer_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['buyer_no']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['buyer_add']) . "</td>";
                        echo "<td>
                                <button class='btn btn-danger btn-sm delete-btn' data-id='" . $row['id'] . "'>Delete</button>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='17'>No records found.</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>

        </table>
    </div>
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

    <script src="../js/script.js"></script>
    <script src="../js/navcss.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>