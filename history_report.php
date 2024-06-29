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
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>

<?php include('component/navbar.php'); ?>

<body>
    <!-- ======= Breadcrumbs ======= -->
    <section id="breadcrumbs" class="breadcrumbs">
        <div class="container">
            <hr>
            <div class="d-flex justify-content-between align-items-center">
                <h2>ประวัติการรายงานของฉัน</h2>
                <ol>
                    <li><a href="index.php">หน้าแรก</a></li>
                    <li><a href="table_booking.php">TimeTable</a></li>
                    <li><a href="report.php">report</a></li>
                    <li>History report</li>
                </ol>
            </div>
        </div>
    </section><!-- End Breadcrumbs -->

    <!-- ======= Report History ======= -->
    <section id="report-history" class="report-history">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="filter-options">
                        <label for="filterReportTime">วันที่:</label>
                        <input type="text" id="filterReportTime" name="filterReportTime">
                        <button id="filterButton" class="btn btn-danger1">ค้นหา</button>
                        <button id="clearFilterButton" class="btn btn-secondary">แสดงทั้งหมด</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>วันที่รายงาน</th>
                                    <th>ประเภทรายงาน</th>
                                    <th>รายละเอียด</th>
                                    <th>สถานะ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // เริ่มต้นการเชื่อมต่อกับฐานข้อมูล
                                include('db_connection.php');

                                // ดึงรายการรายงานของผู้ใช้ที่เข้าสู่ระบบ
                                $user_id = $_SESSION['user_id'];

                                // ดำเนินการหน้าแรก (หน้าที่ 1) หากไม่มีค่าหน้า
                                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                                $items_per_page = 20;
                                $offset = ($page - 1) * $items_per_page;

                                // ดึงข้อมูลจากตาราง 'reports' โดยใช้ LIMIT และ OFFSET
                                $report_sql = "SELECT * FROM reports WHERE user_id = '$user_id' ORDER BY issue_date DESC, created_at DESC LIMIT $items_per_page OFFSET $offset";
                                $report_result = $connection->query($report_sql);

                                if ($report_result->num_rows > 0) {
                                    while ($row = $report_result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>{$row['issue_date']}</td>";
                                        echo "<td>{$row['issue_type']}</td>";
                                        echo "<td>{$row['issue_description']}</td>";
                                        echo "<td>";
                                        if ($row['issue_status'] == 1) {
                                            echo "รอการตรวจสอบจากผู้ดูแล";
                                        } else {
                                            echo "ผู้ดูแลได้ตรวจสอบและแก้ไขแล้ว";
                                        }
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                }

                                // ปิดการเชื่อมต่อกับฐานข้อมูล
                                $connection->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section><!-- End Report History -->

    <!-- Pagination links -->
    <div class="pagination" style="justify-content: center;">
        <style>
            .btnnum {
                padding: 5px;
                background-color: gold;
                margin: 2px;
                border-radius: 5px;
                color: white;
            }

            .btnint {
                padding: 5px;
                border: 0.5px solid #000;
                margin: 2px;
                border-radius: 5px;
            }

            .pagination {
                align-items: center;
            }
        </style>

        <?php
        // ทำการเชื่อมต่อกับฐานข้อมูลอีกครั้งเพื่อคำนวณจำนวนรายการทั้งหมดสำหรับ pagination
        include('db_connection.php');

        $total_items_sql = "SELECT COUNT(*) AS total FROM reports WHERE user_id = '$user_id'";
        $total_items_result = $connection->query($total_items_sql);
        $total_items = $total_items_result->fetch_assoc()['total'];
        $total_pages = ceil($total_items / $items_per_page);

        if ($page > 1) {
            echo '<a href="history_report.php?page=1" class="btnnum">หน้าแรก</a>';
            echo '<a href="history_report.php?page=' . ($page - 1) . '" class="btnnum">ก่อนหน้า</a>';
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            echo '<a ';
            if ($i === $page) {
                echo 'class="active btnint"';
            }
            echo 'class="btnint" href="history_report.php?page=' . $i . '">' . $i . '</a>';
        }

        if ($page < $total_pages) {
            echo '<a class="btnnum" href="history_report.php?page=' . ($page + 1) . '">ถัดไป</a>';
            echo '<a href="history_report.php?page=' . $total_pages . '" class="btnnum">หน้าสุดท้าย</a>';
        }

        // ปิดการเชื่อมต่อกับฐานข้อมูล
        $connection->close();
        ?>
    </div>

    <script>
    $(function() {
        $("#filterReportTime").datepicker({
            dateFormat: 'yy-mm-dd', // รูปแบบวันที่
            changeMonth: true,      // เปลี่ยนเดือนได้
            changeYear: true,       // เปลี่ยนปีได้
            showButtonPanel: true   // แสดงปุ่ม Today
        });

        $("#filterButton").on("click", function() {
            // ดึงวันที่ที่เลือกจาก Datepicker
            var selectedDate = $("#filterReportTime").datepicker("getDate");

            // แปลงวันที่เป็นรูปแบบของ SQL DATE (yyyy-mm-dd)
            var formattedDate = $.datepicker.formatDate("yy-mm-dd", selectedDate);

            // รีเซ็ตการแสดงผลทั้งหมดก่อนที่จะแสดงผลค้นหาใหม่
            $("tbody tr").show();

            // ค้นหาและแสดงรายงานตามวันที่ที่เลือก
            $("tbody tr").each(function() {
                var rowReportTime = $(this).children("td:nth-child(1)").text();
                if (formattedDate !== rowReportTime) {
                    $(this).hide();
                }
            });
        });

        $("#clearFilterButton").on("click", function() {
            // ล้างค่าวันที่ที่เลือกใน Datepicker
            $("#filterReportTime").val("");

            // รีเซ็ตการแสดงผลทั้งหมด
            $("tbody tr").show();
        });
    });
</script>

</body>

<br><br><br><br>
<?php include('component/footer.php'); ?>
</html>
