<?php
session_start();
include '../conn.php';
$user_role = $_SESSION['role'];  
$admin_username = $_SESSION['username'];  
// Process Edit Form Submission
if (isset($_POST['update_manager'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $username = $_POST['username'];
    $contact = $_POST['contact'];
    // $role = $_POST['role'];
    $address = $_POST['address'];
    $gst = $_POST['gst'];

    $sql = "UPDATE create_manager SET name=?, username=?, contact=?, address=?, gst=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $name, $username, $contact, $address, $gst, $id);

    if ($stmt->execute()) { 
        echo "<script>alert('Dealer updated successfully'); window.location.href='admin-view-mng.php';</script>";
    } else {
        echo "<script>alert('Error updating Dealer');</script>";
    }
}

// Process Delete Request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM create_manager WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        echo "<script>alert('Dealer deleted successfully'); window.location.href='admin-view-mng.php';</script>";
    } else {
        echo "<script>alert('Error deleting dealer');</script>";
    }
}

// Fetch managers from the database
$sql = "SELECT * FROM create_manager 
        WHERE created_by = '$admin_username' ";
$result = $conn->query($sql);
?>

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

form.mbg-form {
    position: absolute;
    background: white;
    padding: 10px;
    width: 26%;
    left: 41%;
    top: 10%;
}

.mng-row {
    margin-bottom: 6px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.mng-row input {
    border: 1px solid #8080806b;
}

.mng-row label {
    font-family: sans-serif;
    color: black !important;
    font-size: 18px;
    padding-top: 11px;
    padding-bottom: 4px;
}

.update-mnger {
    background: #F4511E;
    color: white;
}
</style>
<?php include 'header.php'; ?>

<div id="content">
    <div class="container my-3">
        <input type="text" id="searchBox" class="form-control" placeholder="Search for dealers...">
    </div>
    <table>
        <!-- Responsive Table Header Section -->

        <thead>
            <tr>
                <th style="width:50px" scope="col">S.No</th>
                <th scope="col">Manager Id</th>
                <th scope="col">Name</th>
                <th scope="col">Username</th>
                <th scope="col">Contact</th>
                <!-- <th scope="col">Role</th> -->
                <th style="width:180px" scope="col">Address</th>
                <th style="width:180px" scope="col">Gst</th>
                <th scope="col">Document</th>
                <th colspan="2" scope="col">Actions/Deler Data</th>
                

            </tr>
        </thead>
        <!-- Responsive Table Body Section -->
        <tbody>
            <?php if ($result->num_rows > 0): ?>
            <?php $serialNumber = 1;?>
            <?php while ($manager = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $serialNumber++; ?></td>
                <td><?php echo htmlspecialchars($manager['id']); ?></td>
                <td><?php echo htmlspecialchars($manager['name']); ?></td>
                <td><?php echo htmlspecialchars($manager['username']); ?></td>
                <td><?php echo htmlspecialchars($manager['contact']); ?></td>
                <td><?php echo htmlspecialchars($manager['address']); ?></td>
                <td><?php echo htmlspecialchars($manager['gst']); ?></td>
                <td class="mng-dow">
                    <?php 
                    $documents = explode(',', $manager['document']); // If stored as comma-separated values
                    if (!empty($manager['document'])): 
                        foreach ($documents as $doc): ?>
                    <a href="document/<?= htmlspecialchars(trim($doc)); ?>" download>
                        <?= htmlspecialchars(trim($doc)); ?>
                    </a><br>
                    <?php endforeach; 
                    else: ?>
                    No documents
                    <?php endif; ?>
                </td>

                <td style="display:flex">

                    <!-- Delete Button -->
                    <a href="admin-view-mng.php?delete_id= <?php echo $manager['id']; ?>"
                        onclick="return confirm('Are you sure you want to delete this manager?')"
                        class="dlt-btn">Delete</a>

                    <!-- Edit Button -->
                    <form method="post" action="admin-view-mng.php"> <input type="hidden" name="id"
                            value="<?php echo $manager['id']; ?>">
                        <input type="submit" class="edit-btn" name="edit_manager" value="Edit" />
                    </form>
                    <a href="manager-details.php?id=<?php echo $manager['id']; ?>"
                        class="btn btn-info mng-view">View</a>

                </td>

            </tr>
            <?php endwhile; ?>
            <?php else: ?>
            <tr>
                <td colspan="8">No managers found.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>`

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

    <form method="post" action="" class="mbg-form">
        <h2>Edit Manager</h2>
        <div class="mng-row">
            <input type="hidden" name="id" value="<?php echo $manager['id']; ?>">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($manager['name']); ?>" required>
        </div>
        <div class="mng-row">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username"
                value="<?php echo htmlspecialchars($manager['username']); ?>" required>
        </div>
        <div class="mng-row">

            <label for="contact">Contact:</label>
            <input type="text" name="contact" id="contact" value="<?php echo htmlspecialchars($manager['contact']); ?>"
                required>
        </div>
        <div class="mng-row">
            <label for="address">Address:</label>
            <input type="text" name="address" id="address" value="<?php echo htmlspecialchars($manager['address']); ?>"
                required>
        </div>
        <div class="mng-row">

            <label for="gst">Gst:</label>
            <input type="text" name="gst" id="gst" value="<?php echo htmlspecialchars($manager['gst']); ?>" required>
        </div>

        <input type="submit" class="update-mnger" name="update_manager" value="Update Manager">
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

    </script>
    <script src="../js/navcss.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>

    </html>