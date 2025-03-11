<?php
include '../conn.php';
include  "header.php"; 
// Initialize an empty error or success message
$message = '';
$isDuplicate = false; // To track if the username is a duplicate


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// Get the form data
$name = trim($_POST['name']);
$username = trim($_POST['username']);
$phn = trim($_POST['phone']);
$email = trim($_POST['email']);
$password = trim($_POST['password']);
$cpassword = trim($_POST['cpassword']);
$role = trim($_POST['role']);



// Validate the input
if (empty($name) || empty($username) || empty($phn) || empty($email) || empty($password) || empty($cpassword)) {
$message = '<div class="alert alert-danger">All fields are required.</div>';
} elseif ($password !== $cpassword) {
$message = '<div class="alert alert-danger">Passwords do not match.</div>';
} else {
// Check if the username already exists
$checkQuery = "SELECT * FROM create_admin WHERE username = ?";
$stmt = $conn->prepare($checkQuery);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
$isDuplicate = True;
} else {
// Hash the password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Insert the admin into the database
$insertQuery = "INSERT INTO create_admin (name, username, phone, email, password, role) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($insertQuery);
$stmt->bind_param("ssssss", $name, $username, $phn, $email, $hashedPassword,$role);

if ($stmt->execute()) {
$message = 'Admin created successfully.';
} else {
$message = 'Error creating admin. Please try again.';
}
}
}
}
?>
<div id="content">
    <div class="container form-css mb-3 mt-5">
        <div class="row">
            <div class="col-lg-12">
                <div class="title">
                    <h2>Admin Registration</h2>
                </div>
                <form id="createAdminData" method="POST" onsubmit="return validateForm(event);">
                    <div class=" user__details">
                        <div class="input__box">
                            <span class="details">Name</span>
                            <input type="text" name="name" id=" name" placeholder="E.g: John Smith" required>
                        </div>
                        <div class="input__box">
                            <span class="details">Username</span>
                            <input type="text" name="username" id="username" placeholder="johnWC98" required>
                        </div>
                        <div class="input__box">
                            <span class="details">Contact</span>
                            <input type="text" name="phone" id="phone" placeholder="5623XXXXX" required>
                        </div>
                        <div class="input__box">
                            <span class="details">Email</span>
                            <input type="text" name="email" id="email" placeholder="john@gmail.com" required>
                        </div>
                        <div class="input__box">
                            <span class="details">Password</span>
                            <input type="password" name="password" id="password" placeholder="********" required>
                        </div>
                        <div class="input__box">
                            <span class="details">Confirm Password</span>
                            <input type="password" name="cpassword" id="cpassword" placeholder="********" required>
                        </div>
                        <div class="gender__details gender_d">
                            <span class="gender__title role_b">Role</span>
                            <div class="category manager_m" id="role">
                                <label for="dealer">
                                    <input type="radio" id="dealer" name="role" value="admin" required>

                                    &nbsp; <span>Admin</span>
                                </label>

                            </div>
                        </div>
                    </div>
                    <div class="btn-css btn-b">
                        <button type="submit" id="regi-btn" class="button">Register</button>
                        <!--<button type="button" id="login-btn" class="button"><a href="../../index.php">Login</a></button>-->
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
</div>
<script>
function validateForm(event) {
    const password = document.getElementById('password').value;
    const cpassword = document.getElementById('cpassword').value;

    if (password !== cpassword) {
        event.preventDefault(); // Prevent the form from being submitted
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Passwords do not match!',
        });
        return false;
    }
    return true;
}
</script>
<script>
// Display an alert if the username already exists
const isDuplicate = <?php echo json_encode($isDuplicate); ?>;
if (isDuplicate) {
    Swal.fire({
        icon: 'error',
        title: 'Duplicate Username',
        text: 'The username already exists. Please choose another one.',
    });
}

// Display an alert for server-side error messages
const serverMessage = <?php echo json_encode($message); ?>;
if (serverMessage) {
    Swal.fire({
        icon: serverMessage.includes('success') ? 'success' : 'error',
        title: serverMessage.includes('success') ? 'Success' : 'Error',
        text: serverMessage,
    });
}
</script>
<script src="../js/navcss.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>