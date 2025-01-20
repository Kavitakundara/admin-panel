<?php
include '../auth.php';
include '../conn.php';
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Get form data
$pro_name = $conn->real_escape_string($_POST['pro_name']);
$model = $conn->real_escape_string($_POST['model']);
$date = $conn->real_escape_string($_POST['date']);
$quantity = $conn->real_escape_string($_POST['quantity']);
$color = $conn->real_escape_string($_POST['color']);

// Insert data into the table
$sql = "INSERT INTO place_order (pro_name, model, date, quantity, color) VALUES ('$pro_name', '$model', '$date',
'$quantity', '$color')";

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
    <title>Order Page</title>
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
            <div class="mng-top-side">
                <img src=" https://www.rayonengineers.com/assets/img/logo.png" alt="">
                <button class="closeBtn">&times;</button>
            </div>
            <ul>
                <!-- <li><i class="fas fa-home"></i><a href="./manager-home.php">Home</a></li> -->
                <li><i class="fas fa-eye"></i></i> <a href="./order-page.php">Place Order</a></li>
                <li><i class="fas fa-eye"></i></i> <a href="./view-orders.php">View Orders</a></li>
                <li><i class="fab fa-salesforce"></i><a href="./sales-page.php">Create Sales</a></li>
                <li><i class="fab fa-salesforce"></i><a href="./view-sales.php">View Sales</a></li>
            </ul>
        </div>
    </div>

    <div id="content" class="ma_1">
        <div class="container form-css mb-3 mt-5">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title">Place Your Order</div>
                    <form action="" method="POST" id="orderPlaceForm">
                        <div class="user__details">
                            <div class="input__box mx-2">
                                <span class="details">Product Name:</span>
                                <select id="productID" name="pro_name" required>
                                    <option value="">Select a product</option>
                                    <option value="Rocky Top Model Double Chasis">E Rickshaw</option>
                                    <option value="Mini Metro Double Chasis">Electric Auto</option>
                                    <option value="Rocky Base Model MS Deluxe">Loader</option>
                                    <option value="Rocky MS Deluxe Double Chasis">Two Wheeler</option>
                                </select>
                            </div>
                            <div class="input__box mx-2">
                                <span class="details">Product Model:</span>
                                <select id="productID" name="model" required>
                                    <option value="">Select a model</option>
                                    <option value="Rocky Top Model Double Chasis">Rocky Top Model Double Chasis</option>
                                    <option value="Mini Metro Double Chasis">Mini Metro Double Chasis</option>
                                    <option value="Rocky Base Model MS Deluxe">Rocky Base Model MS Deluxe</option>
                                    <option value="Rocky MS Deluxe Double Chasis">Rocky MS Deluxe Double Chasis</option>
                                    <option value="Rocky Base SS Deluxe Double Chasis">Rocky Base SS Deluxe Double
                                        Chasis</option>
                                    <option value="SK Express Passenger Rickshaw">S K Express Passenger Rickshaw
                                    </option>
                                    <option value="Electric Auto L5">Electric Auto L5</option>
                                    <option value="Electric Garbage Loader">Electric Garbage Loader</option>
                                    <option value="Electric Cargo Loader">Electric Cargo Loader</option>
                                    <option value="Rocky Eco Lite Scooter">Rocky Eco Lite Scooter</option>
                                    <option value="Rocky Urban Pro Model">Rocky Urban Pro Model</option>
                                    <option value="Rocky Max Scooter (Vespa Model)">Rocky Max Scooter (Vespa Model)
                                    </option>
                                </select>


                            </div>
                            <div class="input__box mx-2">
                                <span class="details">Quantity:</span>
                                <input type="number" id="quantity" name="quantity" min="1" required>
                            </div>
                            <div class="input__box">
                                <span class="details">Date:</span>
                                <input type="date" id="date" name="date" required>
                            </div>
                            <div class="input__box">
                                <span class="details">Color</span>
                                <input type="text" id="color" name="color" required>
                            </div>
                            <div class="input__box">
                                <span></span>
                                <button type="submit" id="regi-btn" class="button mx-3">Place Order</button>
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