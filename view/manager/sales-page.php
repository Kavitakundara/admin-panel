<?php
// include '../auth.php';
session_start();
include '../conn.php';  // Current user DB connection

// Check if user_id exists in the session
if (isset($_SESSION['user_id'])) {
    $manager_id = $_SESSION['user_id']; 
    $dealerName = $_SESSION['name']; 
} else {
    echo "Manager ID not found in session.";
    exit; // Stop further execution if session value is missing
}


// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieving and sanitizing form inputs
    $product_name = $conn->real_escape_string($_POST['pro_name']);
    $colour = $conn->real_escape_string($_POST['colour']);
    $chassis_no = $conn->real_escape_string($_POST['chassisNo']);
    $motor_no = $conn->real_escape_string($_POST['motorNo']);
    $controller_no = $conn->real_escape_string($_POST['controllerNo']);
    $battery_no = $conn->real_escape_string($_POST['batteryNo']);
    $charger = $conn->real_escape_string($_POST['charger']);
    $rto_charge = $conn->real_escape_string($_POST['rtoCharge']);
    $other_charge = $conn->real_escape_string($_POST['otherCharge']);
    $total_price = $conn->real_escape_string($_POST['tprice']);
    $sale_mode = $conn->real_escape_string($_POST['saleMode']);
    $buyer_name = $conn->real_escape_string($_POST['buyerName']);
    $buyer_phone = $conn->real_escape_string($_POST['buyerPhoneNumber']);
    $buyer_address = $conn->real_escape_string($_POST['buyerAddress']);
    $qty = $conn->real_escape_string($_POST['qty']);
    $date = $conn->real_escape_string($_POST['date']);
    $loan_hp = $conn->real_escape_string($_POST['loanHp']);
    $loan_hp_payment = $conn->real_escape_string($_POST['loanHpPayment']);
    $nominee_name = $conn->real_escape_string($_POST['nomineeName']);
    $relation = $conn->real_escape_string($_POST['relation']);
    $age = $conn->real_escape_string($_POST['age']);
    $sprice = $conn->real_escape_string($_POST['sprice']);
    // $manager_id = $_SESSION['user_id'];  

    // Check if the chassis_no, motor_no, controller_no, and battery_no exist in the super_admin database
    $query = "
        SELECT * FROM u138080682_super_admin.product_chassis_numbers AS pc 
        JOIN u138080682_super_admin.create_products AS cp 
        ON pc.chassis_no = '$chassis_no' AND 
           cp.motor_no = '$motor_no' AND 
           cp.controller_no = '$controller_no' AND 
           cp.battery_no = '$battery_no'
    ";
    $result = $conn->query($query);
   
    // If there is a match
    if ($result->num_rows > 0) {
        // If all data matches, insert into the sales database
        $sql = "INSERT INTO create_sales (product_name, color, chassis_no, motor_no, controller_no, 
            battery_no, charger, rto, oth_chrg, price, sale_mode, buyer_name, buyer_no, buyer_add, qty, sale_date, 
            loan_hp, loan_hp_payment, nominee_name, relation, age, sale_price, manager_id)
        VALUES ('$product_name','$colour', '$chassis_no', '$motor_no', '$controller_no', 
                '$battery_no', '$charger', '$rto_charge', '$other_charge', '$total_price', '$sale_mode', 
                '$buyer_name', '$buyer_phone', '$buyer_address', '$qty', '$date', '$loan_hp', 
                '$loan_hp_payment', '$nominee_name', '$relation', '$age', '$sprice', '$manager_id')";

        if ($conn->query($sql) === TRUE) {
            $message = "New Sale Done";
            addOrderNotification($conn, $managerId, $message, $dealerName);
            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Order Placed!',
                        text: 'Your order has been successfully placed.',
                        confirmButtonText: 'Okay'
                    }).then(function() {
                        window.location = 'view-orders.php'; // Redirect to the orders page after success
                    });
                  </script>";
        } else {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong. Please try again later.',
                        confirmButtonText: 'Retry'
                    });
                  </script>";
        }
    } else {
      // If no match found in the super_admin's database, show an error
      echo "<script>
      Swal.fire({
          icon: 'error',
          title: 'Error!',
          text: 'Chassis, Motor, Controller, or Battery numbers do not match the super admin database!',
          confirmButtonText: 'Okay'
      });
    </script>";
    }
}
function addOrderNotification($conn, $managerId, $message, $dealerName) {
    $stmt = $conn->prepare("INSERT INTO notifications (manager_id, message, dealername) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $managerId, $message, $dealerName);
    $stmt->execute();
    $stmt->close();
}

// /fetch the data
$manager_id = $_SESSION['user_id'];  
$query = "SELECT set_name, set_price FROM dealer_product_prices WHERE manager_id = $manager_id";
$result = mysqli_query($conn, $query);
// Check for query errors
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
// Fetch all products
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);
// Close the connection
$conn->close();

?>

<?php include "header.php" ?>

<div id="content">
    <div class="container form-css mb-3 mt-5">
        <div class="row">
            <div class="col-lg-12 min_he">
                <div class="title">Create Sales</div>
                <form id="salesForm" method="POST" action="">
                    <div class="user__details">
                        <div class="input__box mx-2">
                            <span class="details">Enter Product Name</span>
                            <input type="text" id="pro_name" name="pro_name" required>
                        </div>
                        <div class="input__box mx-2">
                            <span class="details">Colour:</span>
                            <input type="text" id="colour" name="colour" required>
                        </div>
                        <div class="input__box mx-2">
                            <span class="details">Chassis No.:</span>
                            <input type="text" id="chassisNo" name="chassisNo" required>
                        </div>
                        <div class="input__box mx-2">
                            <span class="details">Motor No.:</span>
                            <input type="text" id="motorNo" name="motorNo" required>
                        </div>
                        <div class="input__box mx-2">
                            <span class="details">Controller No.:</span>
                            <input type="text" id="controllerNo" name="controllerNo" required>
                        </div>
                        <div class="input__box mx-2">
                            <span class="details">Battery No.:</span>
                            <input type="text" id="batteryNo" name="batteryNo" required>
                        </div>
                        <div class="input__box mx-2">
                            <span class="details">Charger:</span>
                            <input type="text" id="charger" name="charger" required>
                        </div>
                        <div class="input__box mx-2">
                            <span class="details">RTO Charge:</span>
                            <input type="text" id="rtoCharge" name="rtoCharge" required>
                        </div>
                        <div class="input__box mx-2">
                            <span class="details">Other Charge:</span>
                            <input type="text" id="otherCharge" name="otherCharge">
                        </div>
                        <div class="input__box mx-2">
                            <span class="details">Sale Price:</span>
                            <input type="text" id="salePrice" name="sprice" required>
                        </div>
                        <div class="input__box mx-2">
                            <span class="details">Total Price:</span>
                            <input type="text" id="totalPrice" name="tprice" required>
                        </div>
                        <div class="input__box mx-2">
                            <span class="details">Sale Mode:</span>
                            <select id="saleMode" name="saleMode" required>
                                <option value="cash">Cash</option>
                                <option value="finance">Finance</option>
                            </select>
                        </div>
                        <div class="input__box mx-2">
                            <span class="details">Buyer Name:</span>
                            <input type="text" name="buyerName" id="buyerName" required>
                        </div>
                        <div class="input__box mx-2">
                            <span class="details">Buyer Phone Number:</span>
                            <input type="number" name="buyerPhoneNumber" id="buyerPhoneNumber" required>
                        </div>
                        <div class="input__box mx-2">
                            <span class="details">Buyer Address:</span>
                            <input type="text" name="buyerAddress" id="buyerAddress" required>
                        </div>
                        <div class="input__box mx-2">
                            <span class="details">Quantity:</span>
                            <input type="number" name="qty" id="qty" required>
                        </div>
                        <div class="input__box mx-2">
                            <span class="details">Date:</span>
                            <input type="date" name="date" id="date" required>
                        </div>

                        <!-- Additional fields -->
                        <div class="input__box mx-2">
                            <span class="details">Loan HP:</span>
                            <input type="text" id="loanHp" name="loanHp">
                        </div>
                        <div class="input__box mx-2">
                            <span class="details">Loan HP:</span>

                            <div class="box">
                                <label for="loanHpYes" class="radio-label">
                                    <input type="radio" id="loanHpYes" name="loanHpPayment" value="yes">
                                    Yes
                                </label>
                                <label for="loanHpNo" class="radio-label">
                                    <input type="radio" id="loanHpNo" name="loanHpPayment" value="no">
                                    No
                                </label>
                            </div>
                        </div>

                        <div class="input__box mx-2">
                            <span class="details">Buyer Nominee Name:</span>
                            <input type="text" id="nomineeName" name="nomineeName">
                        </div>
                        <div class="input__box mx-2">
                            <span class="details">Relation:</span>
                            <input type="text" id="relation" name="relation">
                        </div>
                        <div class="input__box mx-2">
                            <span class="details">Age:</span>
                            <input type="number" id="age" name="age">
                        </div>
                    </div>
                    <button type="submit" id="regi-btn" class="button btn_bb">Place Order</button>
                </form>

            </div>
        </div>
    </div>
</div>
<script>
function setProductPrice() {
    const modelSelect = document.getElementById('pro_name');
    const selectedOption = modelSelect.options[modelSelect.selectedIndex];
    const price = selectedOption.getAttribute('data-price');

    document.getElementById('product_price').value = price ? `₹${price}` : '';
}
</script>

<script>
// Function to update the total price dynamically
function calculateTotalPrice() {
    let salePrice = parseFloat(document.getElementById("salePrice").value) || 0;
    let rtoCharge = parseFloat(document.getElementById("rtoCharge").value) || 0;
    let otherCharge = parseFloat(document.getElementById("otherCharge").value) || 0;

    let gst = salePrice * 0.05;

    // Calculate total price
    let totalPrice = salePrice + gst + rtoCharge + otherCharge;

    // Update total price field
    document.getElementById("totalPrice").value = totalPrice.toFixed(2); // To show 2 decimal places
}

// Event listeners for dynamic calculation
document.getElementById("salePrice").addEventListener("input", calculateTotalPrice);
document.getElementById("rtoCharge").addEventListener("input", calculateTotalPrice);
document.getElementById("otherCharge").addEventListener("input", calculateTotalPrice);
</script>
<script src="../js/navcss.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>