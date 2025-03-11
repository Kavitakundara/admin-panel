<?php
session_start();
include '../conn.php';

$manager_id = $_SESSION['user_id'];  
$dealerName = $_SESSION['name'];  

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['payment_mode']) && !empty($_POST['transaction_id']) && !empty($_POST['amount']) && !empty($_POST['transaction_date'])) {
        $mode = mysqli_real_escape_string($conn, $_POST['payment_mode']);
        $tr_id = mysqli_real_escape_string($conn, $_POST['transaction_id']);
        $amount = mysqli_real_escape_string($conn, $_POST['amount']);
        $date = mysqli_real_escape_string($conn, $_POST['transaction_date']);

        // Handle file upload
        if (isset($_FILES['payment_screenshot']) && $_FILES['payment_screenshot']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = "uploads/"; 
            $file_tmp = $_FILES['payment_screenshot']['tmp_name'];
            $file_ext = strtolower(pathinfo($_FILES['payment_screenshot']['name'], PATHINFO_EXTENSION));
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif']; 

            if (in_array($file_ext, $allowed_ext)) {
                $file_name = uniqid("payment_", true) . "." . $file_ext; // Unique file name
                $file_path = $upload_dir . $file_name;

                if (move_uploaded_file($file_tmp, $file_path)) {
                    // Insert into database using prepared statement
                    $stmt = $conn->prepare("INSERT INTO payment (manager_id, payment_mode, transaction_id, amount, transaction_date, payment_screenshot) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("isssss", $manager_id, $mode, $tr_id, $amount, $date, $file_name);

                    if ($stmt->execute()) {
                        addOrderNotification($conn, $manager_id, "New Payment Received", $dealerName);
                        $stmt->close();
                        echo "<script>alert('Payment Done From Your Side, Wait For The Approval!');</script>";
                        header("Location: " . $_SERVER['PHP_SELF']); // Prevent resubmission
                        exit();
                    } else {
                        echo "Error: " . $stmt->error;
                    }
                } else {
                    echo "Error uploading payment screenshot.";
                }
            } else {
                echo "Invalid file format. Only JPG, JPEG, PNG, and GIF are allowed.";
            }
        } else {
            echo "Please upload a valid payment screenshot.";
        }
    } else {
        echo "All fields are required!";
    }
}

// Fetch product data
$query = "SELECT set_name, set_price FROM dealer_product_prices WHERE manager_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $manager_id);
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

function addOrderNotification($conn, $manager_id, $message, $dealerName) {
    $stmt = $conn->prepare("INSERT INTO notifications (manager_id, message, dealername) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $manager_id, $message, $dealerName);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
?>

<?php include "header.php"; ?>

<div id="content" class="ma_1">
    <div class="container form-css mb-3 mt-5">
        <div class="row">
            <div class="col-lg-12">
                <div class="title">Payment Details</div>
                <form action="" method="POST" id="paymentForm" class="mng-pymt" enctype="multipart/form-data">
                    <div class="user__details">
                        <div class="input__box mx-2">
                            <span class="details">Payment Mode:</span>
                            <select name="payment_mode" id="paymentMode" required>
                                <option value="">Select Payment Mode</option>
                                <option value="CASH">Cash</option>
                                <option value="RTGS">RTGS</option>
                                <option value="UPI">UPI</option>
                            </select>
                        </div>

                        <div class="input__box mx-2">
                            <span class="details">Transaction ID:</span>
                            <input type="text" id="transactionID" name="transaction_id" required placeholder="Enter Transaction ID">
                        </div>
                        
                        <div class="input__box mx-2">
                            <span class="details">Amount:</span>
                            <input type="text" id="amount" name="amount" required>
                        </div>
                        
                        <div class="input__box mx-2">
                            <span class="details">Transaction Date:</span>
                            <input type="date" id="transaction_date" name="transaction_date" required>
                        </div>
                        
                        <div class="input__box mx-2">
                            <span class="details">Screenshot of Payment:</span>
                            <input type="file" id="payment_screenshot" name="payment_screenshot" accept="image/*" required>
                        </div>

                        <div class="input__box">
                            <button type="submit" name="submit_payment" class="ptm-btn">Submit Payment</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="../js/navcss.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
