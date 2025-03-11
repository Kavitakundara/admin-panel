<?php
// include '../auth.php';
session_start();

include '../conn.php';
// Check if the form is submitted

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $manager_id = $_SESSION['user_id'];

    if (!isset($_POST['pro_name']) || !is_array($_POST['pro_name'])) {
        die("Invalid form submission.");
    }

    $pro_names = $_POST['pro_name'];
    $product_prices = $_POST['product_price'];
    $quantities = $_POST['quantity'];
    $dates = $_POST['date'];
    $colors = $_POST['color'];

    // Insert each product order
    foreach ($pro_names as $key => $pro_name) {
        $clean_pro_name = mysqli_real_escape_string($conn, $pro_name);
        $clean_price = str_replace('₹', '', mysqli_real_escape_string($conn, $product_prices[$key])); 
        $quantity = (int)$quantities[$key];
        $clean_date = mysqli_real_escape_string($conn, $dates[$key]);
        $clean_color = mysqli_real_escape_string($conn, $colors[$key]);

        $grand_total = $clean_price * $quantity; // Calculate row total
            if(isset($_POST['color'])) {
                $clean_color = implode(", ", $_POST['color']); // Convert array to comma-separated string
            } else {
                $clean_color = ""; // Default value if no color is selected
            }
            
            $query = "INSERT INTO place_order (manager_id, pro_name, pro_price, quantity, date, color, grand_total) 
                      VALUES ('$manager_id', '$clean_pro_name', '$clean_price', '$quantity', '$clean_date', '$clean_color', '$grand_total')";
            
            mysqli_query($conn, $query);
    }

    // Fetch total sum of all orders in the table
    $result = mysqli_query($conn, "SELECT SUM(grand_total) AS total_sum FROM place_order");
    $row = mysqli_fetch_assoc($result);
    $total_sum = $row['total_sum'];

}

// /fetch the data
$manager_id = $_SESSION['user_id'];  
$query = "SELECT set_name, set_price, colors FROM dealer_product_prices WHERE manager_id = $manager_id";
$result = mysqli_query($conn, $query);

// Check for query errors
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
// Fetch all products
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Fetch total sum of all orders
$sum = mysqli_query($conn, "SELECT SUM(grand_total) AS total_sum FROM place_order");
$row = mysqli_fetch_assoc($sum);
$total_sum = $row['total_sum'] ?? 0; // If no data, set to 0

// Close the connection
$conn->close();
?>
<?php include "header.php" ?>


<div id="content" class="ma_1">
    <div class="container form-css mb-3 mt-5">
        <div class="row">
            <div class="col-lg-12">
                <div class="title">Add Your Order</div>
                <form action="" method="POST" id="orderPlaceForm">
                    <div class="user__details">
                        <div class="input__box mx-2">
                            <span class="details">Product:</span>
                            <select id="pro_name" name="pro_name[]" required onchange="setProductPrice()">
                                <option value="">Select a product</option>
                                <?php foreach ($products as $product): ?>
                                <option value="<?= htmlspecialchars($product['set_name']) ?>"
                                    data-price="<?= $product['set_price'] ?>">
                                    <?= htmlspecialchars($product['set_name']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="input__box mx-2">
                            <span class="details">Product Price:</span>
                            <input type="text" id="product_price" name="product_price[]" readonly required>
                        </div>

                        <div class="input__box mx-2">
                            <span class="details">Quantity:</span>
                            <input type="number" id="quantity" name="quantity[]" min="1" required>
                        </div>
                        <div class="input__box">
                            <span class="details">Date:</span>
                            <input type="date" id="date" name="date[]" required>
                        </div>
                      <div class="input__box mx-2">
                            <span class="details">Color:</span>
                            <div class="input__box mx-2">
                            <select id="pro_name" name="color[]">
                                <option value="">Select a Color</option>
                                <?php foreach ($products as $product): ?>
                                <option value="<?= htmlspecialchars($product['colors']) ?>"
                                    >
                                    <?= htmlspecialchars($product['colors']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        </div>

                        <div class="input__box">
                            <span></span>
                            <button type="submit" id="regi-btn" class="button mx-3">Add Order</button>
                        </div>
                    </div>
                </form>


            </div>
        </div>
    </div>
</div>

<script src="../js/navcss.js"></script>
<script>
function setProductPrice() {
    const modelSelect = document.getElementById('pro_name');
    const selectedOption = modelSelect.options[modelSelect.selectedIndex];
    const price = selectedOption.getAttribute('data-price');

    document.getElementById('product_price').value = price ? `₹${price}` : '';
}
</script>
<script>
document.getElementById('color').addEventListener('input', function() {
    const colors = this.value.split(',').map(color => color.trim());
    console.log("Colors entered:", colors); // Prints array of entered colors
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>