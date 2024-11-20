<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/style.css" />
    <title>Home</title>
</head>

<body style="background-color: #2c323a">
    <div id="preloader">
        <div id="loader">
            <img src="https://www.rayonengineers.com/assets/img/logo.png" alt="Loading..." />
        </div>
    </div>

    <!-- Main Content -->
    <div id="main-content" style="display: none">
        <!-- Your main content goes here -->
        <div class="form-sec">
            <img src="https://www.rayonengineers.com/assets/img/logo.png" alt="" class="login-img" />
            <form class="container-2" id="login-form" novalidate>
                <div>
                    <h3>Welcome Back</h3>
                </div>
                <div>
                    <label for="username">Username or Email</label>
                    <input id="username" type="text" required minlength="4" />
                    <span class="error" aria-live="polite"></span>
                </div>
                <div>
                    <label for="password">Password</label>
                    <input id="password" type="password" required minlength="8" />
                    <!-- <a href="#">Forgot your password?</a> -->
                </div>
                <button type="submit" id="submit-btn">Login</button>
                <span aria-live="assertive" id="js-loadingMsg" class="sr-only">
                    <!-- Use JavaScript to inject the loading message -->
                </span>
            </form>
        </div>
    </div>

    <script>
    // Function to simulate loading time and hide preloader
    window.addEventListener("load", function() {
        setTimeout(function() {
            document.getElementById("preloader").style.display = "none";
            document.getElementById("main-content").style.display = "block";
        }, 2000); // Simulate a 2-second loading time
    });

    function loginpanel() {
        const username = document.getElementById("username").value;
        const password = document.getElementById("password").value;

        // Hardcoded credentials (you can change these as needed)
        const users = [{
                username: "superadmin",
                password: "super123",
                role: "super-admin"
            },
            {
                username: "admin",
                password: "admin123",
                role: "admin"
            },
            {
                username: "manager",
                password: "manager123",
                role: "dealer"
            }, // or "distributor"
        ];

        // Check if user credentials are valid
        const user = users.find(user => user.username === username && user.password === password);

        if (!user) {
            alert("Wrong Credentials");
            return;
        }

        // Simulate setting a token and role in local storage
        localStorage.setItem("token", "dummy-token"); // Simulate a token
        localStorage.setItem("role", user.role);

        // Redirect based on role
        switch (user.role) {
            case "super-admin":
                window.location.href = "./super-admin/home.html";
                break;
            case "admin":
                window.location.href = "./admin/manager-create.html";
                break;
            case "dealer":
            case "distributor":
                window.location.href = "./manager/order-page.html";
                break;
            default:
                alert("Unknown role. Please contact support.");
        }
    }

    document.getElementById("login-form").addEventListener("submit", function(e) {
        e.preventDefault();
        loginpanel(); // Call the manual login function
    });
    </script>

</body>

</html>