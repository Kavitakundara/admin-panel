<?php
include '../auth.php';
?>
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
                <!-- <li><i class="fas fa-home"></i><a href="./admin-home.php">Home</a></li> -->
                <li><i class="fas fa-user-cog"></i> <a href="./manager-create.php">Create Manager</a></li>
                <li><i class="fas fa-eye"></i><a href="./admin-view-mng.php">View Manager</a></li>
                <li><i class="fab fa-salesforce"></i><a href="./admin-mg-sales.php">View Sales</a></li>
                <li><i class="fab fa-first-order-alt"></i><a href="./admin-mg-orders.php">View Orders</a></li>
            </ul>
        </div>
    </div>

    <!-- Main Content Area -->
    <div id="content">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">Product Name</th>
                    <th scope="col">Model Name</th>
                    <th scope="col">Date</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Color</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
               include '../conn.php';
                
                // Fetch data from the place_order table
                $sql = "SELECT id, pro_name, model, date, quantity, color FROM place_order";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Output data for each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['pro_name']}</td>
                            <td>{$row['model']}</td>
                            <td>{$row['date']}</td>
                            <td>{$row['quantity']}</td>
                            <td>{$row['color']}</td>
                            <td>
                                <button class='btn btn-primary btn-sm edit-btn' data-id='{$row['id']}'>Edit</button>
                                <button class='btn btn-danger btn-sm delete-btn' data-id='{$row['id']}'>Delete</button>
                                <a href='javascript:window.print()'>Click to print</a>
                            </td>
                        </tr>";
                    }
                    
                } else {
                echo "<tr>
                    <td colspan='4'>No orders found</td>
                </tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    document.addEventListener('click', function(e) {
        const id = e.target.dataset.id;

        if (e.target.classList.contains('edit-btn')) {
            const row = e.target.closest('tr');
            const proName = row.children[0].textContent;
            const model = row.children[1].textContent;
            const date = row.children[2].textContent;
            const quantity = row.children[3].textContent;
            const color = row.children[4].textContent;

            const updatedProName = prompt("Enter Product Name", proName);
            const updatedModel = prompt("Enter Model", model);
            const updatedDate = prompt("Enter Date", date);
            const updatedQuantity = prompt("Enter Quantity", quantity);
            const updatedColor = prompt("Enter Color", color);

            fetch('../handle_order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'edit',
                        id: id,
                        pro_name: updatedProName,
                        model: updatedModel,
                        date: updatedDate,
                        quantity: updatedQuantity,
                        color: updatedColor,
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.success) location.reload();
                })
                .catch(error => console.error('Error:', error));
        } else if (e.target.classList.contains('delete-btn')) {
            if (confirm("Are you sure you want to delete this record?")) {
                fetch('../handle_order.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            action: 'delete',
                            id: id,
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        if (data.success) location.reload();
                    })
                    .catch(error => console.error('Error:', error));
            }
        }
    });
    </script>
</body>

</html>