<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>All Reports</title>
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
    <!-- Include your navigation bar here -->
    <?php include('component/navbar.php'); ?>

    <!-- Breadcrumbs -->
    <section id="breadcrumbs" class="breadcrumbs">
        <div class="container">
            <hr>
            <div class="d-flex justify-content-between align-items-center">
                <h2>All Reports</h2>
                <ol>
                    <li><a href="Index.php">Home</a></li>
                    <li>All Reports</li>
                </ol>
            </div>
        </div>
    </section>

    <!-- All Reports Section -->
    <section id="all-reports" class="all-reports">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            Reports List
                        </div>
                        <div class="card-body">
                            <!-- Search Form -->
                            <form method="GET" action="admin_allreport.php">
                                <div class="input-group mb-3">
                                    <input type="date" name="date_filter" class="form-control" placeholder="วันที่">
                                    <select name="type_filter" class="form-select">
                                        <option value="">Select an issue type</option>
                                        <option value="เครื่องใช้ไฟฟ้า">Electrical Appliances</option>
                                        <option value="เฟอร์นิเจอร์">furniture</option>
                                    </select>
                                    <select name="subtype_filter" class="form-select">
                                        <option value="">Select the problem.</option>
                                        <option value="หลอดไฟ">light bulb</option>
                                        <option value="ปลั๊กไฟ">Power outlet</option>
                                        <option value="พื้น">floor</option>
                                        <!-- Add more options for issue subtypes as needed -->
                                    </select>
                                    <button class="btn btn-danger1" type="submit">Search</button>
                                </div>
                            </form>

                            <!-- Display the list of reports -->
                            <table class="table table-striped">
                                <br>
                                <hr>
                                <thead>
                                    <tr>
                                        <th>User ID</th>
                                        <th>Date&Time</th>
                                        <th>Problem Type</th>
                                        <th>problem</th>
                                        <th>Repair status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Include your database connection
                                    include('db_connection.php');

                                    // จำนวนรายการที่แสดงในแต่ละหน้า
                                    $perPage = 20;

                                    // ตรวจสอบหน้าปัจจุบันและคำนวณหน้าแรก
                                    if (isset($_GET['page'])) {
                                        $currentPage = $_GET['page'];
                                    } else {
                                        $currentPage = 1;
                                    }

                                    $start = ($currentPage - 1) * $perPage;

                                    // แสดงรายงานตามหน้า
                                    $sql = "SELECT * FROM reports WHERE 1=1";

                                    // Check if date filter is set
                                    if (!empty($_GET['date_filter'])) {
                                        $dateFilter = $_GET['date_filter'];
                                        $sql .= " AND issue_date = '$dateFilter'";
                                    }

                                    // Check if issue type filter is set
                                    if (!empty($_GET['type_filter'])) {
                                        $typeFilter = $_GET['type_filter'];
                                        $sql .= " AND issue_type = '$typeFilter'";
                                    }

                                    // Check if issue subtype filter is set
                                    if (!empty($_GET['subtype_filter'])) {
                                        $subtypeFilter = $_GET['subtype_filter'];
                                        $sql .= " AND issue_subtype = '$subtypeFilter'";
                                    }

                                    // เพิ่ม ORDER BY เรียงลำดับตามเวลาที่สร้างในลำดับจากใหม่ไปเก่า
                                    $sql .= " ORDER BY created_at DESC";

                                    // จำนวนทั้งหมดของรายงาน
                                    $totalReports = $connection->query($sql)->num_rows;

                                    // จำนวนหน้าทั้งหมด
                                    $totalPages = ceil($totalReports / $perPage);

                                    // แสดงรายงานตามหน้า
                                    $sql .= " LIMIT $start, $perPage";

                                    $result = $connection->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $userId = $row["user_id"];
                                            $issueType = $row["issue_type"];
                                            $issueSubtype = $row["issue_subtype"];
                                            $issueStatus = $row["issue_status"];
                                            $issueDate = $row["issue_date"];
                                            $createdAt = $row["created_at"]; // เพิ่มการเรียกใช้งานฟิลด์ "created_at"
                                            $issueDateFormatted = date("F j, Y", strtotime($issueDate));
                                            
                                            // แปลงเวลาในรูปแบบ "วันที่ เวลา"
                                            $createdAtFormatted = date("F j, Y H:i:s", strtotime($createdAt));

                                            // กำหนดสีสถานะ (สีแดงสำหรับยังไม่ได้แก้ไข, สีเขียวสำหรับแก้ไขแล้ว)
                                            if ($issueStatus == 1) {
                                                $statusColor = 'red';
                                                $statusText = 'Unresolved';
                                            } elseif ($issueStatus == 0) {
                                                $statusColor = 'orange'; // Use yellow color for "รอการแก้ไข" (Waiting for Repair)
                                                $statusText = 'Wait for fix.';
                                            } else {
                                                $statusColor = 'green';
                                                $statusText = 'Resolved';
                                            }

                                            echo "<tr>";
                                            echo "<td>$userId</td>";
                                            echo "<td>$createdAtFormatted</td>"; // แสดงค่าฟิลด์ "created_at" ในรูปแบบ "วันที่ เวลา"
                                            echo "<td>$issueType</td>";
                                            echo "<td>$issueSubtype</td>";
                                            echo "<td style='color: $statusColor;'>$statusText</td>";
                                            echo "<td><a href='allreport_user_read.php?report_id={$row["report_id"]}' class='btn btn-primary'>Read</a></td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6'>No findings have been reported at this time.</td></tr>";
                                    }

                                    // ปิดการเชื่อมต่อฐานข้อมูล
                                    $connection->close();
                                    ?>
                                </tbody>
                            </table>

                            <!-- แสดงลิงก์หน้าแบ่งหน้า -->
                            <div class="pagination">
                                <?php
                                for ($page = 1; $page <= $totalPages; $page++) {
                                    if ($page == $currentPage) {
                                        echo "<a href='allreport_user.php?page=$page' class='btn btn-danger1'>$page</a>";
                                    } else {
                                        echo "<a href='allreport_user.php?page=$page' class='btn btn-danger1'>$page</a>";
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!-- End All Reports Section -->

    <!-- Include your footer here -->
    <?php include('component/footer.php'); ?>

</body>

</html>
