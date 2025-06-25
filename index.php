<?php
$conn = mysqli_connect('localhost', 'root', '', 'food_order');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$restaurants = mysqli_query($conn, "SELECT * FROM restaurants");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Online Food Ordering</title>
    <style>
        .restaurant img {
            width: 250px;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        .restaurant {
            width: 260px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 10px;
            margin: 10px;
            text-align: center;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .restaurant-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }   
        body {
            font-family: Arial;
            margin: 0;
            background: #f4f4f4;
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
        }
        .hero {
            width: 100%;
            height: 300px;
            background: url('banner.png') no-repeat center center/cover;
        }
        .section-title {
            text-align: center;
            margin: 40px 0 10px;
            font-size: 26px;
            color: #333;
        }
        .restaurant-wrapper {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 30px;
        }
        .restaurant-card {
            flex: 0 0 calc(33.33% - 40px);
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            text-align: center;
            box-sizing: border-box;
        }
        .restaurant-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
        }
        .restaurant-card h3 {
            margin: 10px 0 5px;
        }
        .restaurant-card p {
            margin: 5px 0;
            color: #555;
        }
    </style>
</head>
<body>
<div class="navbar">
    <div><strong>FoodExpress</strong></div>
    <div>
        <a href="index.php">Home</a>
        <a href="food_menu.php">Food Menu</a>
        <a href="register.php">Register</a>
        <a href="login.php">Login</a>
    </div>
</div>
<div class="hero"></div>
<h2 class="section-title">Our Restaurants</h2>
<div class="restaurant-wrapper">
<div class="restaurant-list">
<?php
while ($r = mysqli_fetch_array($restaurants)) {
    echo '<div class="restaurant">' .
         '<img src="admin/' . $r['image'] . '" alt="Restaurant Image">' .
         '<h3>' . $r['name'] . '</h3>' .
         '<p><strong>Category:</strong> ' . $r['category'] . '</p>' .
         '<p><strong>Address:</strong> ' . $r['address'] . '</p>' .
         '</div>';
}
?>
</div>
</div>
</body>
</html>
