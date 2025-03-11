<?php
session_start();
include '../conn.php';
$user_role = $_SESSION['role'];  
$admin_username = $_SESSION['username'];  
// Initialize variables
$message = '';
$alertType = '';
$alertMessage = '';
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $phn = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $gst = trim($_POST['gst']);
    $password = trim($_POST['password']);
    $cpassword = trim($_POST['cpassword']);
    $user_role = $_SESSION['role'];

    // Password validation
    if ($password !== $cpassword) {
        $alertType = 'error';
        $alertMessage = 'Passwords do not match!';
    } else {
        // Hash password for security
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // File upload configuration
        $uploadDir = 'document/';
        $allowedTypes = ['jpg', 'jpeg', 'png', 'pdf'];
        $maxFileSize = 5 * 1024 * 1024; // 5MB
        $userId = 1; // Replace with actual user ID
        $uploadedFiles = [];

        // Ensure upload directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Process multiple file uploads
        if (!empty($_FILES['doc']['name'][0])) {
            foreach ($_FILES['doc']['name'] as $key => $fileName) {
                $fileTmpPath = $_FILES['doc']['tmp_name'][$key];
                $fileSize = $_FILES['doc']['size'][$key];
                $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $uniqueFileName = time() . '_' . basename($fileName);
                $targetFilePath = $uploadDir . $uniqueFileName;

                // Validate file type and size
                if (!in_array($fileType, $allowedTypes)) {
                    $alertMessage .= "Invalid file type for $fileName.<br>";
                } elseif ($fileSize > $maxFileSize) {
                    $alertMessage .= "$fileName exceeds 5MB.<br>";
                } else {
                    // Move uploaded file
                    if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
                        $uploadedFiles[] = [
                            'file_name' => $uniqueFileName,
                            'file_path' => $targetFilePath,
                            'file_type' => $fileType
                        ];
                    } else {
                        $alertMessage .= "Failed to upload $fileName.<br>";
                    }
                }
            }
        }

        // Check for duplicate username
        $checkQuery = $conn->prepare("SELECT id FROM create_manager WHERE username = ?");
        $checkQuery->bind_param("s", $username);
        $checkQuery->execute();
        $checkQuery->store_result();

        if ($checkQuery->num_rows > 0) {
            $alertType = 'error';
            $alertMessage = 'Username already exists!';
        } else {
            // Insert user data into create_manager table
            $insertQuery = $conn->prepare("INSERT INTO create_manager 
                (name, username, password, address, contact, gst, document, document_type, role, created_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            $documentNames = implode(',', array_column($uploadedFiles, 'file_name'));
            $documentTypes = implode(',', array_column($uploadedFiles, 'file_type'));
            
            $insertQuery->bind_param("ssssssssss", $name, $username, $hashedPassword, $address, $phn, $gst, $documentNames, $documentTypes, $user_role, $admin_username);

            if ($insertQuery->execute()) {
                // Insert file records into uploaded_files table
                $stmt = $conn->prepare("INSERT INTO uploaded_files (user_id, file_name, file_path, file_type) VALUES (?, ?, ?, ?)");
                foreach ($uploadedFiles as $file) {
                    $stmt->bind_param("isss", $userId, $file['file_name'], $file['file_path'], $file['file_type']);
                    $stmt->execute();
                }

                $alertType = 'success';
                $alertMessage = 'Manager registered successfully!';
            } else {
                $alertType = 'error';
                $alertMessage = 'Database error: ' . $conn->error;
            }
        }

        $checkQuery->close();
    }
}

$conn->close();
?>

<?php include 'header.php'; ?>

<div id="content">
    <div class="container form-css mb-3 mt-5">
        <div class="row">
            <div class="col-lg-12">
                <div class="title">
                    <h2>Dealer Registration</h2>
                </div>
                <form id="ManagerRegistration" class="form_c" method="POST" enctype="multipart/form-data" action="">
                    <div class="user__details">
                        <div class="input__box">
                            <span class="details">Name</span>
                            <input type="text" id="name" name="name" placeholder="E.g: John Smith" required>
                        </div>
                        <div class="input__box">
                            <span class="details">Username</span>
                            <input type="text" id="username" name="username" placeholder="johnWC98" required>
                        </div>
                        <div class="input__box">
                            <span class="details">Password</span>
                            <input type="password" id="password" name="password" placeholder="********" required>
                        </div>
                        <div class="input__box">
                            <span class="details">Confirm Password</span>
                            <input type="password" id="cpassword" name="cpassword" placeholder="********" required>
                        </div>
                        <div class="input__box ">
                            <span class="details">Address</span>
                            <input name="address" id="address" placeholder="Enter Your Address"></input>

                        </div>
                        <div class="input__box ">
                            <span class="details">Contact</span>
                            <input name="phone" id="contact" placeholder="9999XXXXXX"></input>

                        </div>
                        <div class="input__box ">
                            <span class="details">GST NO.</span>
                            <input type="text" name="gst" id="contact" placeholder="9999XXXXXX"></input>

                        </div>
                        <div class="input__box">
                            <span class="details">Document</span>
                            <input type="file" name="doc[]" id="document" accept="image/*,application/pdf" multiple>
                        </div>
                    </div>

                    <!-- <div class="gender__details gender_d">
                            <span class="gender__title role_b">Role</span>
                            <div class="category manager_m" id="role">
                                <label for="dealer">
                                    <input type="radio" id="dealer" name="role" value="dealer" required>

                                    &nbsp; <span>Dealer</span>
                                </label>
                                <label for="distributor">
                                    <input type="radio" id="distributor" name="role" value="distributor" required>

                                    &nbsp; <span>Distributor</span>
                                </label>
                            </div>
                        </div> -->
                    <div class="btn-css">
                        <button type="submit" id="regi-btn" class="button">Register</button>
                        <button type="submit" id="login-btn" class="button"><a href="../index.php">Login</a></button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const alertType = '<?= $alertType ?>';
    const alertMessage = '<?= $alertMessage ?>';

    if (alertType && alertMessage) {
        Swal.fire({
            icon: alertType,
            title: alertType === 'success' ? 'Success' : 'Error',
            text: alertMessage,
        });
    }
});
</script>
<script src="../js/navcss.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>