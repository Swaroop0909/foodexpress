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
$result = mysqli_query($conn, "SELECT * FROM customers");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Customer List</title>
    <style>
        body { font-family: Arial; background: #f8f9fa; padding: 20px; }
        h2 { text-align: center; }
        table { width: 90%; margin: auto; background: #fff; border-collapse: collapse; box-shadow: 0 0 8px #ccc; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Registered Customers</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
        </tr>
        <?php 
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>" .
                "<td>" . $row['id'] . "</td>" .
                "<td>" . $row['name'] . "</td>" .
                "<td>" . $row['email'] . "</td>" .
                "<td>" . $row['phone'] . "</td>" .
                "<td>" . $row['address'] . "</td>" .
                "</tr>";
        }
        ?>
    </table>
</body>
</html>
