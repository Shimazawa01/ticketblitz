<?php

// Include necessary files (e.g., database connection)
include '../function/functions.php'; // Include your functions file

$userEmail = $_SESSION["email"];
$verificationError = ''; // Initialize the variable

// Check if the verification form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $verificationCode = $_POST["verificationCode"];
    
    // Assuming user ID and email are stored in the session during login or registration
    $userId = $_SESSION["user_id"];
    $userEmail = $_SESSION["email"];

    // Retrieve stored verification code from the database
    $storedVerificationCode = getStoredVerificationCodeById($userId); // Implement this function

    // Compare the entered code with the stored one
    if ($verificationCode == $storedVerificationCode) {
        // Codes match, update the user's account to mark it as verified
        markAccountAsVerified($userId); // Implement this function

        // Redirect to a page indicating successful verification
        header("Location: ../main/index.php"); // Adjust the redirection as needed
        exit();
    } else {
        // Codes don't match, set an error message
        $verificationError = "Invalid verification code. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include '../header/header.php'; ?>
    <link rel="stylesheet" type="text/css" href="../header/style_header.css">
    <link rel="stylesheet" type="text/css" href="../style/style_verify.css">
    <title>Account Verification</title>
</head>
<body>
    <div class="container">
        <div class="verification-card">
            <h2>Verify Your Email Address</h2>
            
            

            <p>
                A verification code has been sent to
            </p>

            <p class="text bold">
                <?php echo $userEmail; ?>. 
            </p>

            <p>
                Please check your inbox to enter the verification code below
                to verify your email address.
            </p>

            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="verificationCode"></label><br>
                <input type="text" name="verificationCode" required><br><br>

                <button type="submit">Verify</button>
            </form>
            
            <?php if (isset($verificationError)): ?>
                <p class="error-message"><?php echo $verificationError; ?></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
