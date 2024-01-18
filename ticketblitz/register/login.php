<?php
session_start();

// Check if the user is already logged in, redirect to the dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: ../main/index.php");
    exit();
}

// Include the database connection file
include("../connection/db.php");

// Initialize variables for login form
$username = $password = "";
$errorMessage = "";




// Process login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user input
    $username = mysqli_real_escape_string($mysqli, $_POST["username"]);
    $enteredPassword = mysqli_real_escape_string($mysqli, $_POST["password"]);

    // Query the user table to check for the entered username
    $sql = "SELECT user_id, username, email, password_hash FROM users WHERE username = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        // Bind the result variables
        $stmt->bind_result($userId, $username, $userEmail, $hashedPassword);
        $stmt->fetch();

        // Verify the entered password against the hashed password
        if (password_verify($enteredPassword, $hashedPassword)) {
            // Login successful, set session variables
            $_SESSION['user_id'] = $userId;
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $userEmail;

            // Redirect to the main dashboard or another secure page
            header("Location: ../main/index.php");
            exit();
        } else {
            // Login failed - Incorrect password
            $errorMessage = "Invalid username or password";
        }
    } else {
        // Login failed - User not found
        $errorMessage = "Invalid username or password";
    }

    // Close the statement
    $stmt->close();
}

// Close the user management database connection
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_login.css"> <!-- Adjust the path accordingly -->
    <title>Login</title>
</head>

<body>
    <div class="container">
        <div class="login-box">
            <h2>Login</h2>

            <?php
            // Display login error message
            if (!empty($errorMessage)) {
                echo "<p class='error-message'>$errorMessage</p>";
            }


            // Check for registration success message
            if (isset($_SESSION['registration_success'])) {
                echo "<p class='error-message'>" . $_SESSION['registration_success'] . "</p>";
                unset($_SESSION['registration_success']); // Clear the session variable
            }
            ?>

            <form action="login.php" method="post">
                <label for="username">Username:</label>
                <input type="text" name="username" required value="<?php echo htmlspecialchars($username); ?>">
                <!-- Display area for error message -->
                <span class="error-message" id="usernameError"></span>

                <label for="password">Password:</label>
                <input type="password" name="password" required>
                <!-- Display area for error message -->
                <span class="error-message" id="passwordError"></span>
                
                <button type="submit">Login</button>
            </form>
            <p style="color: black;">Don't have an account? <a href="register.php" style="color: black;">Register here</a>.</p>
        </div>
    </div>
</body>
</html>
