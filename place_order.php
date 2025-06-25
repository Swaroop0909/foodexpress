<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'food_order');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];
$customer = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM customers WHERE email = '$email'"));
$customer_name = $customer['name'];
$current_address = $customer['address'];

// Get cart items
$cart_items = mysqli_query($conn, "SELECT * FROM cart WHERE customer_email = '$email'");
$cart = [];
$total = 0;
while ($item = mysqli_fetch_assoc($cart_items)) {
    $item_total = $item['price'] * $item['quantity'];
    $total += $item_total;
    $cart[] = $item;
}

$delivery_charge = round($total * 0.04, 2);
$grand_total = $total + $delivery_charge;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $new_address = mysqli_real_escape_string($conn, $_POST['address']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);

    if (empty($new_address) || empty($payment_method)) {
        echo "<script>alert('Please fill in address and select a payment method.');</script>";
    } else {
        // Update address
        if ($new_address !== $current_address) {
            mysqli_query($conn, "UPDATE customers SET address = '$new_address' WHERE email = '$email'");
            $current_address = $new_address;
        }

        // Insert orders
        foreach ($cart as $item) {
            $food = $item['food_name'];
            $qty = $item['quantity'];
            $price = $item['price'] * $qty;

            mysqli_query($conn, "INSERT INTO orders (customer_name, food_item, quantity, price, address, status, order_date, payment_method)
                VALUES ('$customer_name', '$food', $qty, $price, '$current_address', 'Pending', NOW(), '$payment_method')");
        }

        // Clear cart
        mysqli_query($conn, "DELETE FROM cart WHERE customer_email = '$email'");

        echo "<script>alert('Order placed successfully!'); window.location='user.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Place Order</title>
    <style>
        body { font-family: Arial; background: #f9f9f9; margin: 0; padding: 0; }
        .navbar {
            background: #333; color: white; padding: 15px 30px;
            display: flex; justify-content: space-between;
        }
        .navbar a { color: white; text-decoration: none; margin-left: 20px; }

        .container {
            padding: 30px;
            max-width: 800px;
            margin: auto;
            background: white;
            margin-top: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        h2 { margin-bottom: 20px; }
        textarea, select {
            width: 100%; padding: 10px; margin-bottom: 20px;
            border: 1px solid #ccc; border-radius: 5px; font-size: 14px;
        }
        table {
            width: 100%; border-collapse: collapse; margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd; padding: 10px; text-align: center;
        }

        .total {
            text-align: right;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .btn {
            padding: 12px 20px;
            background: #333;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            float: right;
        }
        .btn:hover { background: #444; }
    </style>
</head>
<body>

<div class="navbar">
    <div><strong>FoodExpress</strong></div>
    <div>
        <a href="user.php">Home</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">
    <h2>Confirm Delivery Address</h2>
    <form method="POST">
        <textarea name="address" rows="3" placeholder="Enter delivery address" required><?= htmlspecialchars($current_address) ?></textarea>

        <h2>Select Payment Method</h2>
        <select name="payment_method" required>
            <option value="">-- Select Payment Method --</option>
            <option value="UPI">UPI</option>
            <option value="Cash on Delivery">Cash on Delivery</option>
            <option value="Net Banking">Net Banking</option>
            <option value="Card Payment">Card Payment</option>
        </select>

        <h2>Order Summary</h2>
        <table>
            <tr>
                <th>Food Item</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
            </tr>
            <?php foreach ($cart as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['food_name']) ?></td>
                    <td>₹<?= number_format($item['price'], 2) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>₹<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="total">
            Subtotal: ₹<?= number_format($total, 2) ?><br>
            Delivery Charges (4%): ₹<?= number_format($delivery_charge, 2) ?><br>
            <strong>Grand Total: ₹<?= number_format($grand_total, 2) ?></strong>
        </div>

        <button type="submit" name="place_order" class="btn">Place Order</button>
    </form>
</div>

</body>
</html>
