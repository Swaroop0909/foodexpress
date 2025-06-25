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

// Add Food
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_food'])) {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $restaurant_id = $_POST['restaurant_id'];

    $imagePath = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir);
        }
        $imageName = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName);
        $imagePath = $uploadDir . $imageName;
    }

    $q = "INSERT INTO food_menu (name, description, price, image, restaurant_id) 
          VALUES ('$name', '$desc', '$price', '$imagePath', '$restaurant_id')";
    mysqli_query($conn, $q);
}

// Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM food_menu WHERE id = $id");
}

$restaurants = mysqli_query($conn, "SELECT * FROM restaurants");
$food_list = mysqli_query($conn, 
    "SELECT food_menu.*, restaurants.name AS rest_name 
     FROM food_menu 
     JOIN restaurants ON food_menu.restaurant_id = restaurants.id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Food Menu</title>
    <style>
        body { font-family: Arial; background: #f0f0f0; padding: 20px; }
        h2 { text-align: center; }
        form, table { background: #fff; padding: 20px; width: 80%; margin: auto; }
        input, textarea, select { width: 100%; padding: 10px; margin: 10px 0; }
        button { background: #222; color: #fff; padding: 10px 15px; border: none; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        img { height: 60px; width: 60px; object-fit: cover; }
        .delete { color: red; }
        form {
    background: #ffffff;
    padding: 30px;
    width: 90%;
    max-width: 600px;
    margin: 30px auto;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

input, textarea, select {
    width: 100%;
    padding: 12px;
    margin: 12px 0;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
    transition: 0.3s ease;
}

input:focus, textarea:focus, select:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
}

button {
    background: #007bff;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
    transition: 0.3s ease;
}

button:hover {
    background: #0056b3;
}

    </style>
</head>
<body>

<h2>Add Food Item</h2>
<form method="POST" enctype="multipart/form-data">
    <select name="restaurant_id" required>
        <option value="">Select Restaurant</option>
        <?php while ($row = mysqli_fetch_assoc($restaurants)) { ?>
            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
        <?php } ?>
    </select>
    <input type="text" name="name" placeholder="Food Name" required>
    <textarea name="description" placeholder="Description" required></textarea>
    <input type="number" name="price" placeholder="Price in ₹" step="0.01" required>
    <input type="file" name="image" accept="image/*" required>
    <button type="submit" name="add_food">Add Food</button>
</form>

<h2 style="margin-top: 40px;">All Food Items</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Restaurant</th>
        <th>Name</th>
        <th>Description</th>
        <th>Price (₹)</th>
        <th>Image</th>
        <th>Action</th>
    </tr>
    <?php while ($food = mysqli_fetch_assoc($food_list)) { ?>
        <tr>
            <td><?= $food['id'] ?></td>
            <td><?= $food['rest_name'] ?></td>
            <td><?= $food['name'] ?></td>
            <td><?= $food['description'] ?></td>
            <td>₹<?= $food['price'] ?></td>
            <td><img src="<?= $food['image'] ?>" alt=""></td>
            <td><a class="delete" href="?delete=<?= $food['id'] ?>" onclick="return confirm('Delete this item?')">Delete</a></td>
        </tr>
    <?php } ?>
</table>

</body>
</html>
