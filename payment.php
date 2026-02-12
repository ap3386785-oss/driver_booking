<?php
session_start();
include 'db_connect.php';

// யூசர் லாகின் செய்திருக்க வேண்டும்
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// புக்கிங் ஐடி URL-ல் வர வேண்டும்
if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];

    // புக்கிங் மற்றும் டிரைவர் விவரங்களை எடுத்தல்
    $sql = "SELECT b.*, d.name as driver_name, d.mobile as driver_mobile, d.upi_id 
            FROM bookings b 
            JOIN drivers d ON b.driver_id = d.id 
            WHERE b.booking_id='$booking_id'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $amount = $row['amount'];
        $driver_name = $row['driver_name'];
        $driver_mobile = $row['driver_mobile'];
        
        // டிரைவருக்கு UPI ID இருந்தால் அதை எடு, இல்லை என்றால் மொபைல் நம்பரையே UPI ஆக வை
        $upi_id = !empty($row['upi_id']) ? $row['upi_id'] : $driver_mobile."@upi";
    } else {
        die("Invalid Booking ID");
    }
}

// பேமெண்ட் உறுதி செய்யப்பட்டால் (Confirm Payment)
if (isset($_POST['confirm_payment'])) {
    $update_sql = "UPDATE bookings SET payment_status='Paid' WHERE booking_id='$booking_id'";
    if ($conn->query($update_sql) === TRUE) {
        echo "<script>
                alert('Payment Successful! Thank you for riding with us.');
                window.location='user_dashboard.php';
              </script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Gateway</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f4f4; padding: 50px; text-align: center; }
        .payment-box { 
            background: white; width: 400px; margin: auto; padding: 30px; 
            border-radius: 10px; box-shadow: 0px 0px 10px #ccc; 
        }
        h2 { color: #333; }
        .amount { font-size: 24px; color: green; font-weight: bold; margin: 10px 0; }
        .qr-code { margin: 20px 0; }
        button { background-color: #28a745; color: white; padding: 12px 20px; font-size: 16px; border: none; cursor: pointer; border-radius: 5px; width: 100%; }
        button:hover { background-color: #218838; }
        .cancel { display:block; margin-top:15px; color:#555; text-decoration:none; }
    </style>
</head>
<body>

    <div class="payment-box">
        <h2>Payment Details</h2>
        <p>Paying to Driver: <strong><?php echo $driver_name; ?></strong></p>
        <p class="amount">Amount: Rs. <?php echo $amount; ?></p>
        
        <hr>
        
        <p>Scan this QR Code with GPay / PhonePe:</p>
        
        <div class="qr-code">
            <?php
            // UPI Link Format: upi://pay?pa=UPI_ID&pn=NAME&am=AMOUNT
            $upi_link = "upi://pay?pa=$upi_id&pn=$driver_name&am=$amount";
            
            // Google Chart API மூலம் QR Code படமாக மாற்றுதல்
            echo '<img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data='.urlencode($upi_link).'" alt="QR Code">';
            ?>
        </div>
        
        <p style="font-size:12px; color:gray;">(This is a demo project payment screen)</p>

        <form method="POST">
            <button type="submit" name="confirm_payment">I Have Paid (Confirm)</button>
        </form>

        <a href="user_dashboard.php" class="cancel">Cancel</a>
    </div>

</body>
</html>