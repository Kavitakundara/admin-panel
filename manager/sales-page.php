<?php
include '../auth.php';
include '../conn.php';
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Get form data
// Retrieving and sanitizing form inputs
$product_name = $conn->real_escape_string($_POST['productID']);
$model = $conn->real_escape_string($_POST['model']);
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



$sql = "INSERT INTO create_sales (product_name, model, color, chassis_no, motor_no, controller_no,
battery_no, charger, rto, oth_chrg, price, sale_mode, buyer_name, buyer_no, buyer_add, qty, sale_date)

VALUES ('$product_name', '$model', '$colour', '$chassis_no', '$motor_no', '$controller_no',
'$battery_no', '$charger', '$rto_charge', '$other_charge', '$total_price', '$sale_mode',
'$buyer_name', '$buyer_phone', '$buyer_address', '$qty', '$date')";


if ($conn->query($sql) === TRUE) {
echo "Order placed successfully!";
} else {
echo "Error: " . $sql . "<br>" . $conn->error;
}
}

// Close the connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" />
    <script src="../js/istocken.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert -->
    <title>Sales Page</title>
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
            <ul>
                <!-- <li><i class="fas fa-home"></i><a href="./manager-home.php">Home</a></li> -->
                <li><i class="fas fa-eye"></i><a href="./order-page.php">Place Order</a></li>
                <li><i class="fas fa-eye"></i><a href="./view-orders.php">View Orders</a></li>
                <li><i class="fab fa-salesforce"></i><a href="./sales-page.php">Create Sales</a></li>
                <li><i class="fab fa-salesforce"></i><a href="./view-sales.php">View Sales</a></li>
            </ul>
        </div>
    </div>
    <div id="content">
        <div class="container form-css mb-3 mt-5">
            <div class="row">
                <div class="col-lg-12 min_he">
                    <div class="title">Create Sales</div>
                    <form id="salesForm" method="POST" action="">
                        <div class="user__details">
                            <div class="input__box mx-2">
                                <span class="details">Product Name:</span>
                                <select id="productID" name="productID" required>
                                    <option value="">Select a product</option>
                                    <option value="one">One</option>
                                    <option value="Two">Two</option>
                                    <option value="Three">Three</option>
                                    <option value="Four">Four</option>
                                    <option value="Five">Five</option>

                                </select>
                            </div>
                            <div class="input__box mx-2">
                                <span class="details">Model:</span>
                                <input type="text" id="model" name="model" required>
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
                                <input type="text" id="otherCharge" name="otherCharge" required>
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
                                <span class="details">Quantity</span>
                                <input type="number" name="qty" id="qty" required>
                            </div>
                            <div class="input__box mx-2">
                                <span class="details">Date</span>
                                <input type="date" name="date" id="date" required>
                            </div>
                        </div>
                        <button type="submit" id="regi-btn" class="button btn_bb">Place Order</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="../js/navcss.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>