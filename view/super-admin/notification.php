<?php
include 'header.php';
include "../conn.php"; // Include database connection

// Mark all unread notifications as read
$update_sql = "UPDATE notifications SET is_read = 1 WHERE is_read = 0";
$conn->query($update_sql);

// Fetch all notifications
$sql = "SELECT * FROM notifications ORDER BY id DESC";
$result = $conn->query($sql);
?>

<div id="content">
    <div class="container my-3">
        <input type="text" id="searchBox" class="form-control" placeholder="Search for dealers...">
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Sr Number</th>
                <th scope="col">Message</th>
                <th scope="col">Dealer Id / Name</th>
                <th scope="col">Date</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if ($result->num_rows > 0) {
                $sr = 1; // Serial Number
                while ($row = $result->fetch_assoc()) {
                    // Highlight unread notifications
                    $highlightClass = ($row['is_read'] == 0) ? "table-warning" : ""; 
                    
                    echo "<tr class='$highlightClass'>";
                    echo "<td>" . $sr . "</td>";
                    echo "<td>" . htmlspecialchars($row['message']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['manager_id']) . " / " . htmlspecialchars($row['dealername']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                    echo "</tr>";
                    $sr++;
                }
            } else {
                echo "<tr><td colspan='4'>No notifications found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
