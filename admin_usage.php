<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Admin Usage</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">
    <link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>

<?php
include('component/navbar.php');
include('db_connection.php');
?>

<body>
    <section id="breadcrumbs" class="breadcrumbs">
        <div class="container">
            <hr>
            <div class="d-flex justify-content-between align-items-center">
                <h2>Admin Usage</h2>
                <ol>
                    <li><a href="adminindex.php">Home</a></li>
                    <li>Admin Usage</li>
                </ol>
            </div>
        </div>
    </section>
    <br><br>

    <div class="container">
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['change_usage_status'])) {
                $booking_id = $_POST['booking_id'];

                // Update the usage_status to 2 (marked as used)
                $update_sql = "UPDATE booking SET usage_status = 2 WHERE id = '$booking_id'";
                if ($connection->query($update_sql) === TRUE) {
                    // Successfully updated the usage_status
                    echo '<script>
                            Swal.fire({
                                title: "การทำสถานะสำเร็จ",
                                text: "สถานะการใช้งานห้องถูกเปลี่ยนเป็น \\"ห้องไม่มีคนใช้งาน\\"",
                                icon: "success"
                            }).then(() => {
                                
                            });
                            exit();
                        </script>';
                        
                } else {
                    // Error updating the usage_status
                    echo '<script>
                            Swal.fire({
                                title: "เกิดข้อผิดพลาด",
                                text: "เกิดข้อผิดพลาดในการเปลี่ยนสถานะ",
                                icon: "error"
                                
                            });
                            exit();
                        </script>';
                       
                }
            }
        }

        // Query user's active bookings, sorted by day
        $current_date = date("Y-m-d");
        $sql = "SELECT b.*, u.Fname FROM booking b
                JOIN tb_user u ON b.user_id = u.user_id
                WHERE b.status = 1 AND b.day >= '$current_date'
                ORDER BY b.day ASC";
        $result = $connection->query($sql);
        $current_day = '';

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $booking_id = $row['id'];
                $user_id = $row['user_id'];
                $Fname = $row['Fname'];
                $day = $row['day'];
                $time = $row['time'];
                $zone = $row['zone'];
                $usage_status = $row['usage_status'];

                if ($day != $current_day) {
                    if ($current_day != '') {
                        // Close the previous day's table if it's not the first day
                        echo '</tbody>';
                        echo '</table>';
                        echo '<hr>';
                    }

                    // Start a new table for the current day
                    echo '<h3>' . date("F j, Y", strtotime($day)) . '</h3>';
                    echo '<table class="table table-bordered">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>User ID</th>';
                    echo '<th>ชื่อ</th>';
                    echo '<th>ช่วงเวลา</th>';
                    echo '<th>โซน</th>';
                    echo '<th>สถานะการเข้าใช้งานห้อง</th>';
                    echo '<th>การใช้งานห้อง</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    $current_day = $day;
                }

                echo '<tr>';
                echo '<td>' . $user_id . '</td>';
                echo '<td>' . $Fname . '</td>';
                echo '<td>' . $time . '</td>';
                echo '<td>' . $zone . '</td>';
                if ($usage_status == 1) {
                    echo '<td><span class="badge bg-success">ห้องถูกใช้งาน</span></td>';
                    echo '<td>';
                    echo '<form method="post">';
                    echo '<input type="hidden" name="booking_id" value="' . $booking_id . '">';
                    echo '<button type="submit" name="change_usage_status" class="btn btn-danger">Mark as Used</button>';
                    echo '</form>';
                    echo '</td>';
                } elseif ($usage_status == 2) {
                    echo '<td><span class="badge bg-danger">ห้องไม่มีคนใช้งาน</span></td>';
                    echo '<td>';
                    echo '<button class="btn btn-danger" disabled>Mark as Used</button>';
                    echo '</td>';
                }
                echo '</tr>';
            }

            // Close the last day's table
            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<div class="alert alert-info">No bookings to display.</div>';
        }

        // Close the database connection
        $connection->close();
        ?>
    </div>
</body>
<br><br><br><br>

<?php include('component/footer.php'); ?>

</html>
