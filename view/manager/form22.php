<?php
session_start();
include "../conn.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access!");
}

$managerId = $_SESSION['user_id'];
$records_per_page = 1;
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $records_per_page;

// Fetch records with strict LIMIT and OFFSET
$sql = "SELECT * FROM create_sales WHERE manager_id = ? ORDER BY id DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $managerId, $records_per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();

// Get total records for pagination
$total_stmt = $conn->prepare("SELECT COUNT(*) AS total FROM create_sales WHERE manager_id = ?");
$total_stmt->bind_param("i", $managerId);
$total_stmt->execute();
$total_result = $total_stmt->get_result();
$total_records = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $records_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .heading {
            text-align: center;
            font-size: 24px;
            color: #d33;
        }
        .address {
            text-align: center;
            font-size: 14px;
        }
        .hor-line {
            border-top: 2px solid #d33;
            margin: 10px 0;
        }
        .sub-head {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            margin: 10px 0;
        }
        .form-details, .certification {
            text-align: center;
            margin: 20px 0;
        }
       .signature-img {
    width: 200px;
    margin: 15px 0px;
    display: block;
}
    </style>
</head>
<body>
    <?php include "header.php"; ?>

    <div id="content" class="container ma_1">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="form-container" id="form-<?php echo $row['id']; ?>">
                    <header>
                        <h1 class="heading">RAYON ENGINEERS</h1>
                        <p class="address">KH. NO-123 2-17 AND 114/1 2-12, VILLAGE MAKSUDABAD, NEAR PLOT NO-5, NAJAFGARH,<br>
                            New Delhi, South West Delhi, Delhi, 110043
                            Email: india.rayonengineers@gmail.com<br>
                            Mobile: +91-8595686869 | GST No: 07ABCFR8497H1ZM
                        </p>
                    </header>
                    <div class="hor-line"></div>
                    <div class="sub-head">
                        <p>Ref No.......</p>
                        <p>Date: <?php echo date('d-m-Y'); ?></p>
                    </div>
                    <section class="form-details">
                        <h2>FORM-22</h2>
                        <p>(See Rules 47(g), 115, 124(2), 126(A), and 127(1), 127(2))</p>
                        <p><em>(To be issued by the manufacturers)</em></p>
                        <p>INITIAL CERTIFICATE OF COMPLIANCE WITH POLLUTION STANDARD, SAFETY STANDARDS OF COMPONENTS AND ROAD WORTHINESS</p>
                    </section>
                    <section class="certification">
                        <p>
                            Certified that <strong><?php echo htmlspecialchars($row['product_name']); ?> (<?php echo htmlspecialchars($row['color']); ?>)</strong>
                            (brand name of vehicle) bearing <strong>CHASSIS NO- <?php echo htmlspecialchars($row['chassis_no']); ?></strong>
                            and Engine or Motor No. <strong><?php echo htmlspecialchars($row['motor_no']); ?></strong>
                            and Controller No. <strong><?php echo htmlspecialchars($row['controller_no']); ?></strong>,
                        </p>
                        <p>Complies with the provisions of the Motors Vehicles Act 1988 and the rules made thereunder.</p>
                    </section>
                    <footer>
                        <p>FOR RAYON ENGINEERS</p>
                        <div class="signature">
                            <img src="../images/signature.png" alt="Manufacturer's Signature" class="signature-img">
                            <p>Signature of the Manufacturer</p>
                            <p>Form 22 shall be issued with the signature of the manufacturer duly printed in the form itself</p>
                        </div>
                    </footer>
                    <button class="btn btn-primary mt-3" onclick="printForm('form-<?php echo $row['id']; ?>')">Print</button>
                </div>
            <?php endwhile; ?>

            <!-- Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center mt-4">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">Previous</a>
                        </li>
                    <?php endif; ?>

                    <?php 
                    // Show limited pagination links (current page ± 2)
                    $start = max(1, $page - 2);
                    $end = min($total_pages, $page + 2);
                    for ($i = $start; $i <= $end; $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">Next</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php else: ?>
            <div class="alert alert-info text-center" role="alert">
                No records found.
            </div>
        <?php endif; ?>
    </div>

    <script>
    function printForm(formId) {
        const formContent = document.getElementById(formId).cloneNode(true);
        formContent.querySelector('.btn').remove();

        const printWindow = window.open('', '', 'height=800,width=600');
        printWindow.document.write(`
            <html>
            <head>
                <title>Print Form</title>
                <style>
                    @media print {
                        body {
                            font-family: Arial, sans-serif;
                            line-height: 1.6;
                            color: #000;
                            margin: 20mm;
                        }
                        .form-container {
                            max-width: 800px;
                            margin: 0 auto;
                        }
                        .heading { color: #d33; font-size: 24px; text-align: center; }
                        .address { text-align: center; font-size: 14px; }
                        .hor-line { border-top: 2px solid #d33; margin: 10px 0; }
                        .sub-head { display: flex; justify-content: space-between; font-size: 12px; margin: 10px 0; }
                        .form-details, .certification { text-align: center; margin: 20px 0; }
                        .signature-img { width: 200px; margin: 10px auto; display: block; }
                    }
                </style>
            </head>
            <body>
                <div class="form-container">${formContent.innerHTML}</div>
            </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
        printWindow.close();
    }
    </script>

    <script src="../js/navcss.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$total_stmt->close();
$conn->close();
?>