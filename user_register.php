<?php
include 'db_connect.php';

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $password = $_POST['password'];

    $sql = "INSERT INTO users (name, email, mobile, address, city, password) 
            VALUES ('$name', '$email', '$mobile', '$address', '$city', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('User Registration Successful!'); window.location='index.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #DBE2EF;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px; /* மொபைலில் ஒட்டாமல் இருக்க */
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px; /* பாக்ஸ் அகலம் */
        }
        h2 { text-align: center; color: #333; margin-bottom: 20px; }
        input, textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover { background-color: #218838; }
        p { text-align: center; margin-top: 15px; }
        a { text-decoration: none; color: #007bff; }
    </style>
</head>
<body>

    <div class="container">
        <h2>User Registration</h2>
        <form method="POST" action="">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="text" name="mobile" placeholder="Mobile Number" required>
            <textarea name="address" placeholder="Address" rows="3" required></textarea>
            <input type="text" name="city" placeholder="City" required>
            <input type="password" name="password" placeholder="Password" required>
            
            <button type="submit" name="register">Register</button>
            <p>Already have an account? <a href="index.php">Login here</a></p>
        </form>
    </div>

</body>
</html>