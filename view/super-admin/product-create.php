<?php
include '../auth.php';
include  "header.php"; 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include '../conn.php'; 

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    $file = $_FILES['file'];
    $targetDir = "uploads/";
    $fileName = time() . '_' . basename($file['name']);
    $targetFilePath = $targetDir . $fileName;

    // Allowed file types
    $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
    $allowedTypes = ['csv']; // Use .csv instead of .xls or .xlsx

    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
            // Open and read the CSV file
            if (($handle = fopen($targetFilePath, 'r')) !== false) {
                $isHeader = true;
                $productId = null;

                while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                    if ($isHeader) {
                        // Skip the header row
                        $isHeader = false;
                        continue;
                    }

                    // Extract data from CSV row
                    $chassisNo = $conn->real_escape_string(trim($row[0])); // Chassis No
                    $name = $conn->real_escape_string(trim($row[1]));
                    $inventoryCount = intval(trim($row[2]));
                    $color = $conn->real_escape_string(trim($row[3]));
                    $motorNo = $conn->real_escape_string(trim($row[4]));
                    $controllerNo = $conn->real_escape_string(trim($row[5]));
                    // $price = floatval(trim($row[6]));
                    $date = date('Y-m-d', strtotime(trim($row[6]))); // Format the date
                    $batteryNo = $conn->real_escape_string(trim($row[7]));
                    $chargerNo = $conn->real_escape_string(trim($row[8]));
                    $hsnCode = $conn->real_escape_string(trim($row[9]));
                    $mng_id = $conn->real_escape_string(trim($row[10]));
                    $order_id = $conn->real_escape_string(trim($row[11]));

                    // Insert product details (only once for the product)
                    if (!empty($name) && $productId === null) {
                        $productSql = "INSERT INTO create_products 
                            (name, inventory_count, color, motor_no, controller_no,  date, battery_no, charger_no, hsn_code, manager_id, order_id) 
                            VALUES 
                            ('$name', '$inventoryCount', '$color', '$motorNo', '$controllerNo',  '$date', '$batteryNo', '$chargerNo', '$hsnCode', '$mng_id', '$order_id')";

                        if ($conn->query($productSql) === TRUE) {
                            $productId = $conn->insert_id; // Get the last inserted product ID
                        } else {
                            die("Error inserting product: " . $conn->error);
                        }
                    }

                    // Insert each chassis number into the `product_chassis_numbers` table
                    if (!empty($chassisNo) && isset($productId)) {
                        $chassisSql = "INSERT INTO product_chassis_numbers (product_id, chassis_no) 
                                       VALUES ($productId, '$chassisNo')";

                        if (!$conn->query($chassisSql)) {
                            echo "Error inserting chassis number: " . $conn->error;
                        }
                    }
                }
                fclose($handle);

                echo "<script>alert('CSV data successfully uploaded and saved!');</script>";

            } else {
                echo "<script>alert('Failed to open the CSV file.');</script>";

            }
        } else {
            echo "";
            echo "<script>alert('Error uploading file.');</script>";

        }
    } else {
        echo "<script>alert('Invalid file type. Only .csv is allowed.');</script>";

    }
}
?>



<!-- SweetAlert for success/error notification -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Excel File</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <script>
    <?php if ($alertType): ?>
    Swal.fire({
        icon: '<?php echo $alertType; ?>',
        title: '<?php echo ucfirst($alertType); ?>',
        text: '<?php echo $alertMessage; ?>'
    });
    <?php endif; ?>
    </script>


    <style>
    .input__box input[type="file"] {
        display: block;
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        cursor: pointer;
        background-color: #f9f9f9;
    }

    @media (max-width: 667px) {
        .input__box input[type="file"] {
            display: flex;
            font-size: 16px;
            padding: 10px;
            justify-content: center;
        }
    }
    </style>

    <div id="content">
        <div class="container mt-5 form-css">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title"><i class="fas fa-cart-arrow-down"></i>
                        <h2>Create Product!!</h2>
                    </div>
                    <form id="create-prod" method="POST" action="" enctype="multipart/form-data">
                        <div class="user__details">
                            <!-- <div class="input__box">
                                <span class="details">Name</span>
                                <input type="text" id="name" name="name" placeholder="Enter Product Name" required />
                            </div>
                            <div class="input__box">
                                <span class="details">Inventory Count</span>
                                <input type="text" id="inventory-count" name="inventory_count"
                                    placeholder="Inventory Count" required />
                            </div>
                            <div class="input__box">
                                <span class="details">Colour</span>
                                <input type="text" id="color" name="color" placeholder="Colour" required />
                            </div>
                            <div class="input__box">
                                <span class="details">Chassis No</span>
                                <input type="text" id="chassis-no" name="chassis_no" placeholder="Chassis Number"
                                    required />
                            </div>
                            <div class="input__box">
                                <span class="details">Motor No</span>
                                <input type="text" id="motor-no" name="motor_no" placeholder="Motor Number" required />
                            </div>
                            <div class="input__box">
                                <span class="details">Controller No</span>
                                <input type="text" id="controller-no" name="controller_no"
                                    placeholder="Controller Number" required />
                            </div>
                            <div class="input__box">
                                <span class="details">Price</span>
                                <input type="number" id="price" name="price" placeholder="Price" required />
                            </div>
                            <div class="input__box">
                                <span class="details">Date</span>
                                <input type="date" id="date" name="date" required />
                            </div>
                            <div class="input__box">
                                <span class="details">Battery No</span>
                                <input type="text" id="battery-no" name="battery_no" placeholder="Battery Number"
                                    required />
                            </div>
                            <div class="input__box">
                                <span class="details">Charger No</span>
                                <input type="text" id="charger-no" name="charger_no" placeholder="Charger Number"
                                    required />
                            </div>
                            <div class="input__box">
                                <span class="details">HSN Code</span>
                                <input type="text" id="hsn-code" name="hsn_code" placeholder="HSN Code" required />
                            </div>
                            <div class="input__box">
                                <span class="details">File</span>
                                <input type="file" name="file" accept=".csv" required />
                            </div> -->

                            <div class="input__box">
                                <span class="details">File</span>
                                <input type="file" name="file" accept=".csv" required />
                            </div>
                        </div>
                        <button type="submit" id="regi-btn" class="button btn_d">Submit</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <script src="../js/navcss.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    <?php if ($alertType): ?>
    Swal.fire({
        icon: '<?php echo $alertType; ?>',
        title: '<?php echo ucfirst($alertType); ?>',
        text: '<?php echo $alertMessage; ?>'
    }).then(() => {
        <?php if ($alertType === 'success'): ?>
        window.location.href = 'product-create.php'; // Redirect after success
        <?php endif; ?>
    });
    <?php endif; ?>
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/navcss.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    <?php if ($alertType): ?>
    Swal.fire({
        icon: '<?php echo $alertType; ?>',
        title: '<?php echo ucfirst($alertType); ?>',
        text: '<?php echo $alertMessage; ?>'
    }).then(() => {
        <?php if ($alertType === 'success'): ?>
        window.location.href = 'product-create.php'; // Redirect after success
        <?php endif; ?>
    });
    <?php endif; ?>
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>