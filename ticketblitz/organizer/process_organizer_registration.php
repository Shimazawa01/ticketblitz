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

// Check if the user's account is verified
if (!isVerified($userId, $mysqli)) {
    // The user's account is not verified, handle accordingly
    header("location:../main/index.php");
    exit(); // Ensure script stops execution after redirection
}

// Process Organizer Registration Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Define the target directory for storing ID images
    $targetDirectory = "../uploads/organizer_id_images/";

    if (!is_dir($targetDirectory)) {
        mkdir($targetDirectory, 0755, true);
    }
    // Generate a unique filename for the uploaded image
    $targetFileName = uniqid("organizer_id_") . '_' . basename($_FILES["idImage"]["name"]);
    $targetPath = $targetDirectory . $targetFileName;

    // Check if the file is an image
    $imageFileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));
    if (getimagesize($_FILES["idImage"]["tmp_name"])) {
        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["idImage"]["tmp_name"], $targetPath)) {
            // File uploaded successfully, now insert data into the creator_requests table
            $insertQuery = "INSERT INTO creator_requests (id_image_path, user_id) VALUES (?, ?)";
            $stmt = $mysqli->prepare($insertQuery);
            $stmt->bind_param("si", $targetFileName, $userId);
            $stmt->execute();
            $stmt->close();

            // Redirect to a success page or perform any other desired actions
            header("location: organizer_registration.php");
            exit();
        } else {
            // Failed to move the uploaded file
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        // File is not an image
        echo "File is not an image.";
    }
} else {
    // Invalid request method
    echo "Invalid request method.";
}
?>
