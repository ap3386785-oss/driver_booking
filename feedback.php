<?php
session_start();
include 'db_connect.php';

// யூசர் லாகின் செக்
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// ஃபீட்பேக் சப்மிட் செய்யும் போது
if (isset($_POST['submit_feedback'])) {
    $booking_id = $_POST['booking_id'];
    $rating = $_POST['rating'];
    $positive = $_POST['positive']; 
    $negative = $_POST['negative']; 

    $sql = "INSERT INTO feedback (booking_id, rating, positive_comments, negative_comments) 
            VALUES ('$booking_id', '$rating', '$positive', '$negative')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Thank you for your feedback!'); 
                window.location='user_dashboard.php?page=history';
              </script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Driver Feedback</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        
        .container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
            text-align: center;
        }

        h2 { color: #333; margin-bottom: 20px; }

        label { display: block; text-align: left; font-weight: bold; margin-top: 15px; color: #555; }
        
        select, textarea {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 14px;
            background-color: #f9f9f9;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: orange;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
            font-weight: bold;
        }
        button:hover { background-color: darkorange; }
        
        .cancel-link { display: block; margin-top: 15px; text-decoration: none; color: gray; font-size: 14px; }
    </style>
</head>
<body>

    <div class="container">
        <h2>⭐ Rate Your Trip</h2>
        <p>Your feedback helps us improve.</p>
        
        <form method="POST">
            <input type="hidden" name="booking_id" value="<?php echo $_GET['booking_id']; ?>">
            
            <label>Rating (1 to 5 Stars):</label>
            <select name="rating" required>
                <option value="5">⭐⭐⭐⭐⭐ (Excellent)</option>
                <option value="4">⭐⭐⭐⭐ (Good)</option>
                <option value="3">⭐⭐⭐ (Average)</option>
                <option value="2">⭐⭐ (Bad)</option>
                <option value="1">⭐ (Worst)</option>
            </select>

            <label>Positive Points (What you liked):</label>
            <textarea name="positive" placeholder="E.g. Clean car, polite driver..." rows="3"></textarea>

            <label>Negative Points (Issues if any):</label>
            <textarea name="negative" placeholder="E.g. Rash driving, late arrival..." rows="3"></textarea>

            <button type="submit" name="submit_feedback">Submit Feedback</button>
            
            <a href="user_dashboard.php?page=history" class="cancel-link">Cancel</a>
        </form>
    </div>

</body>
</html>