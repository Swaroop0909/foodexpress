<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}
$conn = mysqli_connect('localhost', 'root', '', 'food_order');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$earning_query = mysqli_query($conn, "SELECT SUM(price) AS total_earnings FROM orders WHERE status = 'Delivered'");
$row = mysqli_fetch_array($earning_query);
$total_earnings = isset($row['total_earnings']) ? $row['total_earnings'] : 0.00;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #333;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .dashboard {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 30px;
        }
        .card {
            background: #fff;
            padding: 20px;
            margin: 15px;
            border-radius: 5px;
            width: 250px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .card h3 {
            margin-bottom: 10px;
        }
        .card a {
            text-decoration: none;
            color: #007BFF;
            font-weight: bold;
        }
        .logout {
            text-align: center;
            margin-top: 20px;
        }
        .logout a {
            color: #333;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Admin Dashboard</h1>
    </div>

    <div class="dashboard">
        <div class="card">
            <h3>Manage Restaurants</h3>
            <a href="manage_restaurants.php">Go</a>
        </div>
        <div class="card">
            <h3>Manage Food Menu</h3>
            <a href="manage_food_menu.php">Go</a>
        </div>
        <div class="card">
            <h3>Manage Orders</h3>
            <a href="manage_orders.php">Go</a>
        </div>
        <div class="card">
            <h3>Customer List</h3>
            <a href="customers_list.php">Go</a>
        </div>
        <div class="card">
            <h3>Total Earnings</h3>
            <p>₹<?php echo number_format($total_earnings, 2); ?>
            </p>
        </div>
    </div>
    <div class="logout">
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
