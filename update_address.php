<!-- update_address.php -->
<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'food_order');
$email = $_SESSION['email'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $update = "UPDATE customers SET address = '$address' WHERE email = '$email'";
    mysqli_query($conn, $update);
    header('Location: cart.php');
    exit();
}

$result = mysqli_query($conn, "SELECT address FROM customers WHERE email = '$email'");
$row = mysqli_fetch_assoc($result);
?>

<form method="post">
    <textarea name="address" required placeholder="Enter delivery address"><?= $row['address'] ?></textarea>
    <button type="submit">Save Address</button>
</form>
