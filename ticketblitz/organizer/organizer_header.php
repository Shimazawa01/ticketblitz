<?php
// Include functions.php for shared functionality
include('../function/common.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TicketBlitz</title>
    <!-- Link to the external CSS file -->
    <link rel="stylesheet" href="../style/style_organizer_header.css">
    <!-- Include additional styles or scripts if needed -->

    <script>
        function toggleDropdown() {
            var dropdownContent = document.getElementById("myDropdownContent");
            dropdownContent.classList.toggle("show");

            // Toggle the "responsive" class on the topnav
            var x = document.getElementById("myTopnav");
            x.classList.toggle("responsive");
        }

        // Close the dropdown if the user clicks outside of it
        window.onclick = function(event) {
            if (!event.target.matches('.dropbtn')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');

                        // Remove the "responsive" class on the topnav when closing the dropdown
                        var x = document.getElementById("myTopnav");
                        x.classList.remove("responsive");
                    }
                }
            }
        }
    </script>
</head>
<body>

<?php if (isLoggedIn()): ?>
    <!-- Display this content if the user is logged in -->
    <div class="topnav" id="myTopnav">
        <div class="left">
            <a href="../main/index.php" class="logo">TicketBlitz</a>
        </div>
        <div class="right">
            <a href="../organizer/create_event.php">Create Event</a>
            <a href="#contact">Tickets</a>
            <div class="dropdown">
                <button class="dropbtn" onclick="toggleDropdown()">
                    <?php echo $_SESSION['email']; ?>
                    <i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown-content" id="myDropdownContent">
                    <!-- Dropdown options here -->
                    <a href="#">Option 1</a>
                    <a href="#">Option 2</a>
                    <a href="../main/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>
    <script src="header.js"></script> <!-- Link to the external JavaScript file -->
<?php else: ?>
    <!-- Display this content if the user is not logged in -->
    <div class="topnav" id="myTopnav">
        <div class ="left">
            <a href="index.php" class="logo">TicketBlitz</a>
        </div>
        <div class="right"> 
            <a href="../register/login.php">Login</a>  <a href="../register/register.php">Register</a> 
        </div>
    </div>
<?php endif; ?>

<!-- Rest of your header HTML -->

</body>
</html>
