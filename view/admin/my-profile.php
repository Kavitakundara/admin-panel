<?php include 'header.php'; ?>

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

    <script>
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
            document.getElementById('image').src = `https://movik.onrender.com/${data.data.image}` ||
                "https://th.bing.com/th/id/OIP.kUxY3nn_Tig7j9T92rsFJQHaF6?w=860&h=686&rs=1&pid=ImgDetMain";

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
    </script>

    <script src="../js/navcss.js"></script>
    <script src="../js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>