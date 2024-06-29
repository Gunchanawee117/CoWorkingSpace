<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Admin - All Booking</title>
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

<body>
    <!-- Header -->
    <?php
    include('component/navbar.php');
    ?>

    <!-- Breadcrumbs -->
    <section id="breadcrumbs" class="breadcrumbs">
        <div class="container">
            <hr>
            <div class="d-flex justify-content-between align-items-center">
                <h2>Admin - All Booking</h2>
                <ol>
                    <li><a href="adminindex.php">Menu Admin</a></li>
                    <li>All Booking</li>
                </ol>
            </div>
        </div>
    </section><!-- End Breadcrumbs -->

    <!-- All Booking Section -->
    <section id="all-booking" class="all-booking">
        <div class="container">
            <div class="row">
                <!-- Booking List Section -->
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            Booking List
                        </div>
                        <div class="card-body">
                            <!-- Filter Form -->
                            <form method="GET" action="admin_allbooking.php">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="title">Title:</label>
                                            <select class="form-control" name="title" id="title">
                                                <option value="">All</option>
                                                <option value="Meeting">Meeting</option>
                                                <option value="Sub-Meeting">Sub-Meeting</option>
                                                <option value="Discussion">Discussion</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="time">Time:</label>
                                            <select class="form-control" name="time" id="time">
                                                <option value="">All</option>
                                                <option value="8:00-9:00">8:00-9:00</option>
                                                <option value="9:00-10:00">9:00-10:00</option>
                                                <option value="10:00-11:00">10:00-11:00</option>
                                                <option value="11:00-12:00">11:00-12:00</option>
                                                <option value="12:00-13:00">12:00-13:00</option>
                                                <option value="13:00-14:00">13:00-14:00</option>
                                                <option value="14:00-15:00">14:00-15:00</option>
                                                <option value="15:00-16:00">15:00-16:00</option>
                                                <option value="16:00-17:00">16:00-17:00</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="zone">Zone:</label>
                                            <select class="form-control" name="zone" id="zone">
                                                <option value="">All</option>
                                                <option value="A">Zone A</option>
                                                <option value="B">Zone B</option>
                                                <option value="C">Zone C</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="day">Day:</label>
                                            <input type="date" class="form-control" name="day" id="day"
                                                value="<?php echo isset($_GET['day']) ? $_GET['day'] : ''; ?>">
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-danger1">กรองข้อมูล</button>
                                    </div>
                                </div>
                                <hr>
                            </form>

                            <!-- Display the list of bookings with user_id, name, title, day, time, zone, and cancel button -->
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>User ID</th>
                                        <th>Full Name</th>
                                        <th>Title</th>
                                        <th>Day</th>
                                        <th>Time</th>
                                        <th>Zone</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include('db_connection.php');

                                    // Define the number of bookings to display per page
                                    $bookings_per_page = 20;

                                    // Initialize search variables
                                    $searchQuery = "";
                                    $searchCondition = "";

                                    // Check if a search query is provided
                                    if (isset($_GET['search']) && !empty($_GET['search'])) {
                                        $searchQuery = $_GET['search'];
                                        $searchCondition = "WHERE user_id LIKE '%$searchQuery%' OR CONCAT(Fname, ' ', Lname, ' ', Phone) LIKE '%$searchQuery%'";
                                    }

                                    // Initialize filter variables
                                    $titleFilter = "";
                                    $timeFilter = "";
                                    $zoneFilter = "";
                                    $dayFilter = "";

                                    // Check if title filter is applied
                                    if (isset($_GET['title']) && !empty($_GET['title'])) {
                                        $titleFilter = "AND title = '" . $_GET['title'] . "'";
                                    }

                                    // Check if time filter is applied
                                    if (isset($_GET['time']) && !empty($_GET['time'])) {
                                        $timeFilter = "AND time = '" . $_GET['time'] . "'";
                                    }

                                    // Check if zone filter is applied
                                    if (isset($_GET['zone']) && !empty($_GET['zone'])) {
                                        $zoneFilter = "AND zone = '" . $_GET['zone'] . "'";
                                    }

                                    // Check if day filter is applied
                                    if (isset($_GET['day']) && !empty($_GET['day'])) {
                                        $dayFilter = "AND day = '" . $_GET['day'] . "'";
                                    }

                                    // Query to count the total number of bookings with search and filter conditions
                                    $countQuery = "SELECT COUNT(*) as total FROM booking WHERE 1 $titleFilter $timeFilter $zoneFilter $dayFilter $searchCondition";
                                    $countResult = $connection->query($countQuery);
                                    $countRow = $countResult->fetch_assoc();
                                    $total_bookings = $countRow['total'];

                                    // Calculate total pages
                                    $total_pages = ceil($total_bookings / $bookings_per_page);

                                    // Check if a page number is specified in the URL
                                    $current_page = isset($_GET['page']) ? $_GET['page'] : 1;

                                    // Calculate the offset for the query
                                    $offset = ($current_page - 1) * $bookings_per_page;

                                    // Query to fetch bookings for the current page with search and filter conditions
                                    $sql = "SELECT booking.*, tb_user.Fname, tb_user.Lname FROM booking LEFT JOIN tb_user ON booking.user_id = tb_user.user_id WHERE 1 $titleFilter $timeFilter $zoneFilter $dayFilter $searchCondition LIMIT $bookings_per_page OFFSET $offset";
                                    $result = $connection->query($sql);

                                    // Loop through the bookings and display their data
                                    while ($row = $result->fetch_assoc()) {
                                        $userId = $row["user_id"];
                                        $fullName = $row["Fname"] . " " . $row["Lname"];
                                        $title = $row["title"];
                                        $day = $row["day"];
                                        $time = $row["time"];
                                        $zone = $row["zone"];
                                        $bookingId = $row["id"];

                                        echo "<tr>";
                                        echo "<td>$userId</td>";
                                        echo "<td>$fullName</td>";
                                        echo "<td>$title</td>";
                                        echo "<td>$day</td>";
                                        echo "<td>$time</td>";
                                        echo "<td>$zone</td>";
                                        echo '<td><a href="javascript:void(0);" onclick="cancelBooking(' . $bookingId . ')" class="btn btn-danger">Cancel</a></td>';
                                        echo "</tr>";
                                    }

                                    $connection->close();
                                    ?>
                                </tbody>
                            </table>

                            <!-- Pagination -->
                            <div class="pagination" style="text-align:center;justify-content:center;">
                                <?php
                                if ($total_pages > 1) {
                                    // Previous page link
                                    if ($current_page > 1) {
                                        echo '<a href="admin_allbooking.php?page=1" class="btn btn-danger1">หน้าแรก</a>';
                                        echo '<a href="admin_allbooking.php?page=' . ($current_page - 1) . '" class="btn btn-danger1">ก่อนหน้า</a>';
                                    }

                                    // Page number links
                                    for ($i = 1; $i <= $total_pages; $i++) {
                                        echo '<a href="admin_allbooking.php?page=' . $i . '" class="btn btn-danger1">' . $i . '</a>';
                                    }

                                    // Next page link
                                    if ($current_page < $total_pages) {
                                        echo '<a href="admin_allbooking.php?page=' . ($current_page + 1) . '" class="btn btn-danger1">ถัดไป</a>';
                                        echo '<a href="admin_allbooking.php?page=' . $total_pages . '" class="btn btn-danger1">หน้าสุดท้าย</a>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!-- End All Booking Section -->

    <!-- Footer -->
    <?php
    include('component/footer.php');
    ?>

    <script>
        function cancelBooking(bookingId) {
        // สร้าง SweetAlert2 เพื่อยืนยันการยกเลิกการจอง
        Swal.fire({
            title: 'ยืนยันการยกเลิกการจอง',
            text: 'คุณแน่ใจหรือไม่ว่าต้องการยกเลิกการจองนี้?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'ยืนยัน',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                // หากผู้ใช้คลิก "ยืนยัน" ให้ทำการยกเลิกการจอง
                var xhr = new XMLHttpRequest();
                var url = "admincancel_booking.php?id=" + bookingId;
                xhr.open("POST", url, true);

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        // แสดง SweetAlert2 แจ้งเตือนการยกเลิกสำเร็จ
                        Swal.fire({
                            title: 'การยกเลิกสำเร็จ',
                            text: 'การจองถูกยกเลิกเรียบร้อยแล้ว',
                            icon: 'success'
                        }).then(() => {
                            // โหลดหน้าเว็บใหม่หลังการยกเลิก
                            location.reload();
                        });
                    }
                };

                xhr.send();
            }
        });
    }
    </script>

</body>

</html>
