<?php
include '../auth.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dealer Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="../css/style.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" /> -->
    <script src="../js/istocken.js"></script>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f2f5;
        margin: 0;
        padding: 0;
    }

    #container {
        width: 80%;
        margin: 0 auto;
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    #container h4 {
        border-bottom: 2px solid #007bff;
        padding-bottom: 10px;
        margin-bottom: 20px;
        color: #007bff;
    }

    #container div {
        margin-bottom: 30px;
    }

    img {
        max-width: 150px;
        height: auto;
        border-radius: 50%;
        margin-bottom: 20px;
        margin: 20px !important;
    }

    p {
        margin: 10px 0;
        font-size: 18px;
    }

    span {
        font-weight: bold;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    th,
    td {
        padding: 12px;
        border: 1px solid #ddd;
        text-align: left;
    }

    th {
        background-color: #007bff;
        color: white;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #e9ecef;
    }

    td[colspan] {
        text-align: center;
        font-style: italic;
    }

    @media (max-width: 667px) {
        #container {
            width: 95%;
        }

        img {
            max-width: 100px;
        }

        p {
            font-size: 16px;
        }

        table {
            font-size: 14px;
        }

        th,
        td {
            padding: 8px;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }
    }


    @media only screen and (max-width: 767px) {
        .container .title {
            font-size: 27px;
        }

        .mg-box {
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            padding: 10px;
            border-radius: 2px;
            background-color: #009688;
            position: relative;
            height: 19vh;
            width: 300px;
            margin: 21px auto;


            /* #sid`eNav { */
            /* width: 0px !important; */
            /* } */

            #content {
                padding: 0px 14px 40px 15px !important;
            }

            #sideNav {
                padding-top: 14% !important;
                z-index: 1 !important;
            }

            #sideNav a {
                padding: 24px 12px !important;
            }

            .gender_d {
                margin-top: 163px !important;
            }
        }
    }

    /* Side Navigation Bar Styles */

    #sideNav {
        height: 100%;
        width: 250px;
        position: fixed;
        /* z-index: 1; */
        top: 0;
        left: 0;
        background-color: #000;
        overflow-x: hidden;
        padding-top: 6%;
        padding-left: 0px;
        transition: 0.5s;
        box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px,
            rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;
    }

    #sideNav a {
        padding: 14px 11px;
        text-decoration: none;
        font-family: system-ui;
        color: white;
        display: block;
        font-family: "Mukta", sans-serif;
        font-size: 17px;
        transition: 0.5s;
    }

    #sideNav a:hover {
        background-color: #555;
    }

    #sideNav .closeBtn {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 30px;
        background: black;
        color: white;
        cursor: pointer;
        outline: navajowhite;
        border: none;
    }

    /* topnav-bar */
    #topNav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: black;
        padding: 5px 35px;
        box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px,
            rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;
    }

    #topNav h1 {
        margin: 0;
    }

    #profileImg {
        width: 55px;
        height: 55px;
        border-radius: 9%;
        object-fit: cover;
        border-radius: 50%;
        /* padding: 6px; */
    }

    #profileImg:hover {
        cursor: pointer;
    }

    #hiddenFeatures {
        display: none;
        position: absolute;
        top: 50px;
        /* Adjust position as needed */
        right: 8px;
        background-color: #fff;
        padding: 10px;
        border-radius: 5px;
        box-shadow: rgba(0, 0, 0, 0.2) 0px 2px 8px;
        z-index: 1;
        /* Ensure it's above other elements */
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
        }

        to {
            opacity: 0;
        }
    }

    .fade-out {
        animation: fadeOut 0.5s ease forwards;
    }

    div#hamburger {
        background: white;
        padding: 0px 11px;
        border-radius: 2px;
    }



    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f1f1f1;
        width: 142px;
        top: 68px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
        right: -15px;
        border-radius: 3px;

    }

    .dropdown-content a {
        color: black;
        padding: 6px 16px;
        text-decoration: none;
        display: block;
        font-size: 16px;
        font-weight: 500;
    }

    .dropdown-content a:hover {
        background-color: #ddd;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .dropdown:hover .dropbtn {
        background-color: #3e8e41;
    }

    form#createAdminData {
        padding: 10px;
    }

    .title i {
        font-size: 38px;
    }

    .title h2 {
        font-size: 21px;
        font-weight: 600;
        padding-top: 14px;
    }

    .btn-css {
        display: flex;
        flex-direction: row;
    }

    .form-sec img {
        width: 196px;
        margin: 0px auto;
    }

    #submit-btn {
        background: #398512;
        color: white;
        font-size: 20px;
        text-transform: uppercase;
        font-weight: 500;
    }

    form#ManagerRegistration {
        padding: 10px;
    }

    form#create-prod {
        padding: 10px;
    }
    </style>
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
                    <a href="http://localhost/rayon-admin/logout.php" onclick="logout()"><i
                            class="fas fa-sign-out-alt"></i> &nbsp;
                        Signout</a>
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
            <img src="../images/heading-logo.png" alt="">
            <ul type="none">
                <li><i class="fas fa-home"></i><a href="./home.php">Home</a></li>
                <li><i class="fas fa-user"></i> <a href="./admin-create.php">Create Admin</a></li>
                <li><i class="fas fa-user"></i> <a href="./view-admin.php">View Admin</a></li>
                <li><i class="fas fa-user-cog"></i> <a href="./dealer-create.php">Create Dealer</a></li>
                <li><i class="fas fa-eye"></i> <a href="./dealer-view.php">View Dealer</a></li>
                <li><i class="fab fa-salesforce"></i><a href="./view-sales.php">View Sales</a></li>
                <li><i class="fab fa-first-order-alt"></i><a href="./view-orders.php">View Orders</a></li>
                <li><i class="fas fa-eye"></i><a href="./product-create.php">Create Product</a></li>
                <li><i class="fas fa-upload"></i><a href="./product-view.php">View Product</a></li>

            </ul>
        </div>
        <!-- MAIN CONTENT OF THE HOME PAGE -->
        <!-- <div id="content">
            <div class="content-box blu">
                <img src="img/video-posted.png" alt="">
                <h1>Total Video Posted</h1>
                <p id="videoPostedCount"></p>
            </div> -->
    </div>

    <div id="container">
        <!-- Adding the Dealer/Distributor here -->
        <div>
            <img src="" alt="Manager Image" id="managerImage" />
            <p>Dealer Name: <span id="managerName"></span></p>
            <p>Email Address: <span id="managerEmail"></span></p>
            <p>Contact Number: <span id="managerNumber"></span></p>
            <p>Role: <span id="managerRole"></span></p>
            <!-- <p>Admin Name: <span id="managerAdminName"></span></p> -->
        </div>

        <!-- Last Sales -->
        <div id="lastSales">
            <h4>Last Sales</h4>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Buyer Name</th>
                            <th>Buyer Phone Number</th>
                            <th>Buyer Address</th>
                            <th>Total Amount</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody id="salesTableBody"></tbody>
                </table>
            </div>
        </div>

        <!-- Orders Details -->
        <div id="lastOrders">
            <h4>Last Orders</h4>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Status of Order</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody id="ordersTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="../js/navcss.js"></script>
    <script src="../js/script.js"></script>
</body>

</html>