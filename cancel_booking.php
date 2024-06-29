<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Co Working Space</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">
    <link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    
    <!-- Include SweetAlert2 CSS and JS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<?php
include('component/navbar.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cancel_booking'])) {
        $booking_id = $_POST['booking_id'];

        // Update the booking status to 2 (canceled)
        $update_sql = "UPDATE booking SET status = 2 WHERE id = '$booking_id'";
        if ($connection->query($update_sql) === TRUE) {
            // Booking canceled successfully, move it to history and delete it
            $move_to_history_sql = "INSERT INTO cancel_booking SELECT * FROM booking WHERE id = '$booking_id'";
            if ($connection->query($move_to_history_sql) === TRUE) {
                $delete_sql = "DELETE FROM booking WHERE id = '$booking_id'";
                if ($connection->query($delete_sql) === TRUE) {
                    // Successfully deleted from booking table

                    // Display SweetAlert2 success notification
                    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.js"></script>';
                    echo '<script>
                        Swal.fire({
                          icon: "success",
                          title: "Booking Canceled",
                          text: "Your booking has been successfully canceled!",
                        }).then(function() {
                          window.location = "cancel_booking.php"; // Redirect after clicking "OK"
                        });
                    </script>';

                    exit();
                } else {
                    echo '<div class="alert alert-danger">Error deleting the booking from booking table: ' . $connection->error . '</div>';
                }
            } else {
                echo '<div class="alert alert-danger">Error moving the booking to cancel_booking table: ' . $connection->error . '</div>';
            }
        } else {
            echo '<div class="alert alert-danger">Error canceling the booking: ' . $connection->error . '</div>';
        }
    }
}

// Query user's active bookings
$user_id = $_SESSION['user_id'];
$current_date = date("Y-m-d");
$sql = "SELECT * FROM booking WHERE user_id = '$user_id' AND status = 1 AND day >= '$current_date'";
$result = $connection->query($sql);

?>

<body>
    <!-- ======= Breadcrumbs ======= -->
    <section id="breadcrumbs" class="breadcrumbs">
        <div class="container">
            <hr>
            <div class="d-flex justify-content-between align-items-center">
                <h2>ข้อมูลการจองในปัจจุบัน</h2>
                <ol>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="table_booking.php">TimeTable</a></li>
                    <li>ข้อมุลการจอง</li>
                </ol>
            </div>
        </div>
    </section><!-- End Breadcrumbs -->
    <br><br>

    <?php
    if ($result->num_rows > 0) {
        echo '<table class="table table-bordered">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Date</th>';
        echo '<th>Time</th>';
        echo '<th>Title</th>';
        echo '<th>Selected Zones</th>';
        echo '<th>Action</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        while ($row = $result->fetch_assoc()) {
            $booking_id = $row['id'];
            $date = $row['day'];
            $time = $row['time'];
            $title = $row['title'];
            $zones = $row['zone'];

            echo '<tr>';
            echo '<td>' . $date . '</td>';
            echo '<td>' . $time . '</td>';
            echo '<td>' . $title . '</td>';
            echo '<td>' . $zones . '</td>';
            echo '<td>';
            echo '<form method="post">';
            echo '<input type="hidden" name="booking_id" value="' . $booking_id . '">';
            echo '<button type="submit" name="cancel_booking" class="btn btn-danger1">Cancel</button>';
            echo '</form>';
            echo '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<div class="alert alert-info">ไม่มีการจองที่เหลืออยู่ในปัจจุบัน.</div>';
    }

    // Close the database connection
    $connection->close();
    ?>

</body>
<br><br><br><br>

<?php include('component/footer.php'); ?>

</html>
