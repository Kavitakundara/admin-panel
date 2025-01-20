<?php
include '../auth.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "super_admin";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process Edit Form Submission
if (isset($_POST['update_manager'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $username = $_POST['username'];
    $contact = $_POST['contact'];
    $role = $_POST['role'];
    $address = $_POST['address'];
    $gst = $_POST['gst'];

    $sql = "UPDATE create_manager SET name=?, username=?, contact=?, role=?, address=?, gst=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $name, $username, $contact, $role, $address, $gst, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Manager updated successfully'); window.location.href='dealer-view.php';</script>";
    } else {
        echo "<script>alert('Error updating manager');</script>";
    }
}

// Process Delete Request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM create_manager WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        echo "<script>alert('Manager deleted successfully'); window.location.href='dealer-view.php';</script>";
    } else {
        echo "<script>alert('Error deleting manager');</script>";
    }
}

// Fetch managers from the database
$sql = "SELECT * FROM create_manager";
$result = $conn->query($sql);
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
                    <th scope="col">Gst</th>
                    <th scope="col">Document</th>
                    <th colspan="2" scope="col">Actions</th>
                </tr>
            </thead>
            <!-- Responsive Table Body Section -->
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                <?php while ($manager = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($manager['name']); ?></td>
                    <td><?php echo htmlspecialchars($manager['username']); ?></td>
                    <td><?php echo htmlspecialchars($manager['contact']); ?></td>
                    <td><?php echo htmlspecialchars($manager['role']); ?></td>
                    <td><?php echo htmlspecialchars($manager['address']); ?></td>
                    <td><?php echo htmlspecialchars($manager['gst']); ?></td>
                    <td>
                        <?php if (!empty($manager['document'])): ?>
                        <img src="doc_upload/<?php echo htmlspecialchars($manager['document']); ?>" alt="Document"
                            style="max-width: 100px;">
                        <?php else: ?>
                        No document
                        <?php endif; ?>
                    </td>
                    <td>
                        <!-- Edit Button -->
                        <form method="post" action="dealer-view.php">
                            <input type="hidden" name="id" value="<?php echo $manager['id']; ?>">
                            <input type="submit" name="edit_manager" value="Edit" />
                        </form>

                        <!-- Delete Button -->
                        <a href="dealer-view.php?delete_id= <?php echo $manager['id']; ?>"
                            onclick="return confirm('Are you sure you want to delete this manager?')"
                            class=" btn-danger">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr>
                    <td colspan="8">No managers found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Edit Manager Form -->
        <?php
    if (isset($_POST['edit_manager'])) {
        $edit_id = $_POST['id'];
        $sql = "SELECT * FROM create_manager WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $edit_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $manager = $result->fetch_assoc();
    ?>
        <h2>Edit Manager</h2>
        <form method="post" action="">
            <input type="hidden" name="id" value="<?php echo $manager['id']; ?>">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($manager['name']); ?>"
                required><br>

            <label for="username">Username:</label>
            <input type="text" name="username" id="username"
                value="<?php echo htmlspecialchars($manager['username']); ?>" required><br>

            <label for="contact">Contact:</label>
            <input type="text" name="contact" id="contact" value="<?php echo htmlspecialchars($manager['contact']); ?>"
                required><br>

            <label for="role">Role:</label>
            <input type="text" name="role" id="role" value="<?php echo htmlspecialchars($manager['role']); ?>"
                required><br>

            <label for="address">Address:</label>
            <input type="text" name="address" id="address" value="<?php echo htmlspecialchars($manager['address']); ?>"
                required><br>

            <label for="gst">Gst:</label>
            <input type="text" name="gst" id="gst" value="<?php echo htmlspecialchars($manager['gst']); ?>"
                required><br>

            <input type="submit" name="update_manager" value="Update Manager">
        </form>
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Admin Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm" method="POST" action="">
                            <input type="hidden" id="edit-id" name="id">
                            <input type="hidden" name="action" value="edit">
                            <div class="mb-2">
                                <label for="edit-Name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="edit-Name" name="name">
                            </div>

                            <div class="mb-2">
                                <label for="edit-Email" class="form-label">Email</label>
                                <input type="text" class="form-control" id="edit-Email" name="email">
                            </div>

                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>

        <script src="../js/navcss.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>