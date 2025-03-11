<?php
session_start();
include "../conn.php"; // Replace with your DB connection file

// Function to delete a record
if (isset($_POST['delete_id'])) {
    $deleteId = intval($_POST['delete_id']);
    $deleteQuery = "DELETE FROM create_sales WHERE id = ?";
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
<?php include 'header.php'; ?>

<div id="content" style="overflow-x: auto;">
    <table class="responsive-table">
        <!-- Responsive Table Header Section -->
        <thead class="thead-dark">
            <tr>
                <th scope="col" style="width:100px;">Product Name</th>
                 <th scope="col" style="width:100px;">Manager Name</th>
                <th scope="col" style="width:100px;">Model</th>
                <th scope="col" style="width:80px;">Quantity</th>
                <th scope="col" style="width:80px;">Colour</th>
                <th scope="col" style="width:80px;">Motor No.</th>
                <th scope="col" style="width:150px;">Chassis No.</th>
                <th scope="col" style="width:100px;">Controller No.</th>
                <th scope="col" style="width:100px;">Battery No.</th>
                <th scope="col" style="width:100px;">Charger</th>
                <th scope="col" style="width:80px;">RTO Charge</th>
                <th scope="col" style="width:80px;">Other Charge</th>
                <th scope="col" style="width:80px;">Total Price</th>
                <th scope="col" style="width:80px;">Sale Mode</th>
                <th scope="col" style="width:100px;">Buyer Name</th>
                <th scope="col" style="width:100px;">Buyer Phone Number</th>
                <th scope="col" style="width:100px;">Buyer Address</th>
                <th scope="col" style="width:80px;">Loan HP</th>
                <th scope="col" style="width:80px;">Loan HP Payment</th>
                <th scope="col" style="width:100px;">Nominee Name</th>
                <th scope="col" style="width:100px;">Relation</th>
                <th scope="col" style="width:80px;">Age</th>
                <th scope="col" style="width:150px;">Actions</th>
            </tr>

        </thead>
        <!-- Responsive Table Body Section -->
        <tbody id="orderData">
            <?php
$sql = "SELECT create_sales.*, create_manager.name 
        FROM create_sales 
        JOIN create_manager ON create_sales.manager_id = create_manager.id
        WHERE create_manager.created_by = '$admin_username'
        ORDER BY create_sales.id DESC";

        
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<tr onclick=\"printForm(this)\">";
            echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['model']) . "</td>";
            echo "<td>" . htmlspecialchars($row['qty']) . "</td>";
            echo "<td>" . htmlspecialchars($row['color']) . "</td>";
            echo "<td>" . htmlspecialchars($row['motor_no']) . "</td>";
            echo "<td>" . htmlspecialchars($row['chassis_no']) . "</td>";
            echo "<td>" . htmlspecialchars($row['controller_no']) . "</td>";
            echo "<td>" . htmlspecialchars($row['battery_no']) . "</td>";
            echo "<td>" . htmlspecialchars($row['charger']) . "</td>";
            echo "<td>" . htmlspecialchars($row['rto']) . "</td>";
            echo "<td>" . htmlspecialchars($row['oth_chrg']) . "</td>";
            echo "<td>" . htmlspecialchars(number_format($row['price'] / 1.05, 2)) . "</td>";
            echo "<td>" . htmlspecialchars($row['sale_mode']) . "</td>";
            echo "<td>" . htmlspecialchars($row['buyer_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['buyer_no']) . "</td>";
            echo "<td>" . htmlspecialchars($row['buyer_add']) . "</td>";
            echo "<td>" . htmlspecialchars($row['loan_hp']) . "</td>"; // Loan HP
            echo "<td>" . htmlspecialchars($row['loan_hp_payment']) . "</td>"; // Loan HP Payment
            echo "<td>" . htmlspecialchars($row['nominee_name']) . "</td>"; // Nominee Name
            echo "<td>" . htmlspecialchars($row['relation']) . "</td>"; // Relation
            echo "<td>" . htmlspecialchars($row['age']) . "</td>"; // Age
            echo "<td>
                    <button class='btn btn-danger btn-sm delete-btn' data-id='" . $row['id'] . "'>Delete</button>
                    <button class='btn btn-primary btn-sm print-btn'><i class=\"fas fa-print\"></i></button>
                  </td>";
                      
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='22'>No records found.</td></tr>"; // Updated colspan to match the total number of columns
    }
    $conn->close();
    ?>
        </tbody>


    </table>
</div>
</div>


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
                                    location
                                        .reload(); // Reload the page
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
<script>
function printForm(row) {
    const rowData = Array.from(row.children).map(cell => cell.innerText);
    const printContent = `
        <p><strong>Product Name:</strong> ${rowData[0]}</p>
        <p><strong>Model:</strong> ${rowData[1]}</p>
        <p><strong>Quantity:</strong> ${rowData[2]}</p>
        <p><strong>Color:</strong> ${rowData[3]}</p>
        <p><strong>Motor No.:</strong> ${rowData[4]}</p>
        <p><strong>Chassis No.:</strong> ${rowData[5]}</p>
        <p><strong>Controller No.:</strong> ${rowData[6]}</p>
        <p><strong>Battery No.:</strong> ${rowData[7]}</p>
        <p><strong>Charger:</strong> ${rowData[8]}</p>
        <p><strong>RTO Charge:</strong> ${rowData[9]}</p>
        <p><strong>Other Charge:</strong> ${rowData[10]}</p>
        <p><strong>Total Price:</strong> ${rowData[11]}</p>
        <p><strong>Sale Mode:</strong> ${rowData[12]}</p>
        <p><strong>Buyer Name:</strong> ${rowData[13]}</p>
        <p><strong>Buyer Phone Number:</strong> ${rowData[14]}</p>
        <p><strong>Buyer Address:</strong> ${rowData[15]}</p>
        <p><strong>Loan HP:</strong> ${rowData[16]}</p>
        <p><strong>Loan HP Payment:</strong> ${rowData[17]}</p>
        <p><strong>Nominee Name:</strong> ${rowData[18]}</p>
        <p><strong>Relation:</strong> ${rowData[19]}</p>
        <p><strong>Age:</strong> ${rowData[20]}</p>
         <button onclick="printForm()">Print</button>
    `;
    document.getElementById('printContent').innerHTML = printContent;
    document.getElementById('printSection').style.display = 'block';
}
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const printButtons = document.querySelectorAll('.print-btn');

    printButtons.forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('tr');
            const rowData = Array.from(row.children).map(cell => cell.innerText);
            const printContent = `
            <html>
            <head>
                <title>Print Sale Details</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
                    th { background-color: #f4f4f4; }
                </style>
            </head>
            <body>
                <h3>Sale Details</h3>
                <table>
                    <tr><th>Product Name</th><td>${rowData[0]}</td></tr>
                    <tr><th>Model</th><td>${rowData[1]}</td></tr>
                    <tr><th>Quantity</th><td>${rowData[2]}</td></tr>
                    <tr><th>Color</th><td>${rowData[3]}</td></tr>
                    <tr><th>Motor No.</th><td>${rowData[4]}</td></tr>
                    <tr><th>Chassis No.</th><td>${rowData[5]}</td></tr>
                    <tr><th>Controller No.</th><td>${rowData[6]}</td></tr>
                    <tr><th>Battery No.</th><td>${rowData[7]}</td></tr>
                    <tr><th>Charger</th><td>${rowData[8]}</td></tr>
                    <tr><th>RTO Charge</th><td>${rowData[9]}</td></tr>
                    <tr><th>Other Charge</th><td>${rowData[10]}</td></tr>
                    <tr><th>Total Price</th><td>${rowData[11]}</td></tr>
                    <tr><th>Sale Mode</th><td>${rowData[12]}</td></tr>
                    <tr><th>Buyer Name</th><td>${rowData[13]}</td></tr>
                    <tr><th>Buyer Phone</th><td>${rowData[14]}</td></tr>
                    <tr><th>Buyer Address</th><td>${rowData[15]}</td></tr>
                    <tr><th>Loan HP</th><td>${rowData[16]}</td></tr>
                    <tr><th>Loan HP Payment</th><td>${rowData[17]}</td></tr>
                    <tr><th>Nominee Name</th><td>${rowData[18]}</td></tr>
                    <tr><th>Relation</th><td>${rowData[19]}</td></tr>
                    <tr><th>Age</th><td>${rowData[20]}</td></tr>
                </table>
            </body>
            </html>
            `;

            const printWindow = window.open('', '_blank');
            printWindow.document.open();
            printWindow.document.write(printContent);
            printWindow.document.close();

            // Add this line to automatically invoke the print dialog
            printWindow.print();
        });
    });
});
</script>
<script>
document.getElementById("searchBox").addEventListener("input", function() {
    const searchValue = this.value.toLowerCase();
    const tableRows = document.querySelectorAll("tbody tr");

    tableRows.forEach(row => {
        const rowText = row.textContent.toLowerCase();
        if (rowText.includes(searchValue)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
});
</script>
<script src="../js/script.js"></script>
<script src="../js/navcss.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>