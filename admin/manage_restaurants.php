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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_restaurant'])) {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $address = $_POST['address'];
    $imagePath = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $imageName);
        $imagePath = 'uploads/' . $imageName;
    }
    $query = "INSERT INTO restaurants (name, category, address, image) VALUES ('$name', '$category', '$address', '$imagePath')";
    mysqli_query($conn, $query);
}
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM restaurants WHERE id = $id");
}
$result = mysqli_query($conn, "SELECT * FROM restaurants");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Restaurants</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; margin: 20px; }
        h2 { text-align: center; }
        form, table { background: white; padding: 20px; margin: auto; width: 80%; max-width: 700px; }
        input, textarea, select { width: 100%; padding: 10px; margin: 8px 0; }
        button { padding: 10px 20px; background: #333; color: white; border: none; cursor: pointer; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        img { width: 60px; height: 60px; object-fit: cover; }
        .delete { color: red; text-decoration: none; }
    </style>
</head>
<body>
    <h2>Add Restaurant</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Restaurant Name" required>
        <input type="text" name="category" placeholder="Category (e.g., Italian, Continental)" required>
        <textarea name="address" placeholder="Address" required></textarea>
        <input type="file" name="image" accept="image/*" required>
        <button type="submit" name="add_restaurant">Add Restaurant</button>
    </form>

    <h2 style="margin-top:40px;">All Restaurants</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Address</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
        <?php
while ($row = mysqli_fetch_array($result)) {
    echo "<tr>" .
        "<td>" . $row['id'] . "</td>" .
        "<td>" . $row['name'] . "</td>" .
        "<td>" . $row['category'] . "</td>" .
        "<td>" . $row['address'] . "</td>" .
        "<td><img src='" . $row['image'] . "' alt='Restaurant Image' width='60' height='60'></td>" .
        "<td><a class='delete' href='?delete=" . $row['id'] . "' onclick='return confirm(\"Delete this restaurant?\")'>Delete</a></td>" .
    "</tr>";
}
?>
    </table>
</body>
</html>
