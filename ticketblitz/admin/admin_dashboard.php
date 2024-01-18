<!-- admin.php -->

<?php
// Include common functions and database connection
include '../function/common.php';
include '../connection/db.php';

// Check if the admin is already logged in
if (!isAdminLoggedIn()) {
    header("location: adminlogin.php");
    exit();
}

// Query to fetch the count of users from the users table
$queryUsers = "SELECT COUNT(*) as userCount FROM users";
$resultUsers = $mysqli->query($queryUsers);
$rowUsers = $resultUsers->fetch_assoc();
$userCount = $rowUsers['userCount'];

// Query to fetch the count of pending requests from the creator_requests table
$queryRequests = "SELECT COUNT(*) as requestCount FROM creator_requests WHERE request_status = 'pending'";
$resultRequests = $mysqli->query($queryRequests);
$rowRequests = $resultRequests->fetch_assoc();
$requestCount = $rowRequests['requestCount'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="admin_sidebar.css">
    <link rel="stylesheet" type="text/css" href="admin_dashboard.css">
</head>
<body>
    <?php include 'admin_sidebar.php'; ?>

    <div class="content">
        <h2>Dashboard</h2>

        <!-- Display counts -->
        <div class="box-container">
            <div class="box">Current Users: <?php echo $userCount; ?></div>
            <div class="box">Earnings</div>
            <div class="box">Pending Requests: <?php echo $requestCount; ?></div>
        </div>
    </div>
</body>
</html>
