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
$customer = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM customers WHERE email = '$email'"));
$customer_name = $customer['name'];
$customer_address = $customer['address'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    foreach ($_POST['quantities'] as $food_id => $qty) {
        $qty = intval($qty);
        if ($qty <= 0) {
            mysqli_query($conn, "DELETE FROM cart WHERE customer_email = '$email' AND food_id = $food_id");
        } else {
            mysqli_query($conn, "UPDATE cart SET quantity = $qty WHERE customer_email = '$email' AND food_id = $food_id");
        }
    }
    header("Location: cart.php");
    exit();
}

if (isset($_GET['remove'])) {
    $id = intval($_GET['remove']);
    mysqli_query($conn, "DELETE FROM cart WHERE customer_email = '$email' AND food_id = $id");
    header("Location: cart.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_order'])) {
    if (empty($customer_address)) {
        echo "<script>alert('Please update your delivery address in your profile.');</script>";
    } else {
        $cartItems = mysqli_query($conn, "SELECT * FROM cart WHERE customer_email = '$email'");
        while ($item = mysqli_fetch_array($cartItems)) {
            $food = $item['food_name'];
            $qty = $item['quantity'];
            $price = $item['price'] * $qty;
            mysqli_query($conn, "INSERT INTO orders (customer_name, food_item, quantity, price, address, status, order_date)
                VALUES ('$customer_name', '$food', $qty, $price, '$customer_address', 'Pending', NOW())");
        }
        mysqli_query($conn, "DELETE FROM cart WHERE customer_email = '$email'");
        echo "<script>alert('Order placed successfully!'); window.location='user.php';</script>";
        exit();
    }
}
?>
<html>
<head>
    <title>Your Cart - FoodExpress</title>
    <style>
        body { font-family: Arial; margin: 0; background: #f9f9f9; }
        .navbar {
            background: #333;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
        }
        .container {
            padding: 30px;
        }
        h2 {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        table th {
            background: #eee;
        }
        .actions {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }
        .btn {
            padding: 10px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
        }
        .btn-update {
            background: #ccc;
        }
        .btn-order {
            background: #333;
            color: white;
        }
        .btn-remove {
            background: none;
            color: red;
            border: none;
            cursor: pointer;
        }
        .btn-add-more {
            background: #444;
            color: white;
        }
        .empty-message {
            font-size: 18px;
            color: #555;
            margin-top: 40px;
            text-align: center;
        }
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
    <h2>Your Cart</h2>
<?php
$cart_query = mysqli_query($conn, "SELECT * FROM cart WHERE customer_email = '$email'");
if (mysqli_num_rows($cart_query) > 0) {
    echo '<form method="post">
    <table>
        <tr>
            <th>Food Item</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Action</th>
        </tr>';
    $grand_total = 0;
    while ($item = mysqli_fetch_array($cart_query)) {
        $id = $item['food_id'];
        $total = $item['price'] * $item['quantity'];
        $grand_total += $total;
        echo '<tr>
            <td>' . htmlspecialchars($item['food_name']) . '</td>
            <td>₹' . number_format($item['price'], 2) . '</td>
            <td><input type="number" name="quantities[' . $id . ']" value="' . $item['quantity'] . '" min="1" style="width: 60px;"></td>
            <td>₹' . number_format($total, 2) . '</td>
            <td><a href="cart.php?remove=' . $id . '" class="btn-remove" onclick="return confirm(\'Remove this item?\')">remove</a></td>
        </tr>';
    }
    echo '<tr>
        <td colspan="3" style="text-align:right;"><strong>Grand Total:</strong></td>
        <td colspan="2"><strong>₹' . number_format($grand_total, 2) . '</strong></td>
    </tr>
    </table>
    <div class="actions">
        <button type="submit" name="update" class="btn btn-update">Update Quantities</button>
        <a href="user.php" class="btn btn-add-more">Add More Items</a>
        <a href="place_order.php" class="btn btn-order">Proceed to Payment</a>
    </div>
    </form>';
} else {
    echo '<p class="empty-message">Your cart is empty. <a href="user.php">Start adding items →</a></p>';
}
?>
</div>
</body>
</html>
