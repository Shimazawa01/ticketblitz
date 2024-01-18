<?php
// Include common functions and database connection
include '../function/common.php';
include '../connection/db.php';

// Check if the admin is already logged in
if (!isAdminLoggedIn()) {
    header("location: adminlogin.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" type="text/css" href="admin_sidebar.css">
    <link rel="stylesheet" type="text/css" href="user_style.css">
</head>
<body>
    <?php include 'admin_sidebar.php'; ?>

    <div class="content">
        <h2>User Dashboard</h2>

        <!-- Non-verified users -->
        <div class="user-table">
            <h3>Non-verified Users</h3>
            <?php
                $queryNonVerified = "SELECT * FROM users WHERE is_verified = 0";
                $resultNonVerified = $mysqli->query($queryNonVerified);

                if ($resultNonVerified->num_rows > 0) {
                    echo "<table>";
                    echo "<tr><th>User ID</th><th>Username</th><th>Email</th></tr>";
                    while ($row = $resultNonVerified->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["user_id"] . "</td>";
                        echo "<td>" . $row["username"] . "</td>";
                        echo "<td>" . $row["email"] . "</td>";
                        // Add more fields as needed
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "No non-verified users found";
                }
            ?>
        </div>

        <!-- Verified users -->
        <div class="user-table">
            <h3>Verified Users</h3>
            <?php
                $queryVerified = "SELECT * FROM users WHERE is_verified = 1";
                $resultVerified = $mysqli->query($queryVerified);

                if ($resultVerified->num_rows > 0) {
                    echo "<table>";
                    echo "<tr><th>User ID</th><th>Username</th><th>Email</th></tr>";
                    while ($row = $resultVerified->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["user_id"] . "</td>";
                        echo "<td>" . $row["username"] . "</td>";
                        echo "<td>" . $row["email"] . "</td>";
                        // Add more fields as needed
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "No verified users found";
                }
            ?>
        </div>

        <!-- Creators -->
        <div class="user-table">
            <h3>Creator Users</h3>
            <?php
                $queryCreators = "SELECT * FROM users WHERE is_creator = 1";
                $resultCreators = $mysqli->query($queryCreators);

                if ($resultCreators->num_rows > 0) {
                    echo "<table>";
                    echo "<tr><th>User ID</th><th>Username</th><th>Email</th></tr>";
                    while ($row = $resultCreators->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["user_id"] . "</td>";
                        echo "<td>" . $row["username"] . "</td>";
                        echo "<td>" . $row["email"] . "</td>";
                        // Add more fields as needed
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "No creator users found";
                }
            ?>
        </div>

        <!-- Pending requests -->
        <div class="user-table">
            <h3>Pending Requests</h3>
            <?php
                $queryPendingRequests = "
                    SELECT cr.request_id, cr.user_id, cr.id_image_path, u.username, u.email
                    FROM creator_requests cr
                    JOIN users u ON cr.user_id = u.user_id
                    WHERE cr.request_status = 'pending'
                ";
                $resultPendingRequests = $mysqli->query($queryPendingRequests);

                if ($resultPendingRequests->num_rows > 0) {
                    echo "<table>";
                    echo "<tr><th>Request ID</th><th>User ID</th><th>Username</th><th>Email</th><th>ID Image Path</th></tr>";
                    while ($row = $resultPendingRequests->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><a href='request_action.php?request_id=" . $row["request_id"] . "'>" . $row["request_id"] . "</a></td>";
                        echo "<td><a href='request_action.php?user_id=" . $row["user_id"] . "'>" . $row["user_id"] . "</a></td>";
                        echo "<td>" . $row["username"] . "</td>";
                        echo "<td>" . $row["email"] . "</td>";
                        echo "<td><a href='request_action.php?id_image_path=" . $row["id_image_path"] . "'>" . $row["id_image_path"] . "</a></td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "No pending requests found";
                }
            ?>
        </div>
    </div>
</body>
</html>
