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
$food_id = intval($_POST['food_id']);
$food_name = ( $_POST['name']);
$price = floatval($_POST['price']);
$quantity = intval($_POST['quantity']);
$email = $_SESSION['email'];
$checkQuery = "SELECT * FROM cart WHERE customer_email = '$email' AND food_id = $food_id";
$checkResult = mysqli_query($conn, $checkQuery);
if (mysqli_num_rows($checkResult) > 0) {
    $updateQuery = "UPDATE cart SET quantity = quantity + $quantity WHERE customer_email = '$email' AND food_id = $food_id";
    mysqli_query($conn, $updateQuery);
} else {
    $insertQuery = "INSERT INTO cart (customer_email, food_id, food_name, quantity, price) 
                    VALUES ('$email', $food_id, '$food_name', $quantity, $price)";
    mysqli_query($conn, $insertQuery);
}
header("Location: cart.php");
exit();
?>
