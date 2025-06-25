<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

$conn = mysqli_connect('localhost', 'root', '', 'food_order');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$email = $_SESSION['email'];

// Get customer name from session
$customer_name = $_SESSION['customer_name'];

// Fetch orders for the logged-in user
$query = "SELECT * FROM orders WHERE customer_name = '$customer_name' ORDER BY order_date DESC";
$orders = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Orders - FoodExpress</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            margin: 0;
        }

        .navbar {
            background: #333;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            padding: 8px 14px;
            border-radius: 6px;
            transition: background 0.2s;
        }

        .navbar a:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .container {
            padding: 40px;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        .status-completed {
            color: green;
            font-weight: bold;
        }

        .status-pending {
            color: orange;
            font-weight: bold;
        }

        .status-cancelled {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div><strong>FoodExpress</strong></div>
    <div>
        <a href="user.php">Home</a>
        <a href="cart.php">🛒 Cart</a>
        <a href="my_orders.php">📦 My Orders</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">
    <h2>📦 My Orders</h2>

    <?php if (mysqli_num_rows($orders) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Food Item</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody>
                <?php $count = 1; ?>
                <?php while ($row = mysqli_fetch_assoc($orders)): ?>
                    <tr>
                        <td><?= $count++ ?></td>
                        <td><?= htmlspecialchars($row['food_item']) ?></td>
                        <td><?= $row['quantity'] ?></td>
                        <td>₹<?= number_format($row['price'], 2) ?></td>
                        <td class="status-<?= strtolower($row['status']) ?>"><?= $row['status'] ?></td>
                        <td><?= date('d M Y, h:i A', strtotime($row['order_date'])) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have no orders yet. Go ahead and order your favorite food! 🍽️</p>
    <?php endif; ?>
</div>

</body>
</html>
