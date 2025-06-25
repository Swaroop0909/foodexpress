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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_order'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    mysqli_query($conn, "UPDATE orders SET status = '$status' WHERE id = $order_id");
}
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM orders WHERE id = $id");
}
$orders = mysqli_query($conn, "SELECT * FROM orders ORDER BY order_date DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Orders</title>
    <style>
        body { font-family: Arial; background: #f9f9f9; margin: 20px; }
        h2 { text-align: center; }
        table { width: 90%; margin: auto; background: white; border-collapse: collapse; }
        th, td { padding: 12px; border: 1px solid #ccc; text-align: left; }
        form { display: inline; }
        select, button { padding: 5px; }
        .delete { color: red; text-decoration: none; }
    </style>
</head>
<body>
    <h2>Manage Orders</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Food Item</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Address</th>
            <th>Status</th>
            <th>Order Date</th>
            <th>Action</th>
        </tr>
        <?php
        while ($order = mysqli_fetch_array($orders)) {
            $pending = $order['status'] == 'pending' ? 'selected' : '';
            $progress = $order['status'] == 'on progress' ? 'selected' : '';
            $delivered = $order['status'] == 'delivered' ? 'selected' : '';
            $canceled = $order['status'] == 'canceled' ? 'selected' : '';

            echo "<tr>" .
                "<td>" . $order['id'] . "</td>" .
                "<td>" . $order['customer_name'] . "</td>" .
                "<td>" . $order['food_item'] . "</td>" .
                "<td>" . $order['quantity'] . "</td>" .
                "<td>₹" . $order['price'] . "</td>" .
                "<td>" . $order['address'] . "</td>" .
                "<td>
                    <form method='POST'>
                        <input type='hidden' name='order_id' value='" . $order['id'] . "'>
                        <select name='status'>
                            <option " . $pending . ">pending</option>
                            <option " . $progress . ">on progress</option>
                            <option " . $delivered . ">delivered</option>
                            <option " . $canceled . ">canceled</option>
                        </select>
                        <button type='submit' name='update_order'>Update</button>
                    </form>
                </td>" .
                "<td>" . $order['order_date'] . "</td>" .
                "<td><a class='delete' href='?delete=" . $order['id'] . "' onclick='return confirm(\"Delete this order?\")'>Delete</a></td>" .
                "</tr>";
        }
        ?>
    </table>
</body>
</html>
