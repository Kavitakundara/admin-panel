<?php
include '../auth.php';
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
                <!-- <li><i class="fas fa-home"></i><a href="./admin-home.php">Home</a></li> -->
                <li><i class="fas fa-user-cog"></i> <a href="./manager-create.php">Create Manager</a></li>
                <li><i class="fas fa-eye"></i><a href="./admin-view-mng.php">View Manager</a></li>
                <li><i class="fab fa-salesforce"></i><a href="./admin-mg-sales.php">View Sales</a></li>
                <li><i class="fab fa-first-order-alt"></i><a href="./admin-mg-orders.php">View Orders</a></li>
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
                <?php
    include "../conn.php"; // Replace with your DB connection file

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
            echo "<td>" . htmlspecialchars($row['price']) . "</td>";
            echo "<td>" . htmlspecialchars($row['sale_mode']) . "</td>";
            echo "<td>" . htmlspecialchars($row['buyer_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['buyer_no']) . "</td>";
            echo "<td>" . htmlspecialchars($row['buyer_add']) . "</td>";
            echo "<td><button class='btn btn-warning btn-sm'>Edit</button> 
                      <button class='btn btn-danger btn-sm'>Delete</button></td>";
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

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Update Data From Here
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <div class="mb-2">
                            <label for="edit-quantity" class="form-label">Quantity</label>
                            <input type="text" class="form-control" id="edit-quantity" required>
                        </div>
                        <div class="mb-2">
                            <label for="edit-totalPrice" class="form-label">Total Price</label>
                            <input type="text" class="form-control" id="edit-totalPrice" required>
                        </div>
                        <div class="mb-2">
                            <label for="edit-newPrice" class="form-label">Price (Including 5% GST)</label>
                            <input type="text" class="form-control" id="edit-newPrice" readonly>
                        </div>
                        <div class="mb-2">
                            <label for="edit-buyerName" class="form-label">Buyer Name</label>
                            <input type="text" class="form-control" id="edit-buyerName" required>
                        </div>
                        <div class="mb-2">
                            <label for="edit-buyerPhoneNumber" class="form-label">Buyer Phone Number</label>
                            <input type="text" class="form-control" id="edit-buyerPhoneNumber" required>
                        </div>
                        <div class="mb-2">
                            <label for="edit-buyerAddress" class="form-label">Buyer Address</label>
                            <input type="text" class="form-control" id="edit-buyerAddress" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>

                    <script>
                    document.getElementById("edit-totalPrice").addEventListener("input", function() {
                        const totalPrice = parseFloat(this.value) ||
                            0; // Get the total price or 0 if empty/invalid
                        const gstRate = 5; // GST rate in percentage
                        const gstAmount = (totalPrice * gstRate) / 100; // Calculate GST amount
                        const newPrice = totalPrice + gstAmount; // Calculate new price including GST

                        // Update the new price field
                        document.getElementById("edit-newPrice").value = newPrice.toFixed(
                            2); // Round to 2 decimal places
                    });

                    document.getElementById("editForm").addEventListener("submit", function(e) {
                        e.preventDefault(); // Prevent default form submission

                        // Collect form data
                        const formData = {
                            quantity: document.getElementById("edit-quantity").value,
                            totalPrice: document.getElementById("edit-totalPrice").value,
                            newPrice: document.getElementById("edit-newPrice").value,
                            buyerName: document.getElementById("edit-buyerName").value,
                            buyerPhoneNumber: document.getElementById("edit-buyerPhoneNumber").value,
                            buyerAddress: document.getElementById("edit-buyerAddress").value,
                        };

                        // Send formData to your database using AJAX or Fetch API
                        fetch('/save-data', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify(formData),
                            })
                            .then(response => response.json())
                            .then(data => {
                                alert('Data saved successfully!');
                                console.log(data);
                            })
                            .catch(error => {
                                console.error('Error saving data:', error);
                            });
                    });
                    </script>

                </div>
            </div>
        </div>
    </div>


    <script src="../js/script.js"></script>
    <script src="../js/navcss.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>