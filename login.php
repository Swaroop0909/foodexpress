<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'food_order');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM customers WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($user = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['customer_id'] = $user['id'];
            $_SESSION['customer_name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            header("Location: user.php");
            exit();
        } else {
            $message = "Invalid password.";
        }
    } else {
        $message = "No user found with this email.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - FoodExpress</title>
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: url('WhatsApp Image 2025-04-06 at 12.50.39_60268e92.jpg') no-repeat center center fixed;
            background-size: cover;
            backdrop-filter: brightness(0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            width: 100%;
            max-width: 420px;
            text-align: center;
        }

        h2 {
            margin-bottom: 30px;
            color: #1a1a1a;
            font-weight: 600;
        }

        .input-group input:focus {
            border-color: #007bff;
            background-color: #fff;
            outline: none;
        }

        .input-group {
    position: relative;
    margin-bottom: 20px;
    width: 100%;
    box-sizing: border-box;
}

.input-group input {
    width: 100%;
    padding: 12px 16px 12px 42px;
    border: 1px solid #ddd;
    border-radius: 10px;
    font-size: 15px;
    background-color: #f9f9f9;
    box-sizing: border-box;
    transition: all 0.3s ease;
}
.input-group i {
    position: absolute;
    top: 50%;
    left: 12px;
    transform: translateY(-50%);
    color: #888;
    font-size: 16px;
    pointer-events: none;
}

        button {
            width: 100%;
            padding: 14px;
            background: #333;
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #333;
        }

        .error {
            color: #e74c3c;
            margin-bottom: 15px;
            font-weight: 500;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 30px 20px;
                border-radius: 12px;
            }
        }
    </style>
</head>
<body>
    <form method="POST" class="login-container">
        <h2>Welcome To Food Express</h2>
        <?php if ($message): ?>
            <p class="error"><?= $message ?></p>
        <?php endif; ?>

        <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" placeholder="Email" required>
        </div>

        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" placeholder="Password" required>
        </div>

        <button type="submit">Login</button>
        <p style="margin-top: 19px;
        margin-bottom:-10px;">
    New user? <a href="register.php" style="color: #007bff; text-decoration: none;">Sign up here</a>
</p>
    </form>
</body>
</html>
