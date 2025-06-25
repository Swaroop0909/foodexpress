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
$username = explode('@', $email)[0];
$search = isset($_GET['search']) ? $_GET['search'] : '';
$query = "SELECT * FROM restaurants WHERE name LIKE '%$search%'";
$restaurants = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Welcome - FoodExpress</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
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

        .navbar a, .cart-button {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            padding: 8px 14px;
            border-radius: 6px;
            transition: background 0.2s;
        }

        .navbar a:hover, .cart-button:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .greeting {
            padding: 30px;
            font-size: 28px;
            font-weight: bold;
            color: #333;
            text-align: left;
        }

        .greeting .hello { color: #333; }
        .greeting .username { color: #ff6600; }

        .caption {
            font-size: 18px;
            font-weight: normal;
            margin-top: 8px;
            color: #555;
        }

        .search-bar {
            text-align: center;
            margin: 0 30px;
            padding-bottom: 10px;
        }

        .search-bar input[type="text"] {
            padding: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .search-bar button {
            padding: 10px 16px;
            border: none;
            background: #333;
            color: white;
            border-radius: 6px;
            cursor: pointer;
        }

        .search-bar button:hover {
            background: #555;
        }

        .restaurant-wrapper {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 30px;
        }

        .restaurant {
            width: 260px;
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.2s;
        }

        .restaurant:hover {
            transform: scale(1.05);
        }

        .restaurant img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
        }
        .restaurant h3 {
            margin: 10px 0 5px;
        }
        .restaurant p {
            margin: 5px 0;
            color: #555;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div><strong>FoodExpress</strong></div>
    <div>
        <a href="user.php">Home</a>
        <a href="my_orders.php">My Orders</a>
        <a href="cart.php" class="cart-button">Cart</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="greeting">
    <span class="hello">Hello, </span><span class="username"><?= $_SESSION['customer_name'] ?></span>!
    <div class="caption">Ready to discover great food?</div>
</div>

<div class="search-bar">
    <form method="GET">
        <input type="text" name="search" placeholder="Search restaurants" >
        <button type="submit">Search</button>
    </form>
</div>

<div class="restaurant-wrapper">
<?php
while ($r = mysqli_fetch_array($restaurants)) {
    echo '<div class="restaurant">' .
            '<a href="restaurant.php?id=' . $r['id'] . '">' .
                '<img src="admin/' . $r['image'] . '" alt="Restaurant Image">' .
            '</a>' .
            '<h3>' . $r['name'] . '</h3>' .
            '<p><strong>Category:</strong> ' . $r['category'] . '</p>' .
            '<p><strong>Address:</strong> ' . $r['address'] . '</p>' .
         '</div>';
}
?>
</div>

</body>
</html>
