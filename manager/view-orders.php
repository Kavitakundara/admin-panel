<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" />
    <script src="../js/istocken.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>View Orders</title>
</head>

<body>
    <div id="main-container">
        <!-- Top Navigation Bar -->
        <div id="topNav">
            <div id="hamburger">&#9776;</div>
            <div>
                <h1 id="welcomeName">Welcome User</h1>
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

        <!-- Side Navigation Bar -->
        <div id="sideNav">
            <button class="closeBtn">&times;</button>
            <ul>
                <li><i class="fas fa-eye"></i> <a href="./order-page.php">Place Order</a></li>
                <li><i class="fas fa-eye"></i> <a href="./view-orders.php">View Orders</a></li>
                <li><i class="fab fa-salesforce"></i><a href="./sales-page.php">Create Sales</a></li>
                <li><i class="fab fa-salesforce"></i><a href="./view-sales.php">View Sales</a></li>
            </ul>
        </div>
    </div>

    <!-- Main Content Area -->
    <div id="content">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">Product Name</th>
                    <th scope="col">Date</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody id="orderData"></tbody>
        </table>
    </div>

    <!-- Modal for Editing Order -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <div class="mb-2">
                            <label for="edit-product" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="edit-product" disabled>
                        </div>
                        <div class="mb-2">
                            <label for="edit-quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="edit-quantity" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Dummy Order Data
    const orders = [{
            productName: "Laptop",
            date: "2024-11-10",
            quantity: 2
        },
        {
            productName: "Smartphone",
            date: "2024-11-11",
            quantity: 5
        },
        {
            productName: "Headphones",
            date: "2024-11-12",
            quantity: 10
        },
    ];

    // Populate the table with order data
    const orderDataContainer = document.getElementById("orderData");

    orders.forEach((order, index) => {
        const row = document.createElement("tr");
        row.innerHTML = `
                <td>${order.productName}</td>
                <td>${order.date}</td>
                <td>${order.quantity}</td>
                <td>
                    <button class="btn btn-primary btn-sm" onclick="editOrder(${index})">Edit</button>
                    <button class="btn btn-danger btn-sm" onclick="deleteOrder(${index})">Delete</button>
                    <a href="javascript:window.print()">Click to print</a>
                </td>
            `;
        orderDataContainer.appendChild(row);
    });

    // Edit Order Function
    function editOrder(index) {
        const order = orders[index];
        document.getElementById("edit-product").value = order.productName;
        document.getElementById("edit-quantity").value = order.quantity;
        const modal = new bootstrap.Modal(document.getElementById("exampleModal"));
        modal.show();
    }

    // Delete Order Function
    function deleteOrder(index) {
        orders.splice(index, 1); // Remove the order from the array
        location.reload(); // Refresh the page to update the table
    }
    </script>
</body>

</html>