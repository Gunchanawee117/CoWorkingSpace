<?php
include('component/navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>ประวัติการจองของฉัน</title>
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
    <!-- ======= Breadcrumbs ======= -->
    <section id="breadcrumbs" class="breadcrumbs">
        <div class="container">
            <hr>
            <div class="d-flex justify-content-between align-items-center">
                <h2>ประวัติการจองของฉัน</h2>
                <ol>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="table_booking.php">TimeTable</a></li>
                    <li>ประวัติ</li>
                </ol>
            </div>
        </div>
    </section><!-- End Breadcrumbs -->

    <!-- ======= Booking History ======= -->
    <section id="booking-history" class="booking-history">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">

                    <div class="filter-options">
                        <label for="filterTitle">หัวข้อ:</label>
                        <select id="filterTitle">
                            <option value="">ทั้งหมด</option>
                            <option value="Meeting">Meeting</option>
                            <option value="Sub-Meeting">Sub-Meeting</option>
                            <option value="Discussion">discussion</option>
                        </select>

                        <label for="filterTime">เวลา:</label>
                        <select id="filterTime">
                            <option value="">ทั้งหมด</option>
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

                        <label for="filterDate">วันที่:</label>
                        <input type="date" id="filterDate">

                        <button id="filterButton" class="btn btn-danger1">ค้นหา</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>วันที่</th>
                                    <th>เวลา</th>
                                    <th>หัวข้อ</th>
                                    <th>โซน</th>
                                    <th>สถานะการจอง</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // เริ่มต้นการเชื่อมต่อกับฐานข้อมูล
                                include('db_connection.php');

                                // ดึงรายการการจองของผู้ใช้ที่เข้าสู่ระบบ
                                $user_id = $_SESSION['user_id'];

                                // ดำเนินการหน้าแรก (หน้าที่ 1) หากไม่มีค่าหน้า
                                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                                $items_per_page = 20;
                                $offset = ($page - 1) * $items_per_page;

                                // ดึงข้อมูลจากตาราง 'booking' ที่ไม่ถูกยกเลิกและอยู่ในอดีต
                                $history_sql = "SELECT * FROM booking WHERE user_id = '$user_id' AND status = 1 AND day < CURDATE() ORDER BY day DESC, time DESC LIMIT $items_per_page OFFSET $offset";
                                $history_result = $connection->query($history_sql);

                                // ดึงข้อมูลจากตาราง 'cancel_booking' โดยใช้ LIMIT และ OFFSET
                                $cancel_history_sql = "SELECT * FROM cancel_booking WHERE user_id = '$user_id' ORDER BY day DESC, time DESC LIMIT $items_per_page OFFSET $offset";
                                $cancel_history_result = $connection->query($cancel_history_sql);

                                // รวมข้อมูลจากทั้งสองตารางเข้าด้วยกัน
                                $combined_history = array();

                                // เพิ่มข้อมูลจากตาราง 'booking' เข้าในอาร์เรย์ผลลัพธ์
                                while ($row = $history_result->fetch_assoc()) {
                                    $combined_history[] = $row;
                                }

                                // เพิ่มข้อมูลจากตาราง 'cancel_booking' เข้าในอาร์เรย์ผลลัพธ์
                                while ($row = $cancel_history_result->fetch_assoc()) {
                                    $combined_history[] = $row;
                                }

                                // เรียงลำดับข้อมูลในอาร์เรย์ผลลัพธ์ตามวันและเวลา
                                usort($combined_history, function($a, $b) {
                                    $dateA = strtotime($a['day']);
                                    $dateB = strtotime($b['day']);
                                    $timeA = strtotime($a['time']);
                                    $timeB = strtotime($b['time']);

                                    if ($dateA == $dateB) {
                                        return $timeA - $timeB;
                                    }

                                    return $dateB - $dateA;
                                });

                                foreach ($combined_history as $row) {
                                    echo "<tr>";
                                    echo "<td>{$row['day']}</td>";
                                    echo "<td>{$row['time']}</td>";
                                    echo "<td>{$row['title']}</td>";
                                    echo "<td>{$row['zone']}</td>";
                                    echo "<td>";
                                    if ($row['status'] == 2) {
                                        echo "ถูกยกเลิก";
                                    } else {
                                        echo "เสร็จสิ้น";
                                    }
                                    echo "</td>";
                                    echo "</tr>";
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
    </section><!-- End Booking History -->

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

        $total_items_sql = "SELECT COUNT(*) AS total FROM booking WHERE user_id = '$user_id' AND status = 1 AND day < CURDATE()";
        $total_items_result = $connection->query($total_items_sql);
        $total_items = $total_items_result->fetch_assoc()['total'];
        $total_pages = ceil($total_items / $items_per_page);

        if ($page > 1) {
            echo '<a href="history_booking.php?page=1" class="btnnum">หน้าแรก</a>';
            echo '<a href="history_booking.php?page=' . ($page - 1) . '" class="btnnum">ก่อนหน้า</a>';
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            echo '<a ';
            if ($i === $page) {
                echo 'class="active btnint"';
            }
            echo 'class="btnint" href="history_booking.php?page=' . $i . '">' . $i . '</a>';
        }

        if ($page < $total_pages) {
            echo '<a class="btnnum" href="history_booking.php?page=' . ($page + 1) . '">ถัดไป</a>';
            echo '<a href="history_booking.php?page=' . $total_pages . '" class="btnnum">หน้าสุดท้าย</a>';
        }

        // ปิดการเชื่อมต่อกับฐานข้อมูล
        $connection->close();
        ?>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const filterTitle = document.getElementById("filterTitle");
            const filterTime = document.getElementById("filterTime");
            const filterDate = document.getElementById("filterDate");
            const filterButton = document.getElementById("filterButton");
            const tableRows = document.querySelectorAll("tbody tr");

            filterButton.addEventListener("click", function () {
                const selectedTitle = filterTitle.value;
                const selectedTime = filterTime.value;
                const selectedDate = filterDate.value;

                tableRows.forEach(function (row) {
                    const rowTitle = row.children[2].textContent;
                    const rowTime = row.children[1].textContent;
                    const rowDate = row.children[0].textContent;

                    if (
                        (!selectedTitle || rowTitle === selectedTitle) &&
                        (!selectedTime || rowTime === selectedTime) &&
                        (!selectedDate || rowDate === selectedDate)
                    ) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                });
            });
        });
    </script>
</body>
<br><br><br><br>
<?php include('component/footer.php'); ?>
</html>
