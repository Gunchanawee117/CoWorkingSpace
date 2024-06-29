<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Admin - Read Report</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
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
    <!-- Include your admin navigation bar here -->
    <?php include('component/navbar.php'); ?>

    <!-- Breadcrumbs -->
    <section id="breadcrumbs" class="breadcrumbs">
        <div class="container">
            <hr>
            <div class="d-flex justify-content-between align-items-center">
                <h2>Admin - Read Report</h2>
                <ol>
                    <li><a href="adminindex.php">Menu Admin</a></li>
                    <li><a href="admin_allreport.php">All Reports</a></li>
                    <li>Read Report</li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Read Report Section -->
    <section id="read-report" class="read-report">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            Report Details
                        </div>
                        <div class="card-body">
                            <?php
                            // Include your database connection
                            include('db_connection.php');

                            // Check if report_id is provided in the URL
                            if (isset($_GET['report_id'])) {
                                $reportId = $_GET['report_id'];

                                // Retrieve report details
                                $sql = "SELECT r.*, u.Fname AS user_fname, u.Lname AS user_lname FROM reports r
                                        INNER JOIN tb_user u ON r.user_id = u.user_id
                                        WHERE r.report_id = $reportId";
                                $result = $connection->query($sql);

                                if ($result->num_rows > 0) {
                                    $row = $result->fetch_assoc();
                                    $userId = $row["user_id"];
                                    $userFullName = $row["user_fname"] . ' ' . $row["user_lname"]; // รวมชื่อและนามสกุลของผู้รายงาน
                                    $issueDate = $row["issue_date"];
                                    $issueType = $row["issue_type"];
                                    $issueSubtype = $row["issue_subtype"];
                                    $issueDescription = $row["issue_description"];
                                    $issueStatus = $row["issue_status"];

                                    // Determine the status color (red for unresolved, green for resolved)
                                    $statusColor = ($issueStatus == 1) ? 'red' : 'green';

                                    echo "<p><strong>ชื่อผู้จอง :</strong> $userFullName</p>";
                                    echo "<p><strong>วันที่ :</strong> $issueDate</p>";
                                    echo "<p><strong>ประเภทปัญหา :</strong> $issueType</p>";
                                    echo "<p><strong>ปัญหาที่เกิด :</strong> $issueSubtype</p>";
                                    echo "<hr>";
                                    echo "<p><strong>เนื้อหาปัญหา :</strong></p>";
                                    echo "<p>$issueDescription</p>";
                                    echo "<hr>";
                                    echo "<p><strong>Issue Status:</strong> <span style='color: $statusColor;'>";

                                    // Display status text based on the status code
                                    echo ($issueStatus == 1) ? 'ยังไม่ถูกแก้ไข' : 'ถูกแก้ไขแล้ว';

                                    echo "</span></p>";

                                    // Display the "Approve" button if the status is unresolved
                                    if ($issueStatus == 1) {
                                        echo "<a href='admin_approvereport.php?report_id=$reportId' class='btn btn-success'>Approve</a>";
                                    }
                                } else {
                                    echo "<p>No report found with the provided ID.</p>";
                                }
                            } else {
                                echo "<p>No report ID provided.</p>";
                            }

                            // Close the database connection
                            $connection->close();
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!-- End Read Report Section -->

    <!-- Include your footer here -->
    <?php include('component/footer.php'); ?>

</body>
</html>
