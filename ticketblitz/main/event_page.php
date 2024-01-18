<?php
include '../function/common.php';
include '../connection/db.php';

// Check if the user is logged in
if (isLoggedIn()) {
    // Get the user_id, email, and password from the session
    $userId = $_SESSION['user_id'];
    $email = $_SESSION['email'];

    // Check if the event_id is provided in the URL
    if (isset($_GET['event_id'])) {
        $eventId = $_GET['event_id'];

        // Fetch event details based on event_id
        $query = "SELECT * FROM events WHERE event_id = '$eventId'";
        $result = $mysqli->query($query);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Get the original population and the number of tickets purchased from the ticket_purchases table
            $originalPopulation = $row['population'];

            // Query ticket_purchases table to get the number of tickets sold for this event
            $ticketPurchaseQuery = "SELECT SUM(normal_tickets_purchased) AS total_normal, SUM(premium_tickets_purchased) AS total_premium FROM ticket_purchases WHERE event_id = '$eventId'";
            $ticketPurchaseResult = $mysqli->query($ticketPurchaseQuery);

            if ($ticketPurchaseResult) {
                // Fetch the ticket purchase data
                $ticketPurchaseData = $ticketPurchaseResult->fetch_assoc();

                // If no data is returned, set the sold tickets to 0
                $normalTicketsSold = $ticketPurchaseData['total_normal'] ?? 0;
                $premiumTicketsSold = $ticketPurchaseData['total_premium'] ?? 0;
            } else {
                // Handle the query error as needed
                echo "Error fetching ticket purchase data: " . $mysqli->error;
                exit();
            }

            // Calculate the available slots left
            $availableSlots = $originalPopulation - ($normalTicketsSold + $premiumTicketsSold);

            // Display event details
            $imagePath = '../uploads/event_images/' . basename($row['image_path']);
            ?>

            <html>
            <head>
                <title><?php echo $row['event_name']; ?></title>
                <?php include '../header/header.php'; ?>
                <link rel="stylesheet" type="text/css" href="../header/style_header.css">
                <link rel="stylesheet" type="text/css" href="../style/style_eventpage.css">
            </head>

            <body>
                <!-- Content of the body -->
                <div class="page">
                    <!-- Display event details -->
                    <div class="event-details-container">
                        <img src="<?php echo $imagePath; ?>" alt="<?php echo $row['event_name']; ?>" class="centered-image">
                    </div>
                    <div class="details-text">
                        <h2><?php echo $row['event_name']; ?></h2>
                        <p><?php echo $row['event_description']; ?></p>
                        <p>Date: <?php echo $row['event_date']; ?></p>
                        <p>Original Population: <?php echo $originalPopulation; ?></p>
                        <p>Available Slots Left: <?php echo $availableSlots; ?></p>
                        <!-- Add more details or customize the display as needed -->
                    </div>
                    <div class="buy-tickets-box" id="buy-tickets-box">
                        <!-- Purchase Form -->
                        <h3>Buy Tickets</h3>
                        <form id="payment-form" method="post">
                            <label for="normal_tickets">Number of Normal Tickets:</label>
                            <input type="number" id="normal_tickets" name="normal_tickets" required>

                            <label for="premium_tickets">Number of Premium Tickets:</label>
                            <input type="number" id="premium_tickets" name="premium_tickets" required>

                            <button type="submit" name="buy_tickets">Buy Tickets</button>
                        </form>
                    </div>
                </div>
            </body>
            </html>

            <?php
        } else {
            // Event not found
            echo '<p>Error: Event not found.</p>';
        }
    } else {
        // Redirect to the main page if event_id is not provided
        header("Location: ../main/index.php");
        exit();
    }
} else {
    // Redirect to the main page if not logged in
    header("Location: ../main/index.php");
    exit();
}
?>
