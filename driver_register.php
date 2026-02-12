<?php
include 'db_connect.php';

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $city = $_POST['city']; 
    $license = $_POST['license'];
    $password = $_POST['password'];

    // போட்டோ அப்லோட்
    $target_dir = "uploads/";
    $photo_name = basename($_FILES["photo"]["name"]);
    $target_file = $target_dir . $photo_name;
    move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);

    // மாற்றம் 1: status காலமில் 'Approved' என்று நேரடியாக சேர்க்கிறோம் (Auto Approve)
    $sql = "INSERT INTO drivers (name, email, mobile, address, city, license_no, photo, password, status) 
            VALUES ('$name', '$email', '$mobile', '$address', '$city', '$license', '$photo_name', '$password', 'Approved')";

    if ($conn->query($sql) === TRUE) {
        // மாற்றம் 2: மெசேஜ் மாற்றப்பட்டுள்ளது
        echo "<script>alert('Driver Registration Successful! You can login now.'); window.location='index.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Driver Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
        }
        h2 { text-align: center; color: #333; margin-bottom: 20px; }
        label { font-weight: bold; display: block; margin-top: 10px; margin-bottom: 5px; color: #555; }
        input, textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 15px;
        }
        button:hover { background-color: #0069d9; }
        p { text-align: center; margin-top: 15px; }
        a { text-decoration: none; color: #007bff; }
    </style>
</head>
<body>

    <div class="container">
        <h2>Driver Join Form</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Driver Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="mobile" placeholder="Mobile Number" required>
            <textarea name="address" placeholder="Address" rows="2" required></textarea>
            <input type="text" name="city" placeholder="Operating City" required>
            <input type="text" name="license" placeholder="License Number" required>
            
            <label>Upload Photo:</label>
            <input type="file" name="photo" required style="border:none; padding-left:0;">
            
            <input type="password" name="password" placeholder="Password" required>
            
            <button type="submit" name="register">Register as Driver</button>
            <p>Already have an account? <a href="index.php">Login here</a></p>
        </form>
    </div>

</body>
</html>