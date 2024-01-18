<?php
include '../function/common.php';
include '../connection/db.php';

// Check if the admin is already logged in
if (!isAdminLoggedIn()) {
    header("location: adminlogin.php");
    exit();
}

// Retrieve parameters from the URL
$requestId = $_GET['request_id'] ?? null;

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $requestStatus = $_POST['request_status']; // Assuming you have a select dropdown for status

    // Update status in the database
    $updateQuery = "UPDATE creator_requests SET request_status = ? WHERE request_id = ?";
    $updateStmt = $mysqli->prepare($updateQuery);
    $updateStmt->bind_param("si", $requestStatus, $requestId);
    $updateStmt->execute();
    $updateStmt->close();

    // If the status is accepted, update is_creator in the users table
    if ($requestStatus === 'accepted') {
        $userIdQuery = "SELECT user_id FROM creator_requests WHERE request_id = ?";
        $userIdStmt = $mysqli->prepare($userIdQuery);
        $userIdStmt->bind_param("i", $requestId);
        $userIdStmt->execute();
        $userIdStmt->bind_result($userId);
        $userIdStmt->fetch();
        $userIdStmt->close();

        // Update is_creator to 1 in the users table
        $updateIsCreatorQuery = "UPDATE users SET is_creator = 1 WHERE user_id = ?";
        $updateIsCreatorStmt = $mysqli->prepare($updateIsCreatorQuery);
        $updateIsCreatorStmt->bind_param("i", $userId);
        $updateIsCreatorStmt->execute();
        $updateIsCreatorStmt->close();
    }
}

if ($requestId) {
    // Fetch data from the creator_requests table along with user information
    $query = "SELECT cr.request_id, cr.user_id, cr.id_image_path, cr.request_status, u.username, u.email 
              FROM creator_requests cr
              JOIN users u ON cr.user_id = u.user_id
              WHERE cr.request_id = ?";
    
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $requestId);
    $stmt->execute();

    $stmt->bind_result($requestId, $userId, $idImagePath, $requestStatus, $username, $email);

    if ($stmt->fetch()) {
        // Data found, proceed to display information
        // Assuming uploads folder is in the same directory as this PHP file
        $uploadsFolder = realpath(dirname(__FILE__) . '/../uploads/');
        $imagePath = $uploadsFolder . '/organizer_id_images/' . $idImagePath;

        if (file_exists($imagePath)) {
            $imageData = base64_encode(file_get_contents($imagePath));
            $imageMimeType = mime_content_type($imagePath);
        } else {
            echo "Image file not found: $imagePath";
        }
    } else {
        echo "No matching request found for request ID: " . $requestId;
    }

    $stmt->close();
} else {
    echo "No request ID parameter provided";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Action</title>
    <link rel="stylesheet" type="text/css" href="admin_sidebar.css">
    <link rel="stylesheet" type="text/css" href="request.css">
</head>
<body>
   
    <?php include 'admin_sidebar.php'; ?>
    <div class="content">
        <h2>Request Action</h2>

        <?php if (isset($requestId)): ?>
            <div class="request-info">
                <h3>Request ID: <?php echo $requestId; ?></h3>
                <h3>User ID: <?php echo $userId; ?></h3>
                <h3>Username: <?php echo $username; ?></h3>
                <h3>Email: <?php echo $email; ?></h3>
                
            </div>
            <div class="image-confirm">
                        <?php if (isset($imageData) && isset($imageMimeType)): ?>
                            <img src="data:<?php echo $imageMimeType; ?>;base64,<?php echo $imageData; ?>" alt="ID Image">
                        <?php else: ?>
                            <p>No image available</p>
                        <?php endif; ?>

                        <!-- Form for accept and deny buttons -->
            <form method="post">
                <label for="request_status">Status:</label>
                <select name="request_status" id="request_status">
                    <option value="accepted" <?php echo ($requestStatus == 'accepted') ? 'selected' : ''; ?>>Accepted</option>
                    <option value="denied" <?php echo ($requestStatus == 'denied') ? 'selected' : ''; ?>>Denied</option>
                </select>
                <button type="submit">Submit</button>
            </form>
                    </div>
            
        <?php endif; ?>
    </div>
</body>
</html>
