<?php
session_start();
include '../conn.php';
include "header.php";

$managerId = $_SESSION['user_id']; 
$dealerName = $_SESSION['name']; 

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST['msg'])) {
    $message = trim($_POST['msg']); // Trim spaces
    addOrderNotification($conn, $managerId, $message, $dealerName);
    
    // Redirect to avoid form resubmission on refresh
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Function to insert notification into the database
function addOrderNotification($conn, $managerId, $message, $dealerName) {
    $stmt = $conn->prepare("INSERT INTO notifications (manager_id, message, dealername) VALUES (?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("iss", $managerId, $message, $dealerName);
        $stmt->execute();
        $stmt->close();
    }
}
?>

<div id="content" class="ma_1">
    <div class="container form-css mb-3 mt-5">
        <div class="row">
            <div class="col-lg-12">
                <div class="title">Type Your Query Here...</div>
                <form action="" method="POST" id="orderPlaceForm">
                    <div class="user__details">
                        <div class="input__box mx-2">
                            <span class="details">Message</span>
                            <textarea id="msg" name="msg" rows="4" cols="80" required></textarea>
                        </div>
                        <div class="input__box my-4">
                            <button type="submit" id="qry-btn" class="button mx-3">Submit Query</button>
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
