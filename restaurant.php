<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'food_order');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
if (!isset($_GET['id'])) {
    header('Location: user.php');
    exit();
}
$restaurant_id = intval($_GET['id']);
$restaurant_query = mysqli_query($conn, "SELECT * FROM restaurants WHERE id = $restaurant_id");
$restaurant = mysqli_fetch_assoc($restaurant_query);

$foods = mysqli_query($conn, "SELECT * FROM food_menu WHERE restaurant_id = $restaurant_id");
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $restaurant['name'] ?> - Food Menu</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; }
        .navbar {
            background: #333; color: white; padding: 15px 30px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .navbar a { color: white; text-decoration: none; margin-left: 20px; }
        .restaurant-info {
            padding: 30px;
            background: white;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            margin: 20px;
            border-radius: 10px;
        }
        .restaurant-container {
            display: flex;
            align-items: center;
            gap: 30px;
            flex-wrap: wrap;
        }
        .restaurant-image img {
            width: 320px;
            height: 220px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .restaurant-details {
            flex: 1;
        }
        .restaurant-details h1 {
            margin: 0 0 15px;
            font-size: 28px;
            color: #333;
        }
        .restaurant-details p {
            font-size: 16px;
            margin: 8px 0;
            color: #555;
        }
        .food-wrapper {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 30px;
        }
        .food-card {
            background: white;
            width: 260px;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            text-align: center;
        }
        .food-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
        }
        .food-card h3 {
            margin: 10px 0 5px;
        }
        .food-card p {
            margin: 5px 0;
            color: #555;
        }
        .add-btn {
            background: orange;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 6px;
            cursor: pointer;
        }
        .add-btn:hover {
            background: darkorange;
        }
        input[type="number"] {
            padding: 6px;
            width: 60px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
<div class="navbar">
    <div><strong>FoodExpress</strong></div>
    <div>
        <a href="user.php">Home</a>
        <a href="cart.php">Cart</a>
        <a href="logout.php">Logout</a>
    </div>
</div>
<div class="restaurant-info">
    <div class="restaurant-container">
        <div class="restaurant-image">
            <img src="admin/<?= $restaurant['image'] ?>" alt="<?= $restaurant['name'] ?>">
        </div>
        <div class="restaurant-details">
            <h1><?= $restaurant['name'] ?></h1>
            <p><strong>Category:</strong> <?= $restaurant['category'] ?></p>
            <p><strong>Address:</strong> <?= $restaurant['address'] ?></p>
        </div>
    </div>
</div>

<div class="food-wrapper">
<?php
while ($food = mysqli_fetch_array($foods)) {
    echo '<div class="food-card">' .
         '<img src="admin/' . $food['image'] . '" alt="' . $food['name'] . '">' .
         '<h3>' . $food['name'] . '</h3>' .
         '<p>' . $food['description'] . '</p>' .
         '<p><strong>₹' . number_format($food['price'], 2) . '</strong></p>' .
         '<form method="post" action="add_to_cart.php">' .
         '<input type="hidden" name="food_id" value="' . $food['id'] . '">' .
         '<input type="hidden" name="name" value="' . $food['name'] . '">' .
         '<input type="hidden" name="price" value="' . $food['price'] . '">' .
         '<input type="number" name="quantity" value="1" min="1">' .
         '<button type="submit" class="add-btn">Add to Cart</button>' .
         '</form>' .
         '</div>';
}
?>
</div>
</body>
</html>
