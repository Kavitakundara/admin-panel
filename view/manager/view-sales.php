<?php
// include '../auth.php';
session_start();
include "../conn.php";
$managerId = $_SESSION['user_id']; 

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
<?php include "header.php" ?>

<div id="content" style="overflow-x: auto;">
    <table class="responsive-table">
        <!-- Responsive Table Header Section -->
        <thead class="thead-dark">
          <tr>
                <th scope="col" style="width:50px;">S.No</th>
                <th scope=" col" style="width:100px;">Dealer Name</th>
                <th scope="col" style="width:150px;">Product Name</th>
                <th scope="col" style="width:80px;">Quantity</th>
                <th scope="col" style="width:80px;">Colour</th>
                <th scope="col" style="width:100px;">Motor No.</th>
                <th scope="col" style="width:180px;">Chassis No.</th>
                <th scope="col" style="width:120px;">Controller No.</th>
                <th scope="col" style="width:100px;">Battery No.</th>
                <th scope="col" style="width:100px;">Charger</th>
                <th scope="col" style="width:100px;">RTO Charge</th>
                <th scope="col" style="width:100px;">Other Charge</th>
                <th scope="col" style="width:150px;">Total Price With Charges</th>
                <th scope="col" style="width:150px;">Final Price(GST Include)</th>
                <th scope="col" style="width:100px;">Sale Mode</th>
                <th scope="col" style="width:100px;">Buyer Name</th>
                <th scope="col" style="width:100px;">Buyer Phone Number</th>
                <th scope="col" style="width:100px;">Buyer Address</th>
                <th scope="col" style="width:80px;">Loan HP</th>
                <th scope="col" style="width:80px;">Loan HP Payment</th>
                <th scope="col" style="width:100px;">Nominee Name</th>
                <th scope="col" style="width:100px;">Relation</th>
                <th scope="col" style="width:80px;">Age</th>
                 <th scope="col" style="width:80px;">Date</th>
                <th scope="col" style="width:120px;">Actions</th>
                <th scope="col" style="Display:none;">Actions</th>
            </tr>

        </thead>
        <!-- Responsive Table Body Section -->
        <tbody id="orderData">
            <?php
$sql = "SELECT DISTINCT create_sales.*, create_manager.name AS manager_name, 
        (SELECT id FROM place_order WHERE manager_id = create_sales.manager_id LIMIT 1) AS place_order_id
        FROM create_sales 
        INNER JOIN create_manager ON create_sales.manager_id = create_manager.id
        WHERE create_sales.manager_id = '$managerId'
        ORDER BY create_sales.id DESC LIMIT 100";


        
        $result = $conn->query($sql);

       if ($result->num_rows > 0) {
    $serialNumber = 1; // Initialize serial number
    while ($row = $result->fetch_assoc()) {
       
            echo "<tr onclick=\"printForm(this)\">";
            echo "<td>" . $serialNumber . "</td>"; // Display serial number
            echo "<td>" . htmlspecialchars($row['manager_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['qty']) . "</td>";
            echo "<td>" . htmlspecialchars($row['color']) . "</td>";
            echo "<td>" . htmlspecialchars($row['motor_no']) . "</td>";
            echo "<td>" . htmlspecialchars($row['chassis_no']) . "</td>";
            echo "<td>" . htmlspecialchars($row['controller_no']) . "</td>";
            echo "<td>" . htmlspecialchars($row['battery_no']) . "</td>";
            echo "<td>" . htmlspecialchars($row['charger']) . "</td>";
            echo "<td>" . htmlspecialchars($row['rto']) . "</td>";
            echo "<td>" . htmlspecialchars($row['oth_chrg']) . "</td>";
            echo "<td>" . htmlspecialchars($row['price']) ."</td>";
            echo "<td>" . htmlspecialchars(number_format($row['price'] * 1.05, 2)) . "</td>";
            echo "<td>" . htmlspecialchars($row['sale_mode']) . "</td>";
            echo "<td>" . htmlspecialchars($row['buyer_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['buyer_no']) . "</td>";
            echo "<td>" . htmlspecialchars($row['buyer_add']) . "</td>";
            echo "<td>" . htmlspecialchars($row['loan_hp']) . "</td>"; 
            echo "<td>" . htmlspecialchars($row['loan_hp_payment']) . "</td>"; 
            echo "<td>" . htmlspecialchars($row['nominee_name']) . "</td>"; 
            echo "<td>" . htmlspecialchars($row['relation']) . "</td>"; 
            echo "<td>" . htmlspecialchars($row['age']) . "</td>"; 
            echo "<td>" . htmlspecialchars($row['sale_date']) . "</td>"; 
            echo "<td style='display: none;'>" . htmlspecialchars($row['place_order_id']) . "</td>";

        echo "<td>
                <button class='btn btn-danger btn-sm delete-btn' data-id='" . $row['id'] . "'> <i class=\"fas fa-trash\"></i></button>
                <button class='btn btn-primary btn-sm print-btn'><i class=\"fas fa-print\"></i></button>
              </td>";
        echo "</tr>";

        $serialNumber++; // Increment serial number
    }
} else {
        echo "<tr><td colspan='22'>No records found.</td></tr>"; // Updated colspan to match the total number of columns
    }
    $conn->close();
    ?>
        </tbody>


    </table>
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
                            body: delete_id = $ {
                                deleteId
                            },
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
    // Calculate GST within this scope
    const totalPrice = parseFloat(rowData[12]); // Product Price
    const gstAmount = totalPrice * 0.05;
    const cgst = gstAmount / 2;
    const sgst = gstAmount / 2;
    const totalTaxAmount = cgst + sgst;
}
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const printButtons = document.querySelectorAll('.print-btn');

    printButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation(); // Prevent row click event from interfering
            const row = this.closest('tr');
            const rowData = Array.from(row.children).map(cell => cell.innerText);
            
            // Calculate GST values here as well since we need them in the print output
            const totalPrice = parseFloat(rowData[12]);
            const gstAmount = totalPrice * 0.05;
            const cgst = gstAmount / 2;
            const sgst = gstAmount / 2;
            const totalTaxAmount = cgst + sgst;

            const printContent = `
<html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tax Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .invoice-container {
            width: 800px;
            background: white;
            padding: 20px;
            border: 1px solid black;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        .header-table {
            border: 1px solid black;
        }

        .header-table td {
            border: none;
            padding: 8px;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <h2 class="center">TAX INVOICE</h2>
        <table class="header-table">
            <tr>
                <td><strong>Rayon Engineers</strong><br>KH. NO-123 2-17 AND 114/1 2-12, <br>VILLAGE MAKSUDABAD, NEAR PLOE NO-5, NAJAFGARH, <br>New Delhi, South West Delhi, Delhi, 110043<br>GSTIN: 07CPAPP3340B1ZE<br>CONTACT: 7053494589</td>
                <td>
                    <strong>Invoice No:</strong> DS/02-25/${rowData[0]}<br>
                    <strong>Dated:</strong> ${rowData[23]}<br>
                    <strong>Delivery Mode:</strong>Part Payment<br>
                    <strong>Mode of Payment:</strong> ${rowData[14]}<br>
                    <strong>Buyer’s Order No:</strong>  ${rowData[24]}
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <th>Consignee (Ship To)</th>
                <th>Buyer (Bill To)</th>
            </tr>
            <tr>
                <td><strong>${rowData[15]}</strong><br>${rowData[17]}<br>Contact: ${rowData[16]}</td>
                <td><strong>${rowData[15]}</strong><br>${rowData[17]}<br>Contact: ${rowData[16]}</td>
            </tr>
        </table>
        <table>
            <tr>
                <th>S.No</th>
                <th>Product Name</th>
                <th>HSN/SAC</th>
                <th>Quantity</th>
                <th>Rate</th>
                <th>Amount</th>
            </tr>
            <tr>
                <td>1</td>
                <td>${rowData[2]}</td>
                <td>87039010</td>
                <td>${rowData[3]}</td>
                <td>5%</td>
                <td>${rowData[12]}</td>
            </tr>
            <tr>
                <td colspan="5" class="bold">CGST (2.5%)</td>
                <td>${cgst}</td>
            </tr>
            <tr>
                <td colspan="5" class="bold">SGST (2.5%)</td>
                <td>${sgst}</td>
            </tr>
            <tr>
                <td colspan="5" class="bold">Total</td>
                <td>${rowData[13]}</td>
            </tr>
        </table>
        <p class="bold">Amount in Words: INR Thirty Thousand Rupees Only</p>
        <table>
            <tr>
                <th>HSN/SAC</th>
                <th>Taxable Value</th>
                <th>CGST (2.5%)</th>
                <th>SGST (2.5%)</th>
                <th>Total Tax Amount</th>
            </tr>
            <tr>
                <td>87039010</td>
                <td>${rowData[12]}</td>
                <td>${cgst}</td>
                <td>${sgst}</td>
                <td>${totalTaxAmount}</td>
            </tr>
        </table>
        <p class="bold">Tax Amount (in words): INR Four Thousand Five Hundred Seventy-Six and Twenty-Seven Paisa</p>
        <table>
            <tr>
                <th>Bank Information</th>
                <th>RAYON ENGINEERS</th>
            </tr>
            <tr>
                <td>Bank Name: BANK OF BARODA<br>Bank Account No: 32130200000421<br>Bank IFSC Code:
                    BARB0NAJDEL<br>Account Holder's Name: Rayon Engineers</td>
                <td class="center"><br><br>Authorised Signatory</td>
            </tr>
        </table>
    </div>
</body>


            `;

            const printWindow = window.open('', '_blank');
            printWindow.document.open();
            printWindow.document.write(printContent);
            printWindow.document.close();
            printWindow.print();
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