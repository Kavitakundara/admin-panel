<?php
include '../auth.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile</title>
    <script src="../js/istocken.js"></script>

    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    #container {
        max-width: 600px;
        margin: 50px auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .profile-item {
        margin-bottom: 15px;
    }

    .profile-item label {
        font-weight: bold;
        display: block;
    }

    .profile-item span {
        display: block;
        margin-top: 5px;
    }

    .profile-image {
        max-width: 100px;
        margin-top: 10px;
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
                    <a href="#" onclick="logout()"><i class="fas fa-sign-out-alt"></i> &nbsp; Signout</a>
                </div>
            </div>
        </div>
        <div id="hiddenFeatures">
            <div>Your Account</div>
            <div id="logOut">Logout</div>
        </div>
        <!-- Side Navigation Bar -->
        <div id="sideNav">
            <button class="closeBtn">&times;</button>
            <ul>
                <!-- <li><i class="fas fa-home"></i><a href="./admin-home.php">Home</a></li> -->
                <li><i class="fas fa-user-cog"></i> <a href="manager-create.php">Create Manager</a></li>
                <li><i class="fas fa-eye"></i><a href="./admin-view-mng.php">View Manager</a></li>
                <li><i class="fab fa-salesforce"></i><a href="./admin-mg-sales.php">View Sales</a></li>
                <li><i class="fab fa-first-order-alt"></i><a href="./admin-mg-orders.php">View Orders</a></li>
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
        <h1>My Profile</h1>
        <div class="profile-item">
            <label for="name">Name:</label>
            <span id="name"></span>
        </div>
        <div class="profile-item">
            <label for="email">Email:</label>
            <span id="email"></span>
        </div>
        <div class="profile-item">
            <label for="role">Role:</label>
            <span id="role"></span>
        </div>
        <div class="profile-item">
            <label for="address">Address:</label>
            <span id="address"></span>
        </div>
        <div class="profile-item">
            <label for="adminName">Admin Name:</label>
            <span id="adminName"></span>
        </div>
        <div class="profile-item">
            <label for="image">Profile Image:</label>
            <img id="image" class="profile-image" alt="Profile Image" />
        </div>
    </div>
    <script src="../js/istocken.js"></script>

    <script src="../js/navcss.js"></script>
    <script src="../js/script.js"></script>

</html>