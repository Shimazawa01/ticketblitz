<?php
require '../vendor/autoload.php';
include '../connection/db.php';
include '../function/functions.php'; // Include the functions file


$errors = [];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $verificationCode = generateVerificationCode();

    // Check for duplicate username
    $checkDuplicateUsername = "SELECT user_id FROM users WHERE username = ?";
    $stmtCheckDuplicate = $mysqli->prepare($checkDuplicateUsername);
    $stmtCheckDuplicate->bind_param("s", $username);
    $stmtCheckDuplicate->execute();
    $stmtCheckDuplicate->store_result();

    // Check for duplicate email
    $checkDuplicateEmail = "SELECT user_id FROM users WHERE email = ?";
    $stmtCheckDuplicateEmail = $mysqli->prepare($checkDuplicateEmail);
    $stmtCheckDuplicateEmail->bind_param("s", $email);
    $stmtCheckDuplicateEmail->execute();
    $stmtCheckDuplicateEmail->store_result();

    if ($stmtCheckDuplicateEmail->num_rows > 0) {
        $errors[] = "Email address is already registered. Please use a different email.";
    }

    if ($stmtCheckDuplicate->num_rows > 0) {
        $errors[] = "Username already exists. Please choose a different one.";
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    // Validate password
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long";
    }

    if (!preg_match("/[A-Z]/", $password)) {
        $errors[] = "Password must contain at least one capital letter";
    }

    if (!preg_match("/[0-9]/", $password)) {
        $errors[] = "Password must contain at least one number";
    }

    // Check if passwords match
    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert user into the database only if there are no errors
    if (empty($errors)) {
        $query = "INSERT INTO users (username, email, password_hash, firstname, lastname, verification_code) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("ssssss", $username, $email, $hashedPassword, $firstname, $lastname, $verificationCode);

        if ($stmt->execute()) {
            // Send verification email
            sendVerificationEmail($email, $verificationCode);
        
            // Set success message in session
            $_SESSION['registration_success'] = "Account successfully created.";
        
            // Redirect to login.php
            header("Location: login.php");
            exit(); // Ensure no further code execution after redirection
        } else {
            echo "Error: " . $stmt->error;
        }
        

        $stmt->close();
    }

    $stmtCheckDuplicate->close();
    $stmtCheckDuplicateEmail->close();
}
?>
<!-- The rest of your HTML code remains unchanged -->


<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="style_register.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <div class="container">
        <div class="tile-card">
            <div class="registration-container">
                <h2>Create an Account</h2>
                <?php
                // Display errors, if any
                if (!empty($errors)) {
                    echo "<div class='error-box'>";
                    foreach ($errors as $error) {
                        echo "<p class='error-message'>$error</p>";
                    }
                    echo "</div>";
                }
                ?>

                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                <label for="firstname">First Name:</label>
                <input type="text" name="firstname" value="<?php echo isset($_POST['firstname']) ? htmlspecialchars($_POST['firstname']) : ''; ?>" required><br>

                <label for="lastname">Last Name:</label>
                <input type="text" name="lastname" value="<?php echo isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname']) : ''; ?>" required><br>

                <label for="username">Username:</label>
                <input type="text" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required><br>

                <label for="email">Email:</label>
                <input type="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required><br>

                <label for="password">Password:</label>
                <input type="password" name="password" required><br>

                <!-- Confirm Password -->
                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" name="confirmPassword" required><br>


                    <!-- Toggle Password Visibility -->
                    <input type="checkbox" onclick="togglePasswordVisibility()"> Show Password


                    <input type="submit" value="Register">
                </form>
    
                <p style="color: black;text-align: center;">Already have an account? <a href="login.php" style="color: black;">Login here</a>.</p>
            </div>
        </div>
        <div class="main-content fade-in">
            <!-- Your main content goes here -->
            <h1>Welcome to <br>Ticket Blitz!</h1>
            <p><br></p>
            <img class="logo" src="../assets/logo.png">
        </div>
    </div>

 <!-- JavaScript to toggle password visibility -->
 <script>
        function togglePasswordVisibility() {
            var passwordField = document.getElementsByName("password")[0];
            if (passwordField.type === "password") {
                passwordField.type = "text";
            } else {
                passwordField.type = "password";
            }
        }
    </script>


</body>
</html>
