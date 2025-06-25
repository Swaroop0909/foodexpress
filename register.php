<?php
$conn = mysqli_connect('localhost', 'root', '', 'food_order');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $check = mysqli_query($conn, "SELECT * FROM customers WHERE email = '$email'");
    if (mysqli_num_rows($check) > 0) {
        $message = "Email already exists!";
    } else {
        $query = "INSERT INTO customers (name, email, phone, password) VALUES ('$name', '$email', '$phone', '$hashed_password')";
        if (mysqli_query($conn, $query)) {
            $message = "Registration successful! <a href='login.php'>Login here</a>.";
            $success=true;
        } else {
            $message = "Error: " . mysqli_error($conn);
            $success=false;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - FoodExpress</title>
    
    <link rel="stylesheet"href="style.css">
</head>
<body>
    <form method="POST" class="register-container">
        <h2>Customer Registration</h2>

        <div class="input-group">
            
            <input type="text" name="name" placeholder="Full Name" required>
        </div>

        <div class="input-group">
                        <input type="email" name="email" placeholder="Email Address" required>
        </div>

        <div class="input-group">
            
            <input type="text" name="phone" placeholder="Phone Number" required>
        </div>

        <div class="input-group">
            
            <input type="password" name="password" placeholder="Password" required>
        </div>

        <button type="submit">Register</button>

        <?php
if (!empty($message)) {
    echo "<div class='msg " . ($success ? "" : "error") . "'>" . $message . "</div>";
}
?>

    </form>
</body>
</html>
