<!DOCTYPE html>
<html lang="en">
<head>
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        #user-info,
        #logout-button {
            display: inline;
            margin-left: 20px;
        }

        #user-info {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .getstarted {
            border: 1px solid #ffffff;
            background-color: transparent;
            color: #ffffff;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
        }

        .getstarted:hover {
            background-color: #ffffff;
            color: #333;
        }
    </style>
</head>
<body>
<header id="header" class="fixed-top d-flex align-items-center">
    <div class="container d-flex align-items-center">
        <h1 class="logo me-auto"><a href="index.php">Co Working Space <h4>Metaverse</h4></a></h1>
        <nav id="navbar" class="navbar">
            <ul>
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="table_booking.php">Booking</a></li>
                <li><a href="metaverse.php">Metaverse</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php" style="padding-right: 10px;">Contact</a></li>

                <?php
                session_start(); // Start the session if not already started
                if (isset($_SESSION['user_id'])) { // Check if user_id is set in the session
                    echo '<li>&nbsp;&nbsp;</li>';
                    echo '<li id="user-info"> User : ' . $_SESSION['user_id'] . '</li>';
                    if ($_SESSION['Role'] === 'A') { // Check if the user has Role 'A'
                        echo '<li><a href="adminindex.php" class="active">Admin Setting</a></li>';
                    }
                    echo '<li id="logout-button"><a href="logout.php" class="getstarted">Logout</a></li>';
                } else {
                    echo '<li>&nbsp;</li>';
                    echo '<li><a href="login.php" class="getstarted">Login</a></li>';
                }
                ?>
            </ul>
            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav>
    </div>
</header>
</body>
</html>
