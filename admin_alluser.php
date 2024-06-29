<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Admin - All Users</title>
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
</head>

<body>
    <!-- ======= Header ======= -->
    <?php
    include('component/navbar.php');
    ?>

    <!-- ======= Breadcrumbs ======= -->
    <section id="breadcrumbs" class="breadcrumbs">
        <div class="container">
            <hr>
            <div class="d-flex justify-content-between align-items-center">
                <h2>Admin - All Users</h2>
                <ol>
                    <li><a href="adminindex.php">Menu Admin</a></li>
                    <li>All Users</li>
                </ol>
            </div>
        </div>
    </section><!-- End Breadcrumbs -->

    <!-- ======= All Users Section ======= -->
    <section id="all-users" class="all-users">
        <div class="container">
            <div class="row">
                <!-- Users List Section -->
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            Users List
                        </div>
                        <div class="card-body">
                            <!-- Search Form -->
                            <form method="GET" action="admin_alluser.php">
                                <div class="input-group mb-3">
                                    <input type="text" name="search" class="form-control" placeholder="Search by UserID or Full Name">
                                    <button class="btn btn-danger1" type="submit">Search</button>
                                </div>
                            </form>

                            <!-- Display the list of users based on your filters -->
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>User ID</th>
                                        <th>Email</th>
                                        <th>Full Name</th>
                                        <th>Phone</th>
                                        <th>Total Booking</th>
                                        <th>Total Cancel</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include('db_connection.php');

                                    // Define the number of users to display per page
                                    $users_per_page = 20;

                                    // Initialize search variables
                                    $searchQuery = "";
                                    $searchCondition = "";

                                    // Check if a search query is provided
                                    if (isset($_GET['search']) && !empty($_GET['search'])) {
                                        $searchQuery = $_GET['search'];
                                        $searchCondition = "WHERE user_id LIKE '%$searchQuery%' OR CONCAT(Fname, ' ', Lname , Email , Phone) LIKE '%$searchQuery%'";
                                    }

                                    // Query to count the total number of users with search condition
                                    $countQuery = "SELECT COUNT(*) as total FROM tb_user $searchCondition";
                                    $countResult = $connection->query($countQuery);
                                    $countRow = $countResult->fetch_assoc();
                                    $total_users = $countRow['total'];

                                    // Calculate total pages
                                    $total_pages = ceil($total_users / $users_per_page);

                                    // Check if a page number is specified in the URL
                                    $current_page = isset($_GET['page']) ? $_GET['page'] : 1;

                                    // Calculate the offset for the query
                                    $offset = ($current_page - 1) * $users_per_page;

                                    // Query to fetch users for the current page with search condition
                                    $sql = "SELECT * FROM tb_user $searchCondition LIMIT $users_per_page OFFSET $offset";
                                    $result = $connection->query($sql);

                                    // Query to count total bookings for each user
                                    $bookingCountQuery = "SELECT user_id, COUNT(*) as total_booking FROM booking GROUP BY user_id";
                                    $bookingCountResult = $connection->query($bookingCountQuery);
                                    $bookingCounts = array();

                                    // Create an associative array to store total bookings for each user
                                    while ($bookingCountRow = $bookingCountResult->fetch_assoc()) {
                                        $bookingCounts[$bookingCountRow['user_id']] = $bookingCountRow['total_booking'];
                                    }

                                    // Query to count total cancellations for each user
                                    $cancelCountQuery = "SELECT user_id, COUNT(*) as total_cancel FROM cancel_booking WHERE status = '2' GROUP BY user_id";
                                    $cancelCountResult = $connection->query($cancelCountQuery);
                                    $cancelCounts = array();

                                    // Create an associative array to store total cancellations for each user
                                    while ($cancelCountRow = $cancelCountResult->fetch_assoc()) {
                                        $cancelCounts[$cancelCountRow['user_id']] = $cancelCountRow['total_cancel'];
                                    }

                                    // Loop through the users and display their data
                                    while ($row = $result->fetch_assoc()) {
                                        $userId = $row["user_id"];
                                        $email = $row["Email"];
                                        $fullName = $row["Fname"] . " " . $row["Lname"];
                                        $phone = $row["Phone"];
                                        // Get the total bookings and cancellations for the current user
                                        $totalBooking = isset($bookingCounts[$userId]) ? $bookingCounts[$userId] : 0;
                                        $totalCancel = isset($cancelCounts[$userId]) ? $cancelCounts[$userId] : 0;

                                        echo "<tr>";
                                        echo "<td>$userId</td>";
                                        echo "<td>$email</td>";
                                        echo "<td>$fullName</td>";
                                        echo "<td>$phone</td>";
                                        echo "<td>$totalBooking</td>";
                                        echo "<td>$totalCancel</td>";
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
                                        echo '<a href="admin_alluser.php?page=1" class="btn btn-danger">First</a>';
                                        echo '<a href="admin_alluser.php?page=' . ($current_page - 1) . '" class="btn btn-danger">Previous</a>';
                                    }

                                    // Page number links
                                    for ($i = 1; $i <= $total_pages; $i++) {
                                        echo '<a href="admin_alluser.php?page=' . $i . '" class="btn btn-danger">' . $i . '</a>';
                                    }

                                    // Next page link
                                    if ($current_page < $total_pages) {
                                        echo '<a href="admin_alluser.php?page=' . ($current_page + 1) . '" class="btn btn-danger">Next</a>';
                                        echo '<a href="admin_alluser.php?page=' . $total_pages . '" class="btn btn-danger">Last</a>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!-- End All Users Section -->

    <!-- ======= Footer ======= -->
    <?php
    include('component/footer.php');
    ?>

</body>

</html>
