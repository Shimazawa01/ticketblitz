<?php
// Include common functions and database connection
include '../function/common.php';
include '../connection/db.php';

// Check if the user is logged in
if (!isLoggedIn()) {
    header("location:../main/index.php");
    exit(); // Ensure script stops execution after redirection
}

// Get the user_id from the session
$userId = $_SESSION['user_id'];



// Check if there is a pending creator request for the user
$checkRequestQuery = "SELECT request_status FROM creator_requests WHERE user_id = ?";
$stmt = $mysqli->prepare($checkRequestQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($requestStatus);
$stmt->fetch();
$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="../header/style_header.css">
    <link rel="stylesheet" type="text/css" href="../style/style_organizer_registration.css">
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizer Registration</title>
    <?php include 'organizer_header.php'; ?>
    
</head>
<body>
    <!-- Content of the body -->
    <div class="page">
        <?php if (!isVerified($userId, $mysqli)): ?>
            <!-- Display a message for non-verified users -->
            <div class="verification-message">Looking to become an organizer? Verify your account to access the registration form!
                <a href="../main/verify.php" target="_blank">Verify Here</a>
            </div>
        <?php elseif ($requestStatus === "pending"): ?>
            <!-- Display a message for users with a pending creator request -->
            <div class="pending-message">Your creator request is still pending. Please wait for approval.</div>
        <?php else: ?>
            <!-- Display the organizer registration form for verified users with no pending request -->
            <div class="form-container">
                <form action="process_organizer_registration.php" method="post" enctype="multipart/form-data">
                    <label for="idImage">Upload ID Image:</label>
                    <input type="file" name="idImage" id="idImage" accept="image/*" required>

                    <!-- Add more form fields as needed -->

                    <button type="submit">Submit Registration</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
