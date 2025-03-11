<?php
include '../auth.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" />
    <script src="../js/istocken.js"></script>
    <title>Your Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="../css/style.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" /> -->
    <script src="../js/istocken.js"></script>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
    body {
        font-family: Arial, sans-serif;
    }

    #container {
        max-width: 600px;
        margin: 50px auto;
        border: 1px solid #ccc;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .profile-item {
        margin-bottom: 15px;
        display: flex;
        border-bottom: 1px solid #b1d3db73;
        justify-content: space-between;
        padding-bottom: 10px;
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
        max-width: 186px;
        margin-top: 17px;
    }

    .hidden {
        display: none;
    }

    #edit-form {
        margin-top: 20px;
    }

    #edit-form input,
    #edit-form button {
        display: block;
        margin-top: 10px;
    }


    .form_div {
        position: absolute;
        top: 57%;
        left: 50%;
        width: 60%;
        transform: translate(-50%, -50%);
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 10px;
    }



    @media (max-width: 768px) {
        .form_div {
            width: 100%;
        }

        .form_div {
            position: absolute;
            top: 50%;
            left: 50%;
            /* width: 60%; */
            transform: translate(-50%, -50%);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 10px;
            z-index: -1;
        }
    }

    .image-size {
        width: 200px;
        height: 100px;
        border: 1px solid black;
    }

    .rounded-1 {
        width: 200px;
        height: 200px;
        border: 2px solid black;
        border-radius: 50%;
    }

    .btn-style {
        padding: 8px 40px;
        margin-left: 20px;
    }

    .responsive_1 {}

    .res_roll {
        display: none;

    }

    .res_em {
        margin-top: -72px;
    }

    .res_rol {
        margin-top: -72px;
    }

    .choose_p {
        font-size: 15px;
    }




    @media (max-width: 776px) {
        .flex_dire {
            display: flex;
            flex-direction: column-reverse;
        }

        .res_2 {
            margin-top: 256px;
            z-index: 999;
        }

        .responsive_1 {
            margin-top: 104px;
        }

        .res_roll {
            display: block;
            margin-top: -42px;
        }

        .res_img {
            width: 150px;
            height: 150px;
            border-radius: 50% !important;
            margin-left: 90px;
        }

        .res_img img {
            width: 62%;
            border-radius: 50% !important;
            margin-left: 23px;
            margin-top: 39px;
        }

        .my_hide {
            display: none !important;
        }

        .choose_p {
            margin-left: 102px;
            font-size: 12px;
        }

        .mar_t {
            margin-top: -90px;
        }

        .res_em {
            margin-top: -42px;
        }

        .res_rol {
            margin-top: -43px;
        }

        .btn-style {
            margin-bottom: 56px;
            margin-top: -30px;
            margin-left: 112px;
        }

        .pass_chnage {
            color: black;
            padding: 5px 10px;
            border-radius: 8px;
        }
    }



    /* ------------ nevbar--------end */
    </style>
</head>

<body>
    <div id="container">
        <h1>My Profile</h1>
        <button id="edit-button">Edit</button>
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
            <label for="image">Profile Image:</label>
            <img id="image" class="profile-image" alt="Profile Image" />
        </div>

        <form id="edit-form" class="hidden">
            <label for="edit-name">Name:</label>
            <input type="text" id="edit-name" name="name" />

            <label for="edit-email">Email:</label>
            <input type="email" id="edit-email" name="email" />

            <label for="edit-image">Choose a Profile Image</label>
            <input type="file" id="edit-image" name="image" />

            <button type="button" onclick="updateProfile()">Save</button>
        </form>
    </div>

    <!-- <script>
        async function fetchProfile() {
            try {
                let response = await fetch("https://movik.onrender.com/api/super-admin/my-profile", {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${sessionToken}`
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                let data = await response.json();
                document.getElementById('name').textContent = data.data.name || "N/A";
                document.getElementById('email').textContent = data.data.email || "N/A";
                document.getElementById('role').textContent = data.data.role || "N/A";
                document.getElementById('image').src = `https://movik.onrender.com/${data.data.image}` || "https://th.bing.com/th/id/OIP.kUxY3nn_Tig7j9T92rsFJQHaF6?w=860&h=686&rs=1&pid=ImgDetMain";

                // Pre-fill the form with current data
                document.getElementById('edit-name').value = data.data.name || "";
                document.getElementById('edit-email').value = data.data.email || "";


            } catch (error) {
                console.error('Error fetching profile:', error);
                alert('Error fetching profile. Please try again');
            }
        }

        async function updateProfile() {
            const updatedData = {
                name: document.getElementById('edit-Name').value,
                email: document.getElementById('edit-Email').value
            };

            try {
                let response = await fetch("https://movik.onrender.com/api/update-my-profile", {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${sessionToken}`
                    },
                    body: JSON.stringify(updatedData)
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                let data = await response.json();
                console.log(data);
                alert('Profile updated successfully');
                fetchProfile();
            } catch (error) {
                console.error('Error updating profile:', error);
                alert('Error updating profile. Please try again');
            }
        }

        async function updatePassword(event) {
            event.preventDefault();
            const newPassword = document.getElementById('new-password').value;
            const confirmPassword = document.getElementById('confirm-password').value;

            if (newPassword !== confirmPassword) {
                alert("Passwords do not match");
                return;
            }

            const updatedPasswordData = {
                password: newPassword
            };

            try {
                let response = await fetch("https://movik.onrender.com/api/update-password", {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${sessionToken}`
                    },
                    body: JSON.stringify(updatedPasswordData)
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                let data = await response.json();
                console.log(data);
                alert('Password updated successfully');
                document.getElementById('passwordModal').classList.remove('show');
                document.body.classList.remove('modal-open');
                document.querySelector('.modal-backdrop').remove();
            } catch (error) {
                console.error('Error updating password:', error);
                alert('Error updating password. Please try again');
            }
        }

        document.getElementById('passwordForm').addEventListener('submit', updatePassword);
        document.getElementById('editForm').addEventListener('submit', updateProfile);

        fetchProfile();
    </script> -->

    <script src="../js/navcss.js"></script>
    <script src="../js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>