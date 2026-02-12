<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'];
$page = isset($_GET['page']) ? $_GET['page'] : 'home'; 

// Booking Logic
if (isset($_POST['book_driver'])) {
    $pickup = $_POST['pickup']; $city = $_POST['city']; $drop = $_POST['drop']; 
    $car = $_POST['car']; 
    $transmission = $_POST['transmission']; 
    $type = $_POST['type']; $date = $_POST['date']; $amt = $_POST['amount'];
    
    $sql = "INSERT INTO bookings (user_id, pickup_address, pickup_city, drop_address, car_type, car_transmission, trip_type, amount, trip_date, booking_status, payment_status) 
            VALUES ('$user_id', '$pickup', '$city', '$drop', '$car', '$transmission', '$type', '$amt', '$date', 'Pending', 'Pending')";
            
    if ($conn->query($sql) === TRUE) echo "<script>alert('Booking Sent!'); window.location='user_dashboard.php?page=history';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Velacity - User Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background-color: #f4f7f6; }

        /* --- NAVBAR STYLE --- */
        .navbar {
            background-color: #2c3e50; padding: 0 30px; height: 70px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .nav-brand { font-size: 24px; font-weight: 800; color: white; text-transform: uppercase; letter-spacing: 1px; font-family: 'Arial Black', sans-serif; }
        .nav-center { display: flex; gap: 15px; }
        .nav-center a { color: rgba(255,255,255,0.7); text-decoration: none; font-weight: 500; padding: 8px 15px; border-radius: 20px; transition: 0.3s; font-size: 15px; }
        .nav-center a:hover { color: white; background-color: rgba(255,255,255,0.1); }
        .nav-center a.active { color: white; background-color: #3498db; font-weight: bold; }
        .logout-btn { background-color: #e74c3c; color: white; padding: 8px 20px; border-radius: 5px; text-decoration: none; font-size: 14px; font-weight: bold; transition: 0.3s; }
        .logout-btn:hover { background-color: #c0392b; }

        /* --- CONTAINER & FORM --- */
        .container { max-width: 800px; margin: 40px auto; background: white; padding: 40px; border-radius: 12px; box-shadow: 0 5px 25px rgba(0,0,0,0.08); }
        .container.history-mode { max-width: 1000px; }
        h2 { text-align: center; margin-bottom: 25px; color: #333; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; margin-top: 15px; }
        input, textarea, select { width: 100%; padding: 12px; margin-bottom: 5px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; background-color: #fcfcfc; }
        input:focus, textarea:focus, select:focus { border-color: #3498db; outline: none; background-color: #fff; }
        button { width: 100%; padding: 14px; background-color: #3498db; color: white; border: none; border-radius: 6px; font-size: 16px; cursor: pointer; font-weight: bold; margin-top: 25px; }
        button:hover { background-color: #2980b9; }

        /* Table & Driver Card */
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border-bottom: 1px solid #eee; padding: 15px; text-align: left; vertical-align: top; }
        th { background-color: #f8f9fa; color: #333; }
        .driver-card { display: flex; align-items: center; gap: 15px; background-color: #ecf0f1; padding: 10px 15px; border-radius: 8px; border-left: 5px solid #3498db; }
        .driver-photo { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 2px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .driver-info p { margin: 0; font-size: 13px; color: #555; line-height: 1.4; }
        .driver-info b { color: #2c3e50; font-size: 14px; }
        .btn-pay { background: #27ae60; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 13px; display: inline-block; margin-top: 5px; }
        .notification-box { background-color: #fff3cd; color: #856404; padding: 15px; margin: 20px auto; max-width: 800px; border-radius: 6px; text-align: center; border: 1px solid #ffeeba; }
    </style>
    
    <script>
        function calc() {
            // KM அல்லது Hours எதுவும் இல்லையென்றால் 0 என்று எடுத்துக்கொள்ளும்
            var km = parseFloat(document.getElementById("km").value) || 0;
            var hr = parseFloat(document.getElementById("hr").value) || 0;

            // விலை பட்டியல் (Rate Card)
            var basePrice = 200; // வண்டி ஏறுனாலே 200 ரூபாய்
            var pricePerKm = 12; // 1 கி.மீக்கு 12 ரூபாய்
            var pricePerHr = 50; // 1 மணி நேரத்திற்கு 50 ரூபாய்

            // கணக்கீடு (Calculation)
            // Total = Base + (KM * 12) + (Hours * 50)
            var total = basePrice + (km * pricePerKm) + (hr * pricePerHr);

            document.getElementById("disp").value = "Rs. " + total;
            document.getElementById("amt").value = total;
        }
    </script>
</head>
<body>

    <div class="navbar">
        <div class="nav-brand">🚖 Velacity</div>
        <div class="nav-center">
            <a href="user_dashboard.php?page=home" class="<?php if($page=='home') echo 'active'; ?>">Book Ride</a>
            <a href="user_dashboard.php?page=history" class="<?php if($page=='history') echo 'active'; ?>">History</a>
            <a href="user_dashboard.php?page=feedback" class="<?php if($page=='feedback') echo 'active'; ?>">Feedback</a>
        </div>
        <a href="index.php" class="logout-btn">Logout</a>
    </div>

    <?php
    $alert_sql = "SELECT * FROM bookings WHERE user_id='$user_id' AND booking_status='Pending' AND request_time < (NOW() - INTERVAL 24 HOUR)";
    $alert_res = $conn->query($alert_sql);
    if ($alert_res && $alert_res->num_rows > 0) {
        while($alert_row = $alert_res->fetch_assoc()) {
            echo "<div class='notification-box'>⚠️ Alert: Your booking for <b>".$alert_row['pickup_city']."</b> has not been accepted for 24 hours. Please try again.</div>";
        }
    }
    ?>

    <div class="container <?php if($page=='history') echo 'history-mode'; ?>">
        
        <?php if ($page == 'home') { ?>
            <h2>Book the driver</h2>
            <form method="POST">
                <label>Pickup City</label>
                <input type="text" name="city" placeholder="E.g. Trichy" required>

                <label>Pickup Address</label>
                <textarea name="pickup" rows="2" placeholder="Full Address" required></textarea>

                <label>Drop Address</label>
                <textarea name="drop" rows="2" placeholder="Full Address" required></textarea>

                <div style="display:flex; gap:20px;">
                    <div style="flex:1;"><label>Date & Time</label><input type="datetime-local" name="date" required></div>
                    <div style="flex:1;">
                        <label>Trip Type</label>
                        <select name="type"><option>One Way</option><option>Round Trip</option></select>
                    </div>
                </div>

                <div style="display:flex; gap:20px;">
                    <div style="flex:1;">
                        <label>Car Model</label>
                        <input type="text" name="car" placeholder="E.g. Swift Dzire" required>
                    </div>
                    <div style="flex:1;">
                        <label>Transmission</label>
                        <select name="transmission">
                            <option>Manual</option>
                            <option>Automatic</option>
                        </select>
                    </div>
                </div>

                <div style="display:flex; gap:20px;">
                    <div style="flex:1;">
                        <label>Est. KM (Distance)</label>
                        <input type="number" id="km" onkeyup="calc()" onchange="calc()" placeholder="Ex: 50" >
                    </div>
                    <div style="flex:1;">
                        <label>Hours (Waiting/Rental)</label>
                        <input type="number" id="hr" onkeyup="calc()" onchange="calc()" placeholder="Ex: 2" >
                    </div>
                </div>

                <label>Estimated Amount</label>
                <input type="text" id="disp" readonly placeholder="Rs. 200 (Base)" style="background:#e9ecef; color:#27ae60; font-weight:bold;">
                <input type="hidden" name="amount" id="amt" value="200">

                <button type="submit" name="book_driver">Confirm Booking</button>
            </form>

        <?php } elseif ($page == 'history') { ?>
            <h2>📜 Trip History</h2>
            <table>
                <tr>
                    <th style="width:15%">Date</th>
                    <th style="width:20%">Info</th>
                    <th style="width:40%">Driver</th>
                    <th style="width:10%">Status</th>
                    <th style="width:15%">Action</th>
                </tr>
                <?php
                $sql = "SELECT b.*, d.name as d_name, d.mobile as d_mobile, d.photo as d_photo, d.address as d_address, d.license_no as d_license 
                        FROM bookings b LEFT JOIN drivers d ON b.driver_id = d.id 
                        WHERE b.user_id='$user_id' ORDER BY booking_id DESC";
                $res = $conn->query($sql);
                
                if($res->num_rows > 0) {
                    while($r = $res->fetch_assoc()) {
                        $trans_display = isset($r['car_transmission']) ? " (" . $r['car_transmission'] . ")" : "";
                        echo "<tr>
                                <td>".$r['trip_date']."</td>
                                <td><b>".$r['car_type']."</b>".$trans_display."<br><small>Rs.".$r['amount']."</small></td>
                                <td>";
                                if ($r['booking_status'] == 'Accepted' || $r['booking_status'] == 'Completed') {
                                    echo "<div class='driver-card'>
                                            <img src='uploads/".$r['d_photo']."' class='driver-photo' alt='Driver'>
                                            <div class='driver-info'>
                                                <b>".$r['d_name']."</b><br>
                                                📞 ".$r['d_mobile']."<br>
                                                Details: ".$r['d_address']."
                                            </div>
                                          </div>";
                                } else { echo "<span style='color:gray;'>Waiting...</span>"; }
                        echo "  </td>
                                <td style='font-weight:bold; color:".($r['booking_status']=='Completed'?'green':($r['booking_status']=='Cancelled'?'red':'#3498db'))."'>".$r['booking_status']."</td>
                                <td>";
                        if($r['booking_status']=='Accepted' && $r['payment_status']=='Pending') 
                            echo "<a href='payment.php?booking_id=".$r['booking_id']."' class='btn-pay'>Pay Now</a>";
                        elseif($r['booking_status']=='Completed') echo "<span style='color:green;'>Finished</span>";
                        else echo "-";
                        echo "</td></tr>";
                    }
                } else { echo "<tr><td colspan='5' style='text-align:center;'>No bookings found.</td></tr>"; }
                ?>
            </table>

        <?php } elseif ($page == 'feedback') { ?>
            <h2>⭐ Feedback</h2>
            <table>
                <tr><th>Date</th><th>Route</th><th>Status</th><th>Action</th></tr>
                <?php
                $sql = "SELECT * FROM bookings WHERE user_id='$user_id' AND booking_status='Completed' AND booking_id NOT IN (SELECT booking_id FROM feedback)";
                $res = $conn->query($sql);
                while($r = $res->fetch_assoc()) {
                    echo "<tr><td>".$r['trip_date']."</td><td>From: ".$r['pickup_address']."</td><td style='color:green; font-weight:bold;'>Completed</td><td><a href='feedback.php?booking_id=".$r['booking_id']."' style='background:orange; color:white; padding:5px 10px; border-radius:4px; text-decoration:none;'>Write Feedback</a></td></tr>";
                }
                ?>
            </table>
        <?php } ?>

    </div>
</body>
</html>