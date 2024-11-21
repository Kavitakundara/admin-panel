let sessionToken = localStorage.getItem('token');

if (!sessionToken) {
    alert('You are not logged in. Please try again');
    window.location.href = '/';
} else {
    let role = localStorage.getItem('role');
    let state;

    if (role == "super-admin") {
        state = "super-admin";
    } else if (role == "admin") {
        state = "admin";
    } else if (role == "dealer") {
        state = "manager";
    } else if (role == "distributor") {
        state = "manager";
    } else {
        alert('Invalid role. Please try again');
        window.location.href = '/';
    }

    async function fetchProfile(url, token) {
        try {
            let response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            let data = await response.json();
            return data;
        } catch (error) {
            console.error('Error fetching profile:', error);
            alert('Error fetching profile. Please try again');
        }
    }

    fetchProfile(`http://localhost:9000/api/${state}/my-profile`, sessionToken);
}
