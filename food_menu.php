<?php
$conn = mysqli_connect('localhost', 'root', '', 'food_order');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch all restaurants for dropdown
$restaurantList = mysqli_query($conn, "SELECT id, name FROM restaurants");

// Filter handling
$selected_restaurant = isset($_GET['restaurant_id']) ? (int)$_GET['restaurant_id'] : 0;

if ($selected_restaurant > 0) {
    $foods = mysqli_query($conn, "
        SELECT f.*, r.name AS restaurant_name 
        FROM food_menu f 
        JOIN restaurants r ON f.restaurant_id = r.id
        WHERE r.id = $selected_restaurant
    ");
} else {
    $foods = mysqli_query($conn, "
        SELECT f.*, r.name AS restaurant_name 
        FROM food_menu f 
        JOIN restaurants r ON f.restaurant_id = r.id
    ");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>All Food Menu</title>
    <style>
        body { font-family: Arial; margin: 0; background: #f8f8f8; }
        .navbar {
            background: #333;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar .links a {
            margin-left: 25px;
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .container { max-width: 1100px; margin: auto; padding: 20px; }
        h2 { text-align: center; margin-bottom: 20px; }

        .filter-form {
            text-align: center;
            margin-bottom: 30px;
        }

        select {
            padding: 10px;
            font-size: 16px;
            margin-right: 10px;
        }

        .food-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .food-card {
            width: 280px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 15px;
        }

        .food-card img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-radius: 5px;
        }

        .food-card h3 { margin: 10px 0 5px; }
        .food-card p { margin: 5px 0; }
        .price { color: green; font-weight: bold; }
        .restaurant-name { font-size: 14px; color: #666; }
    </style>
</head>
<body>

<div class="navbar">
    <div class="brand">FoodExpress</div>
    <div class="links">
        <a href="index.php">Home</a>
        <a href="register.php">Register</a>
        <a href="login.php">Login</a>
    </div>
</div>

<div class="container">
    <h2>Food Items</h2>

    <form method="GET" class="filter-form">
        <select name="restaurant_id" onchange="this.form.submit()">
            <option value="0">-- All Restaurants --</option>
            <?php
            while ($res = mysqli_fetch_array($restaurantList)) {
                echo '<option value="' . $res['id'] . '"' . ($selected_restaurant == $res['id'] ? ' selected' : '') . '>' . $res['name'] . '</option>';
            }
            ?>
        </select>
    </form>

    <div class="food-grid">
        <?php
        if (mysqli_num_rows($foods) > 0) {
            while ($f = mysqli_fetch_array($foods)) {
                echo '<div class="food-card">' .
                     '<img src="admin/' . $f['image'] . '" alt="' . $f['name'] . '">' .
                     '<h3>' . $f['name'] . '</h3>' .
                     '<p>' . $f['description'] . '</p>' .
                     '<p class="price">₹' . $f['price'] . '</p>' .
                     '<p class="restaurant-name">From: ' . $f['restaurant_name'] . '</p>' .
                     '</div>';
            }
        } else {
            echo '<p style="text-align:center;">No food items found for this restaurant.</p>';
        }
        ?>
    </div>
</div>

</body>
</html>
