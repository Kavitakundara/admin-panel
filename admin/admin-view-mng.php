<?php
include '../auth.php';
session_start();
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "super_admin";


$loggedInAdminId = $_SESSION['user_id'];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}

// Fetch manager data filtered by the logged-in admin
$sql = "SELECT * FROM create_manager WHERE created_by = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $loggedInAdminId); // Bind the admin's ID
$stmt->execute();
$result = $stmt->get_result();

$managers = [];

if ($result) {
if ($result->num_rows > 0) {
$managers = $result->fetch_all(MYSQLI_ASSOC); // Fetch all rows as an associative array
}
}
$stmt->close();
$conn->close();
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <title>View Manager</title>
    <style>
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: 0.4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: 0.4s;
    }

    input:checked+.slider {
        background-color: #2196f3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196f3;
    }

    input:checked+.slider:before {
        transform: translateX(26px);
    }

    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert -->
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
        <div id="sideNav" style="z-index: 999;">
            <button class="closeBtn">&times;</button>
            <img src="../images/heading-logo.png" alt="">
            <ul>
                <!-- <li><i class="fas fa-home"></i><a href="./admin-home.php">Home</a></li> -->
                <li><i class="fas fa-user-cog"></i> <a href="./manager-create.php">Create Manager</a></li>
                <li><i class="fas fa-eye"></i><a href="./admin-view-mng.php">View Manager</a></li>
                <li><i class="fab fa-salesforce"></i><a href="./admin-mg-sales.php">View Sales</a></li>
                <li><i class="fab fa-first-order-alt"></i><a href="./admin-mg-orders.php">View Orders</a></li>
            </ul>
        </div>
        <!-- MAIN CONTENT OF THE HOME PAGE -->
        <!-- <div id="content">
            <div class="content-box blu">
                <img src="img/video-posted.png" alt="">
                <h1>Total Video Posted</h1>
                <p id="videoPostedCount"></p>
            </div> -->
    </div>
    <div id="content">
        <table>
            <!-- Responsive Table Header Section -->

            <thead>
                <tr>
                    <!-- <th scope="col">Id</th> -->
                    <th scope="col">Name</th>
                    <th scope="col">Username</th>
                    <th scope="col">Contact</th>
                    <th scope="col">Role</th>
                    <th scope="col">Address</th>
                    <th colspan="2" scope="col">Actions</th>
                </tr>
            </thead>
            <!-- Responsive Table Body Section -->
            <tbody class="responsive-table__body" id="managerBody">
                <?php foreach ($managers as $manager): ?>
                <tr>
                    <td><?php echo htmlspecialchars($manager['name']); ?></td>
                    <td><?php echo htmlspecialchars($manager['username']); ?></td>
                    <td><?php echo htmlspecialchars($manager['contact']); ?></td>
                    <td><?php echo htmlspecialchars($manager['address']); ?></td>
                    <td><?php echo htmlspecialchars($manager['role']); ?></td>

                    <td>
                        <button class="btn btn-info btn-sm"
                            onclick="editManager(<?php echo $manager['id']; ?>)">Edit</button>
                        <button class="btn btn-danger btn-sm"
                            onclick="deleteManager(<?php echo $manager['id']; ?>)">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Manager Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <div class="mb-2">
                            <label for="edit-Name" class="form-label"> Name</label>
                            <input type="text" class="form-control" id="edit-Name">
                        </div>

                        <div class="mb-2">
                            <label for="edit-Email" class="form-label"> Email</label>
                            <input type="text" class="form-control" id="edit-Email">
                        </div>

                        <div class="mb-2">
                            <label for="edit-address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="edit-address">
                        </div>

                        <div class="mb-2">
                            <label for="edit-phonenumber" class="form-label">Manager Phone Number</label>
                            <input type="text" class="form-control" id="edit-phonenumber">
                        </div>


                        <!-- <div class="mb-2">
                            <label for="edit-Role" class="form-label">Role</label>
                            <input type="number" class="form-control" id="edit-Role" required>
                        </div> -->
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="../js/navcss.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>