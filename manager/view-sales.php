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
                <!-- <li><i class="fas fa-home"></i><a href="./manager-home.php">Home</a></li> -->
                <li><i class="fas fa-eye"></i> <a href="./order-page.php">Place Order</a></li>
                <li><i class="fas fa-eye"></i> <a href="./view-orders.php">View Orders</a></li>
                <li><i class="fab fa-salesforce"></i><a href="./sales-page.php">Create Sales</a></li>
                <li><i class="fab fa-salesforce"></i><a href="./view-sales.php">View Sales</a></li>
            </ul>
        </div>
    </div>
    </div>

    <div id="content">
        <table class="responsive-table">
            <!-- Responsive Table Header Section -->
            <thead>
                <tr>
                    <!-- <th scope="col">Dealer/Distributor</th> -->
                    <th scope="col">Product Name</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Total Price</th>
                    <th scope="col">Buyer Name</th>
                    <th scope="col">Buyer Phone Number</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <!-- Responsive Table Body Section -->
            <tbody class="responsive-table__body" id="orderData">
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