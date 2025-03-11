<?php
session_start();
include '../conn.php';
include  "header.php"; 

// Function to delete a record
if (isset($_POST['delete_id'])) {
    $deleteId = intval($_POST['delete_id']);
    $deleteQuery = "DELETE FROM create_products WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $deleteId);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Record deleted successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error deleting record"]);
    }
    $stmt->close();
    $conn->close();
    exit;
}
?>

<div id="content" style="overflow-x: auto;">
    <div class="container mt-3" >
        <input type="text" id="filterInput" class="form-control" placeholder="Search any field..."
            onkeyup="filterTable()">
    </div>
    <table class="responsive-table ">        <!-- Responsive Table Header Section -->
        <thead class="thead-dark">
            <tr>
                <th scope="col"style="width:80px;" >Dealer Id</th>
                <th scope="col" style="width: 100px;">Product Name</th>
                <th scope="col" style="width: 100px;">Inventory Count</th>
                <th scope="col" style="width: 100px;">Color</th>
                <th scope="col" style="width: 180px;">Chassis No.</th>
                <th scope="col" style="width: 100px;">Motor No.</th>
                <th scope="col" style="width: 100px;">Controller No.</th>
                <!-- <th scope="col">Price</th> -->
                <th scope="col" style="width: 150px;">Date</th>
                <th scope="col" style="width: 150px;">Battery No.</th>
                <th scope="col" style="width: 100px;">Charger No.</th>
                <th scope="col" style="width: 100px;">HSN Code</th>
                <th scope="col" style="width: 100px;">Actions</th>
            </tr>
        </thead>
        <!-- Responsive Table Body Section -->
        <tbody id="productData">
            <?php
                    // Fetching data from create_products table
            
                    $sql = "
                    SELECT 
                        p.id AS id,
                        p.manager_id AS mng_id,
                        p.name AS name,
                        p.inventory_count,
                        p.color,
                        cs.chassis_no,
                        p.motor_no,
                        p.controller_no,
                        p.date,
                        p.battery_no,
                        p.charger_no,
                        p.hsn_code,
                        GROUP_CONCAT(c.chassis_no SEPARATOR ', ') AS chassis_numbers,
                        IFNULL(SUM(cs.qty), 0) AS sold_quantity,
                        CASE 
                            WHEN p.inventory_count > IFNULL(SUM(cs.qty), 0) 
                            THEN p.inventory_count - IFNULL(SUM(cs.qty), 0)
                            ELSE 0
                        END AS remaining_inventory
                    FROM u138080682_super_admin.create_products p
                    LEFT JOIN u138080682_super_admin.product_chassis_numbers c ON p.id = c.product_id
                    LEFT JOIN create_sales cs ON c.chassis_no = cs.chassis_no
                    GROUP BY p.id";
                
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                    
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['mng_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row ['name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['remaining_inventory']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['color']) . "</td>";
                            echo "<td> ";
                            $chassisNumbers = explode(',', $row['chassis_numbers']); // Split the string into an array
                            foreach ($chassisNumbers as $number) {
                                echo htmlspecialchars($number) . "<br>"; // Output each number on a new line
                            }
                            echo "</td>";
                            
                            echo "<td>" . htmlspecialchars($row['motor_no']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['controller_no']) . "</td>";
                            // echo "<td>" . htmlspecialchars($row['price']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['battery_no']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['charger_no']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['hsn_code']) . "</td>";
                            // echo "<td><img src='uploads/" . htmlspecialchars($row['img']) . "' width='100' height='100'></td>";
                            echo "<td>
                            <button class='btn btn-danger btn-sm delete-btn' data-id='" . $row['id'] . "'>
                                <i class=\"fas fa-trash\"></i>
                            </button>
                        </td>";
                    
                            echo "</tr>";
                        }
                    } else {    
                    echo "<tr><td colspan='12'>No records found.</td></tr>";
                }
                $conn->close();
                ?>
        </tbody>

</div>
</div>
<script>
function filterTable() {
    const input = document.getElementById('filterInput');
    const filter = input.value.toLowerCase();
    const rows = document.querySelectorAll('#productData tr');

    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        const matches = Array.from(cells).some(cell => cell.textContent.toLowerCase().includes(filter));
        row.style.display = matches ? '' : 'none';
    });
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const deleteId = this.getAttribute('data-id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `delete_id=${deleteId}`,
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire(
                                    'Deleted!',
                                    data.message,
                                    'success'
                                ).then(() => {
                                    // Find the table row and remove it
                                    const row = button.closest('tr');
                                    row.remove();
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    data.message,
                                    'error'
                                );
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error!', 'Failed to delete the record.',
                                'error');
                        });
                }
            });
        });
    });
});
</script>

<script src="../js/script.js"></script>
<script src="../js/navcss.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>