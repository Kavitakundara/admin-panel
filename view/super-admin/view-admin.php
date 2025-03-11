<?php
include '../conn.php';
include  "header.php"; 
// Handle Edit Request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $con = $_POST['phone'];

    $sql = "UPDATE create_admin SET name='$name', email='$email', phone='$con' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Record updated successfully!');</script>";
    } else {
        echo "<script>alert('Error updating record: " . $conn->error . "');</script>";
    }
}

// Handle Delete Request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['id'];

    $sql = "DELETE FROM create_admin WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Record deleted successfully!');</script>";
    } else {
        echo "<script>alert('Error deleting record: " . $conn->error . "');</script>";
    }
}

// Fetch the admin data
$sql = "SELECT * FROM create_admin";
$result = $conn->query($sql);
?>

<div id="content">
    <div class="container my-3">
        <input type="text" id="searchBox" class="form-control" placeholder="Search for dealers...">
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">User Name</th>
                <th scope="col">Email Id</th>
                <th scope="col">Contact Details</th>
                <th scope="col">Actions</th>
                <th scope="col">View Data</th>

            </tr>
        </thead>
        <tbody>
            <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['username'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['phone'] . "</td>";
            echo "<td>
                    <button class='btn btn-success' data-bs-toggle='modal' data-bs-target='#exampleModal' onclick='populateEditForm(" . $row['id'] . ", \"" . $row['name'] . "\", \"" . $row['email'] . "\" , \"" . $row['phone'] . "\")'><i class='fas fa-edit'></i></button>
                    <form method='POST' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to delete this admin?\");'>
                        <input type='hidden' name='id' value='" . $row['id'] . "'>
                        <input type='hidden' name='action' value='delete'>
                        <button type='submit' class='btn btn-danger'><i class='fas fa-trash'></i></button>
                    </form>
               
            </td>";
            echo "<td>
            <a href='admin-details.php?id=" . $row['id'] . "' class='btn btn-info mng-view'>View</a>
        </td>";

            echo "</tr>";
            }
            } else {
            echo "<tr>
                <td colspan='5'>No admins found</td>
            </tr>";
            }
            ?>
        </tbody>

    </table>
</div>

<!-- Modal for editing admin -->
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

                    <div class="mb-2">
                        <label for="edit-contact" class="form-label">Contact</label>
                        <input type="number" class="form-control" id="edit-contact" name="phone">
                    </div>

                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
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
<script>
function populateEditForm(id, name, email, phone) {
    document.getElementById('edit-id').value = id;
    document.getElementById('edit-Name').value = name;
    document.getElementById('edit-Email').value = email;
    document.getElementById('edit-contact').value = phone;
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
// Close database connection
$conn->close();
?>