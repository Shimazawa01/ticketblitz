<?php

include '../connection/db.php';

// Check if the session has already started
if (session_status() == PHP_SESSION_NONE) {
    // Start the session
    session_start();
}

// Check if the function is not already defined before declaring it
if (!function_exists('isLoggedIn')) {
    // Function to check if the user is logged in
    function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}

// Check if the function is not already defined before declaring it
if (!function_exists('isAdminLoggedIn')) {
    // Function to check if the user is logged in
    function isAdminLoggedIn() {
        return isset($_SESSION['isAdminLoggedIn']) && $_SESSION['isAdminLoggedIn'] === true;
    }
}

// Function to check if the user's account is verified
if (!function_exists('isVerified')) {
    function isVerified($userId, $mysqli) {
        $sql = "SELECT is_verified FROM users WHERE user_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($isVerified);
        $stmt->fetch();
        $stmt->close();
        return $isVerified == 1;
    }
}

// Function to check if the user is a creator
if (!function_exists('isCreator')) {
    function isCreator($userId, $mysqli) {
        // Query to check if the user is a creator
        $sql = "SELECT is_creator FROM users WHERE user_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $userId);

        // Execute the query
        $stmt->execute();

        // Bind the result variable
        $stmt->bind_result($isCreator);

        // Fetch the result
        $stmt->fetch();

        // Close the statement
        $stmt->close();

        // Return true if the user is a creator, false otherwise
        return $isCreator == 1;
    }
}

// Function to log out the user
if (!function_exists('logout')) {
    function logout() {
        session_unset();
        session_destroy();
        header("Location: ../register/login.php");
        exit();
    }
}

// Function to log out the user
if (!function_exists('adminlogout')) {
    function adminlogout() {
        session_unset();
        session_destroy();
        header("Location: adminlogin.php");
        exit();
    }
}




?>
