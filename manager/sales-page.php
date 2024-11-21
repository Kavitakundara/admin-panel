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
                    <form id="salesForm">
                        <div class="user__details">
                            <div class="input__box mx-2">
                                <span class="details">Product Name:</span>
                                <select id="productID" name="productID" required>
                                    <option value="">Select a product</option>
                                    <option value="one">One</option>
                                </select>
                            </div>
                            <div class="input__box mx-2">
                                <span class="details">Quantity:</span>
                                <input type="number" id="quantity" name="quantity" min="1" required>
                            </div>
                            <div class="input__box mx-2">
                                <span class="details">Buyer Name</span>
                                <input type="text" name="buyerName" id="buyerName">
                            </div>
                            <div class="input__box mx-2">
                                <span class="details">Buyer Phone Number</span>
                                <input type="number" name="buyerPhoneNumber" id="buyerPhoneNumber">
                            </div>
                            <div class="input__box mx-2">
                                <span class="details">Buyer Address</span>
                                <input type="text" name="buyerAddress" id="buyerAddress">
                            </div>
                            <div class="input__box mx-2">
                                <span class="details">Total Price:</span>
                                <input type="text" id="totalPrice" name="tprice" required>
                            </div>
                        </div>
                        <button type="submit" id="regi-btn" class="button  btn_bb">Place Order</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fetch products from the API and populate the dropdown
        async function fetchProducts() {
            try {
                const response = await fetch("http://localhost:9000/api/products/get-product");
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                const products = await response.json();
                const productSelect = document.getElementById("productID");

                products.data.forEach((product) => {
                    const option = document.createElement("option");
                    option.value = product.id;
                    option.textContent = product.name;
                    productSelect.appendChild(option);
                });
            } catch (error) {
                console.error("There was a problem with the fetch operation:", error);
            }
        }

        // Call fetchProducts when the page loads
        document.addEventListener("DOMContentLoaded", fetchProducts);

        // Handle form submission
        document.getElementById("salesForm").addEventListener("submit", function (event) {
            event.preventDefault();

            const productID = document.getElementById("productID").value;
            const quantity = document.getElementById("quantity").value;
            const buyerName = document.getElementById("buyerName").value;
            const buyerPhoneNumber = document.getElementById("buyerPhoneNumber").value;
            const buyerAddress = document.getElementById("buyerAddress").value;
            const totalPrice = document.getElementById("totalPrice").value;

            let obj = { productID, quantity, totalPrice, buyerName, buyerPhoneNumber, buyerAddress };

            fetch("http://localhost:9000/api/sales/create-sales", {
                method: "POST",
                headers: {
                    Authorization: `Bearer ${sessionToken}`,
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(obj),
            })
                .then((res) => res.json())
                .then((data) => {
                    if (data.status) {
                        // Use SweetAlert for success notification
                        Swal.fire({
                            icon: 'success',
                            title: 'Sales Added successfully!',
                            text: 'Sales Added successfully!',
                        });
                    } else {
                        // Use SweetAlert for success notification
                        Swal.fire({
                            icon: 'error',
                            title: 'Error in creating Sales Report!',
                            text: 'Error in creating Sales Report!',
                        });
                    }
                })
                .catch((error) => {
                    console.error("There was a problem with the fetch operation:", error);
                    alert("Error placing order. Please try again later.");
                });
        });
    </script>
    <script src="../js/navcss.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>