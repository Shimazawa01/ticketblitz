<!-- organizer_create_event.php -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <?php include 'organizer_header.php'; ?>
    <link rel="stylesheet" type="text/css" href="../header/style_organizer_header.css">
    <link rel="stylesheet" type="text/css" href="../style/style_create_event.css">
</head>

<body>

    <!-- Content of the body -->
    <div class="page">
        <!-- Check if the session id is a verified account -->
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
                        echo '<div class="verification-message">
                                You need to verify your email to complete your account creation
                                <a href="../main/verify.php" style="color: white;">Verify Here</a>  
                            </div>';
                    }
                } else {
                    header("location:../main/index.php");
                }
            ?>
        </div>

        <?php
            include '../function/common.php';

            // Check if the user's account is not a creator
            if (!isCreator($userId, $mysqli)) {
                // The user's account is not a creator, redirect to overview.php
                header("Location:overview.php");
                exit(); // Ensure script execution stops after redirect
            }
        ?>
    
        <!-- Create Event Form -->
        <div class="create-event-form">
            <h2>Create Event</h2>

            <!-- Display PHP error message -->
            <?php
                // Display error message if provided
                if (isset($_GET['error']) && $_GET['error'] === 'date') {
                    echo '<p class="error-message" style="color: red;">Event date must be at least a week from now.</p>';
                }
            ?>

            <form id="create-event-form" action="process_create_event.php" method="post" enctype="multipart/form-data" onsubmit="return validateEventDate()">
                <!-- Event Name -->
                <label for="event_name">Event Name:</label>
                <input type="text" name="event_name" required value="<?php echo isset($_POST['event_name']) ? $_POST['event_name'] : ''; ?>">

                <!-- Event Location -->
                <label for="event_location">Event Location:</label>
                <input type="text" name="event_location" value="<?php echo isset($_POST['event_location']) ? $_POST['event_location'] : ''; ?>">

                <!-- Event Description -->
                <label for="event_description">Event Description:</label>
                <textarea name="event_description"><?php echo isset($_POST['event_description']) ? $_POST['event_description'] : ''; ?></textarea>

                <!-- Event Date -->
                <label for="event_date">Event Date:</label>
                <input type="datetime-local" name="event_date" id="event_date" required value="<?php echo isset($_POST['event_date']) ? $_POST['event_date'] : ''; ?>">

                <!-- Display JavaScript error message -->
                <div id="js-error-message" class="error-message" style="color: red; display: none;"></div>

                <!-- Add min attribute for date validation -->
                <small class="form-text text-muted">Select a date at least a week from now.</small>

                <!-- Population -->
                <label for="population">Population:</label>
                <input type="number" name="population" required value="<?php echo isset($_POST['population']) ? $_POST['population'] : ''; ?>">

                <!-- Normal Ticket Price -->
                <label for="normal_ticket">Normal Ticket Price:</label>
                <input type="number" name="normal_ticket" required value="<?php echo isset($_POST['normal_ticket']) ? $_POST['normal_ticket'] : ''; ?>">

                <!-- Premium Ticket Price -->
                <label for="premium_ticket">Premium Ticket Price:</label>
                <input type="number" name="premium_ticket" value="<?php echo isset($_POST['premium_ticket']) ? $_POST['premium_ticket'] : ''; ?>">

                <!-- Event Image -->
                <label for="event_image">Event Image:</label>
                <input type="file" name="event_image" accept="image/*">

                <button type="submit" name="create_event">Create Event</button>
            </form>
        </div>

        <script>
            function validateEventDate() {
                var eventDateInput = document.getElementById('event_date');
                var errorMessage = document.getElementById('js-error-message');
                var eventDate = new Date(eventDateInput.value);
                var now = new Date();
                var oneWeekLater = new Date(now.getTime() + 7 * 24 * 60 * 60 * 1000); // One week in milliseconds

                if (eventDate < oneWeekLater) {
                    errorMessage.innerHTML = 'Event date must be at least a week from now.';
                    errorMessage.style.display = 'block';
                    return false; // Prevent form submission
                } else {
                    errorMessage.style.display = 'none';
                    return true; // Allow form submission
                }
            }
        </script>
    </div>
</body>
</html>
