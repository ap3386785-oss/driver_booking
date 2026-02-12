<?php
session_start();
include 'db_connect.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // 1. ADMIN LOGIN
    if ($role == 'admin') {
        if ($email == 'admin@gmail.com' && $password == 'admin123') {
            $_SESSION['user_id'] = 'admin';
            $_SESSION['role'] = 'admin';
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "<script>alert('Error: Wrong Admin Password');</script>";
        }
    } 
    // 2. USER LOGIN
    elseif ($role == 'user') {
        $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['role'] = 'user';
            header("Location: user_dashboard.php");
            exit();
        } else {
            echo "<script>alert('Error: User Email/Password Wrong');</script>";
        }
    } 
    // 3. DRIVER LOGIN (முக்கியமானது)
    elseif ($role == 'driver') {
        $sql = "SELECT * FROM drivers WHERE email='$email' AND password='$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            
            // Session செட் செய்தல்
            $_SESSION['driver_id'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['role'] = 'driver';

            // வெற்றி மெசேஜ் காட்டிவிட்டு உள்ளே அனுப்புதல்
            echo "<script>
                    alert('Login Success! Welcome Driver.');
                    window.location.href='driver_dashboard.php';
                  </script>";
            exit();
            
        } else {
            echo "<script>alert('Error: Driver Email or Password Wrong! Please check database.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Driver Booking</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-box {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        h2 { margin-bottom: 20px; color: #333; }
        input, select {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: blue;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover { opacity: 0.9; }
        .links { margin-top: 15px; font-size: 14px; }
        .links a { text-decoration: none; color: blue; margin: 0 5px; }
    </style>
</head>
<body>

    <div class="login-box">
        <h2>Login</h2>
        <form method="POST" action="">
            <label style="float:left; font-weight:bold;">Select Role:</label>
            <select name="role">
                <option value="user">User</option>
                <option value="driver">Driver</option> <option value="admin">Admin</option>
            </select>
            
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            
            <button type="submit" name="login">Login</button>
            
            <div class="links">
                <a href="user_register.php">New User? Register</a> <br><br>
                <a href="driver_register.php">Are you a Driver? Join</a>
            </div>
        </form>
    </div>

</body>
</html>