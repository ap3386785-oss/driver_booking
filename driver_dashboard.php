<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['driver_id']) || $_SESSION['role'] != 'driver') {
    header("Location: index.php");
    exit();
}

$driver_id = $_SESSION['driver_id'];
$driver_name = $_SESSION['name'];
$page = isset($_GET['page']) ? $_GET['page'] : 'home'; 

$driver_sql = "SELECT city FROM drivers WHERE id='$driver_id'";
$driver_result = $conn->query($driver_sql);
$driver_row = $driver_result->fetch_assoc();
$driver_city = $driver_row['city'];

// Action Logic
if (isset($_GET['action']) && isset($_GET['id'])) {
    $booking_id = $_GET['id'];
    $action = $_GET['action'];

    if ($action == 'accept') {
        $update_sql = "UPDATE bookings SET driver_id='$driver_id', booking_status='Accepted' WHERE booking_id='$booking_id'";
    } elseif ($action == 'decline') {
        $update_sql = "UPDATE bookings SET booking_status='Cancelled' WHERE booking_id='$booking_id'";
    } elseif ($action == 'complete') {
        $update_sql = "UPDATE bookings SET booking_status='Completed' WHERE booking_id='$booking_id'";
    }

    if ($conn->query($update_sql) === TRUE) {
        $redirect = ($action == 'accept') ? 'history' : 'home';
        echo "<script>window.location='driver_dashboard.php?page=$redirect';</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Driver Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background-color: #f4f7f6; }

        /* Navbar */
        .navbar {
            background-color: #0056b3;
            padding: 0 20px;
            height: 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .nav-links { display: flex; height: 100%; }
        .nav-links a {
            display: flex;
            align-items: center;
            padding: 0 20px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
        }
        .nav-links a:hover { color: white; background-color: rgba(255,255,255,0.1); }
        .nav-links a.active { color: white; border-bottom: 4px solid white; background-color: rgba(255,255,255,0.1); }
        
        .logout-btn { background-color: #dc3545; color: white; padding: 8px 15px; border-radius: 4px; text-decoration: none; font-size: 14px; }

        /* Container */
        .container { max-width: 1000px; margin: 30px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }

        /* Tables & Buttons */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border-bottom: 1px solid #eee; padding: 15px; text-align: left; }
        th { background-color: #f8f9fa; color: #333; }
        .btn { padding: 6px 12px; border-radius: 4px; color: white; text-decoration: none; font-size: 13px; margin-right: 5px; display:inline-block; }
        .accept { background: #28a745; } .decline { background: #dc3545; } .complete { background: #007bff; }
        
        /* Feedback Stars */
        .star { color: gold; font-size: 18px; }
    </style>
</head>
<body>

    <div class="navbar">
        <div class="nav-links">
            <a href="driver_dashboard.php?page=home" class="<?php if($page=='home') echo 'active'; ?>">🔔 New Requests</a>
            <a href="driver_dashboard.php?page=history" class="<?php if($page=='history') echo 'active'; ?>">📜 My History</a>
            <a href="driver_dashboard.php?page=reviews" class="<?php if($page=='reviews') echo 'active'; ?>">⭐ My Reviews</a>
        </div>
        <a href="index.php" class="logout-btn">Logout</a>
    </div>

    <div class="container">
        <h2 style="margin-top:0;">Welcome, <?php echo $driver_name; ?>!</h2>
        <p style="color:gray;">Operating City: <b><?php echo $driver_city; ?></b></p>
        <hr style="border:0; border-top:1px solid #eee; margin:20px 0;">

        <?php if ($page == 'home') { ?>
            <h3 style="color:#0056b3;">🔔 New Booking Requests</h3>
            <table>
                <tr><th>Date</th><th>Pickup Area</th><th>Car & Type</th><th>Amount</th><th>Action</th></tr>
                <?php
                $sql = "SELECT * FROM bookings WHERE pickup_city='$driver_city' AND booking_status='Pending'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>".$row['trip_date']."</td>
                                <td>".$row['pickup_address']."</td>
                                <td>".$row['car_type']." (".$row['trip_type'].")</td>
                                <td>Rs. ".$row['amount']."</td>
                                <td>
                                    <a href='driver_dashboard.php?page=home&action=accept&id=".$row['booking_id']."' class='btn accept'>Accept</a>
                                    <a href='driver_dashboard.php?page=home&action=decline&id=".$row['booking_id']."' class='btn decline'>Decline</a>
                                </td>
                              </tr>";
                    }
                } else { echo "<tr><td colspan='5' style='text-align:center; color:gray;'>No new requests.</td></tr>"; }
                ?>
            </table>
        
        <?php } elseif ($page == 'history') { ?>
            <h3 style="color:green;">✅ My Trips History</h3>
            <table>
                <tr><th>Date</th><th>Customer</th><th>Route</th><th>Status</th><th>Action</th></tr>
                <?php
                $sql = "SELECT b.*, u.name, u.mobile FROM bookings b JOIN users u ON b.user_id=u.id WHERE b.driver_id='$driver_id' ORDER BY b.trip_date DESC";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $status = $row['booking_status'];
                        $color = ($status=='Completed') ? 'green' : 'blue';
                        echo "<tr>
                                <td>".$row['trip_date']."</td>
                                <td>".$row['name']."<br><small>".$row['mobile']."</small></td>
                                <td>".$row['pickup_address']." <b>to</b> ".$row['drop_address']."</td>
                                <td style='color:$color; font-weight:bold;'>$status</td>
                                <td>";
                        if ($status == 'Accepted') echo "<a href='driver_dashboard.php?page=history&action=complete&id=".$row['booking_id']."' class='btn complete'>🏁 End Trip</a>";
                        else echo "Done";
                        echo "</td></tr>";
                    }
                } else { echo "<tr><td colspan='5' style='text-align:center; color:gray;'>No history found.</td></tr>"; }
                ?>
            </table>

        <?php } elseif ($page == 'reviews') { ?>
            <h3 style="color:orange;">⭐ Customer Ratings & Reviews</h3>
            <table>
                <tr>
                    <th>Date</th>
                    <th>Customer Name</th>
                    <th>Rating</th>
                    <th>Comments</th>
                </tr>
                <?php
                // Feedback டேபிளில் இருந்து இந்த டிரைவருக்கான ரிவ்யூக்களை மட்டும் எடுக்கிறோம்
                $sql = "SELECT f.*, u.name as user_name, b.trip_date 
                        FROM feedback f 
                        JOIN bookings b ON f.booking_id = b.booking_id 
                        JOIN users u ON b.user_id = u.id 
                        WHERE b.driver_id = '$driver_id' 
                        ORDER BY f.id DESC";
                
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        // ஸ்டார் காட்டுவதற்கான லாஜிக்
                        $rating = $row['rating'];
                        $stars = str_repeat("⭐", $rating); // 5 என்றால் 5 நட்சத்திரம் காட்டும்

                        echo "<tr>
                                <td>".$row['trip_date']."</td>
                                <td>".$row['user_name']."</td>
                                <td><span class='star'>".$stars."</span> (".$rating."/5)</td>
                                <td>
                                    <b>Liked:</b> ".$row['positive_comments']."<br>
                                    <b style='color:red;'>Issues:</b> ".$row['negative_comments']."
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align:center; padding:20px; color:gray;'>No reviews received yet.</td></tr>";
                }
                ?>
            </table>
        <?php } ?>
    </div>
</body>
</html>