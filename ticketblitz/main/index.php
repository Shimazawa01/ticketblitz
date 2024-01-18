<!-- index.php -->
<?php  
include '../function/common.php';
include '../connection/db.php';

// Check if the user is logged in
if (isLoggedIn()) {
    // Get the user_id from the session
    $userId = $_SESSION['user_id'];

   
} else {
    // Redirect to the main page if not logged in
    header("Location: ../main/index.php");
    exit();
}

// Fetch upcoming events for the next week
$nextWeek = date('Y-m-d', strtotime('+1 week'));
$query = "SELECT * FROM events WHERE event_date >= '$nextWeek'";
$result = $mysqli->query($query);

?>

<html>
<head>
    <title>Home</title>
    <?php
        include '../header/header.php';
    ?>
    <link rel="stylesheet" type="text/css" href="../header/style_header.css">
    <link rel="stylesheet" type="text/css" href="../style/style_index.css">
</head>

<body>
    <!-- Content of the body -->
    <div class="page">
        <div class="notverified"> <!-- check if the session id is a verified account-->
            <?php
                    // Check if the user's account is verified
                    if (!isVerified($userId, $mysqli)) {
                        // The user's account is not verified, print a div or message
                        echo '<div class="verification-message">
                                You need to verify your email to complete your account creation
                                <a href="verify.php" style="color: white;">Verify Here</a>  
                            </div>';
                    }
            ?>
        </div>
        <!-- Display index.jpg from ../assets -->
        <div class="index-image-container">
            <img src="../assets/index.jpg" alt="Index Image" class="index-image">
        </div>  
        <h2>Upcoming Events</h2>

        <!-- Display upcoming events -->
        <div class="upcoming-events-container">

            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $imagePath = '../uploads/event_images/' . basename($row['image_path']);  // Use basename to get just the filename

                    // Check if the image file exists
                    if (file_exists($imagePath)) {
                        echo '<a href="event_page.php?event_id=' . $row['event_id'] . '" class="event-link">';
                        echo '<div class="event-box">';
                        echo '<img src="' . $imagePath . '" alt="' . $row['event_name'] . '" class="event-image">';
                        echo '<h3>' . $row['event_name'] . '</h3>';
                        echo '<p>' . $row['event_description'] . '</p>';
                        echo '<p>' . $row['event_date'] . '</p>';
                        // Add more details or customize the display as needed
                        echo '</div>';
                        echo '</a>';
                    } else {
                        echo '<p>Error: Image not found for ' . $row['event_name'] . ' at path ' . $imagePath . '</p>';
                    }
                }
            } else {
                echo '<p>No upcoming events for the next week.</p>';
            }
            ?>

        </div>

    </div>
</body>
</html>
