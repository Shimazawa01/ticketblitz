<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <?php include 'organizer_header.php'; ?>
    <link rel="stylesheet" type="text/css" href="../header/style_organizer_header.css">
    <link rel="stylesheet" type="text/css" href="../style/style_overview.css">
   
    
</head>
<body>
    <!-- Content of the body -->
    <div class="page">
        <div class="notverified">
            <?php
                include '../function/common.php';
                // Check if the user is logged in
                if (isLoggedIn()) {
                    // Get the user_id from the session
                    $userId = $_SESSION['user_id'];

                    // Check if the user's account is verified
                    if (!isVerified($userId, $mysqli)) {
                        // The user's account is not verified, print a div or message
                       
                    }
                } else {
                    header("location:../main/index.php");
                }
            ?>
        </div>

        <!-- Image with overlay and title -->
        <div class="cover-image">
            <img class="become" src="../assets/becomecreator.png" alt="Become an Organizer">
        </div>

        <div class="image-overlay">
                <h2>Become an Organizer and create your own events</h2>
                <a href="organizer_registration.php">
                    <button class="become-organizer-btn">Become an Organizer</button>
                </a>
        </div>

        <!-- Rest of your content -->
        <div class="join">
            
            Monetize your Events
        </div>
    </div>
</body>
</html>
