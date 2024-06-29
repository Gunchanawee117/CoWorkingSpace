<?php
include('db_connection.php');

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $bookingId = $_GET['id'];

    // Retrieve booking information
    $getBookingQuery = "SELECT * FROM booking WHERE id = $bookingId";
    $bookingResult = $connection->query($getBookingQuery);

    if ($bookingResult->num_rows > 0) {
        $bookingData = $bookingResult->fetch_assoc();

        // Insert booking data into cancel_booking table
        $insertHistoryQuery = "INSERT INTO cancel_booking (user_id, title, day, time, zone) VALUES ('" . $bookingData['user_id'] . "', '" . $bookingData['title'] . "', '" . $bookingData['day'] . "', '" . $bookingData['time'] . "', '" . $bookingData['zone'] . "')";
        if ($connection->query($insertHistoryQuery) === TRUE) {
            // Successfully added to cancel_booking, now delete from booking
            $deleteBookingQuery = "DELETE FROM booking WHERE id = $bookingId";

            if ($connection->query($deleteBookingQuery) === TRUE) {
                // Booking canceled successfully
                header("Location: admin_allbooking.php");
                exit;
            } else {
                echo "Error deleting booking: " . $connection->error;
            }
        } else {
            echo "Error inserting into cancel_booking: " . $connection->error;
        }
    } else {
        echo "Booking not found.";
    }
}

$connection->close();
?>
