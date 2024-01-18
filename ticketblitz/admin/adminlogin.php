<?php
// Include common functions and database connection
include '../function/common.php';
include '../connection/db.php';

// Check if the admin is already logged in
/*if (isAdminLoggedIn()) {
    header("location: admin_dashboard.php");
    exit();
}*/

// Process Admin Login Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate credentials (This is a simple example, in a real scenario, use secure authentication methods)
    $query = "SELECT * FROM admins WHERE username = ? AND password = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Admin login successful, set session and redirect to the dashboard
        session_start();
        $_SESSION['isAdminLoggedIn'] = true;
        header("location: admin_dashboard.php");
        exit();
    } else {
        // Invalid credentials, show an error message
        $error_message = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" type="text/css" href="admin_login.css">
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="username">Username:</label>
            <input type="text" name="username" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
