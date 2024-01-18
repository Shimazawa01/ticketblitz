<?php
include '../function/common.php';
include '../connection/db.php';

// Check if the user is logged in
if (!isLoggedIn()) {
    header("Location: ../main/index.php");
    exit();
}

// Get the user_id from the session
$userId = $_SESSION['user_id'];

// Check if the user's account is a creator
if (!isCreator($userId, $mysqli)) {
    header("Location: overview.php");
    exit();
}

// Check if the form was submitted
if (isset($_POST['create_event'])) {
    // Retrieve form data
    $eventName = $_POST['event_name'];
    $eventLocation = $_POST['event_location'];
    $eventDescription = $_POST['event_description'];
    $eventDate = $_POST['event_date'];
    $population = $_POST['population'];
    $normalTicket = $_POST['normal_ticket'];
    $premiumTicket = isset($_POST['premium_ticket']) ? $_POST['premium_ticket'] : null;

    // Validate event date (at least a week from now)
    $currentDate = time();
    $eventTimestamp = strtotime($eventDate);

    if ($eventTimestamp < $currentDate + (7 * 24 * 60 * 60)) {
        // Redirect with error if the event date is not at least a week from now
        header("Location: organizer_create_event.php?error=date");
        exit();
    }

    // Upload event image
$imagePath = null;
if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] == 0) {
    $uploadsFolder = realpath(dirname(__FILE__) . '/../uploads/');
    $imageFolder = $uploadsFolder . '/event_images/';

    // Ensure the target directory exists
    if (!file_exists($imageFolder)) {
        mkdir($imageFolder, 0777, true);
    }

    // Get file information
    $imageName = $_FILES['event_image']['name'];
    $imageTmpName = $_FILES['event_image']['tmp_name'];
    $imageSize = $_FILES['event_image']['size'];
    $imageType = $_FILES['event_image']['type'];

    // Check if the file is an image
    $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
    $imageExtension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

    if (!in_array($imageExtension, $allowedExtensions)) {
        // Redirect with error if the file is not an allowed image type
        header("Location: organizer_create_event.php?error=image");
        exit();
    }

    // Generate a unique filename to avoid overwriting
    $uniqueFilename = uniqid('event_image_') . '_' . time() . '.' . $imageExtension;
    $imagePath = $imageFolder . $uniqueFilename;

    // Move the uploaded file to the target location
    if (!move_uploaded_file($imageTmpName, $imagePath)) {
        // Redirect with error if the file move operation fails
        header("Location: organizer_create_event.php?error=upload");
        exit();
    }
}

    // Insert event data into the events table
    $query = "INSERT INTO events (user_id, event_name, event_location, event_description, event_date, population, normal_ticket, premium_ticket, image_path) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("issssiiss", $userId, $eventName, $eventLocation, $eventDescription, $eventDate, $population, $normalTicket, $premiumTicket, $imagePath);

    if ($stmt->execute()) {
        // Event creation successful
        header("Location: ../main/index.php?success=true");
    } else {
        // Event creation failed
        echo "Error creating event: " . $stmt->error;
    }

    $stmt->close();
} else {
    // Redirect if form was not submitted
    header("Location: organizer_create_event.php");
    exit();
}
?>
