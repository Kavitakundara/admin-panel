<?php
include '../auth.php';
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "super_admin";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'delete') {
            $id = intval($_POST['id']);
            error_log("Deleting ID: " . $id); // Log the ID for debugging
            $stmt = $conn->prepare("DELETE FROM create_products WHERE id = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Record deleted successfully"]);
            } else {
                error_log("Delete Error: " . $stmt->error); // Log any errors
                echo json_encode(["success" => false, "message" => "Failed to delete record"]);
            }
            $stmt->close();
        }
        

        if ($action === 'edit') {
            $id = intval($_POST['id']);
            $name = $_POST['name'];
            $inventory = intval($_POST['inventory']);
            $slug = $_POST['slug'];

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imageName = basename($_FILES['image']['name']);
                $imagePath = "uploads/" . $imageName;
                move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);

                $stmt = $conn->prepare("UPDATE create_products SET name = ?, inventory = ?, slug = ?, img = ? WHERE id = ?");
                $stmt->bind_param("sissi", $name, $inventory, $slug, $imageName, $id);
            } else {
                $stmt = $conn->prepare("UPDATE create_products SET name = ?, inventory = ?, slug = ? WHERE id = ?");
                $stmt->bind_param("sisi", $name, $inventory, $slug, $id);
            }

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Record updated successfully"]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to update record"]);
            }
            $stmt->close();
        }
    }
    exit;
}

$sql = "SELECT * FROM create_products";
$result = $conn->query($sql);
$managers = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
    <link rel="stylesheet" href="../css/style.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="../js/istocken.js"></script>
    <title>Create Products</title>
    <style>
    .input__box input[type="file"] {
        display: block;
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        cursor: pointer;
        background-color: #f9f9f9;
    }

    @media (max-width: 667px) {
        .input__box input[type="file"] {
            display: flex;
            font-size: 16px;
            padding: 10px;
            justify-content: center;
        }
    }
    </style>
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
        <!-- MAIN CONTENT OF THE HOME PAGE -->
    </div>
    <div id="content">
        <div class="">
            <h1>Product List</h1>
            <table class=" table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Image</th>
                        <th>Inventory Count</th>
                        <th>Slug</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($managers as $manager): ?>
                    <tr>
                        <td><?= htmlspecialchars($manager['name']); ?></td>
                        <td><img src="uploads/<?= htmlspecialchars($manager['img']); ?>" alt="Product Image"
                                style="width: 100px; height: auto;"></td>
                        <td><?= htmlspecialchars($manager['inventory']); ?></td>
                        <td><?= htmlspecialchars($manager['slug']); ?></td>
                        <td>
                            <button class="btn btn-info btn-sm"
                                onclick="openEditModal(<?= $manager['id']; ?>, '<?= htmlspecialchars($manager['name']); ?>', <?= $manager['inventory']; ?>, '<?= htmlspecialchars($manager['slug']); ?>')">Edit</button>
                            <button class="btn btn-danger btn-sm"
                                onclick="deleteProduct(<?= $manager['id']; ?>)">Delete</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="edit-id">
                        <div class="mb-3">
                            <label for="edit-name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="edit-name">
                        </div>
                        <div class="mb-3">
                            <label for="edit-inventory" class="form-label">Inventory</label>
                            <input type="number" class="form-control" id="edit-inventory">
                        </div>
                        <div class="mb-3">
                            <label for="edit-slug" class="form-label">Slug</label>
                            <input type="text" class="form-control" id="edit-slug">
                        </div>
                        <div class="mb-3">
                            <label for="edit-image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="edit-image">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="updateProduct()">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    function deleteProduct(id) {
        if (confirm("Are you sure you want to delete this product?")) {
            axios.post('', {
                    action: 'delete',
                    id
                })
                .then(response => {
                    console.log(response.data); // Log response for debugging
                    alert(response.data.message);
                    if (response.data.success) location.reload();
                })
                .catch(error => console.error(error));
        }
    }

    function openEditModal(id, name, inventory, slug) {
        document.getElementById('edit-id').value = id;
        document.getElementById('edit-name').value = name;
        document.getElementById('edit-inventory').value = inventory;
        document.getElementById('edit-slug').value = slug;
        new bootstrap.Modal(document.getElementById('editModal')).show();
    }

    function updateProduct() {
        const formData = new FormData();
        formData.append('action', 'edit');
        formData.append('id', document.getElementById('edit-id').value);
        formData.append('name', document.getElementById('edit-name').value);
        formData.append('inventory', document.getElementById('edit-inventory').value);
        formData.append('slug', document.getElementById('edit-slug').value);
        const image = document.getElementById('edit-image').files[0];
        if (image) formData.append('image', image);

        axios.post('', formData)
            .then(response => {
                alert(response.data.message);
                if (response.data.success) location.reload();
            })
            .catch(error => console.error(error));
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>