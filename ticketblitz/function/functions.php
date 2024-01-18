<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once 'common.php';

function generateVerificationCode() {
    return substr(md5(uniqid(rand(), true)), 0, 8);
}

function sendVerificationEmail($email, $verificationCode) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'lesterlim869@gmail.com';
        $mail->Password = 'xbns rprf yqky wgqw';  // Replace with your App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('lesterlim869@gmail.com', 'ticketblitz');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Email Verification';
        $mail->Body = 'Your verification code is: ' . $verificationCode;

        // Attempt to send the email
        if ($mail->send()) {
            // The email was sent successfully
            echo 'Verification email sent! LIM';
        } else {
            // An error occurred during email sending
            echo 'Error sending email: ' . $mail->ErrorInfo;
        }
    } catch (Exception $e) {
        // Log the error for your reference
        error_log("Error sending email: " . $e->getMessage());
        echo 'Error sending email: ' . $e->getMessage();
    }
}



function getStoredVerificationCodeById($userId) {
    // Placeholder for your actual database connection
    include '../connection/db.php';

    // Query to get the stored verification code for the given user ID
    $sql = "SELECT verification_code FROM users WHERE user_id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($storedVerificationCode);
    
    // Fetch the result
    $stmt->fetch();
    
    // Close the statement
    $stmt->close();

    return $storedVerificationCode;
}

// functions.php

function markAccountAsVerified($userId) {
    // Placeholder for your actual database connection
    include '../connection/db.php';

    // Query to update the user's account as verified
    $sql = "UPDATE users SET is_verified = 1 WHERE user_id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $userId);
    
    // Execute the update
    $stmt->execute();
    
    // Close the statement
    $stmt->close();
}


?>
