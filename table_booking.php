<?php
include('component/navbar.php');
include('db_connection.php'); // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['user_id'])) {
    // ถ้าไม่ได้เข้าสู่ระบบ ให้เปลี่ยนเส้นทางไปยังหน้า login
    header("Location: login.php");
    exit();
}

$selected_date = "";

if (!empty($_GET['date'])) {
    $selected_date = $_GET['date'];
} else {
    // ถ้า $_GET['date'] ไม่มีค่า ให้ใช้วันปัจจุบันเป็นค่าเริ่มต้น
    $selected_date = date('Y-m-d');
}

// ดึงข้อมูลการจองทั้งหมดในวันที่เลือก
$booking_sql = "SELECT * FROM booking WHERE day = '$selected_date' AND status = 1";
$booking_result = $connection->query($booking_sql);

// ดึงข้อมูลการจองแต่ละโซน
$zone_a = array();
$zone_b = array();
$zone_c = array();

while ($row = $booking_result->fetch_assoc()) {
    $zone = explode(", ", $row['zone']);
    $time = $row['time'];
    $title = $row['title'];

    foreach ($zone as $z) {
        // ตรวจสอบ title เพื่อกำหนดคลาส CSS
        if (strpos($title, 'meeting') !== false) {
            $class = 'meeting';
        } elseif (strpos($title, 'discussion') !== false) {
            $class = 'discussion';
        } elseif (strpos($title, 'submeeting') !== false) {
            $class = 'submeeting';
        } else {
            $class = '';
        }

        // ตรวจสอบโซนและเวลาแล้วกำหนดข้อมูลในตาราง
        switch ($z) {
            case 'A':
                $zone_a[$time] = "<td class='$class booked-slot' id='A-$time' onclick=\"bookSlot('A', '$time', '$selected_date', 'Zone A')\">$title</td>";
                break;
            case 'B':
                $zone_b[$time] = "<td class='$class booked-slot' id='B-$time' onclick=\"bookSlot('B', '$time', '$selected_date', 'Zone B')\">$title</td>";
                break;
            case 'C':
                $zone_c[$time] = "<td class='$class booked-slot' id='C-$time' onclick=\"bookSlot('C', '$time', '$selected_date', 'Zone C')\">$title</td>";
                break;
        }
    }
}

// ปิดการเชื่อมต่อกับฐานข้อมูล
$connection->close();
?>

<!DOCTYPE html>
<html lang="en">


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
    <link href="assets/css/table.css" rel="stylesheet">
    <link href="assets/css/banner.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

</head>


<body>

    <!-- ======= Hero Section ======= -->
    <section id="hero" style="height: 60vh;">
        <div id="heroCarousel" data-bs-interval="5000" class="carousel slide carousel-fade" data-bs-ride="carousel">

            <ol class="carousel-indicators" id="hero-carousel-indicators"></ol>

            <div class="carousel-inner" role="listbox">

                <!-- Slide 1 -->
                <div class="carousel-item active" style="background-image: url(assets/img/portfolio/room2.jpg)">
                    <div class="carousel-container">
                        <div class="container">
                            <h2 class="animate__animated animate__fadeInDown">Booking</h2>
                            <p class="animate__animated animate__fadeInUp">ระบบจองห้อง Co Working Space
                                สำหรับนิสิตนักศึกษา
                                มหาวิทยาลัยทักษิณ ประชุม พูดคุยปรึกษา หรือทำงานร่วมกัน - Co Working Space reservation
                                system for Thaksin
                                University students to meet, discuss or collaborate .</p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!-- End Hero -->

    <!-- ======= Breadcrumbs ======= -->
    <section id="breadcrumbs" class="breadcrumbs" style="margin-top: 10px;">
        <div class="container">
            <hr>
            <div class="d-flex justify-content-between align-items-center">

                <h2>Table Booking</h2>
                <ol>
                    <li><a href="index.php">Home</a></li>
                    <li>Timetable</li>
                </ol>
            </div>

        </div>
    </section><!-- End Breadcrumbs -->



    <br><br>
    <form action="table_booking.php" method="post">
        <input class="btn btn-danger1" style="padding: 5px;" type="button" value="ย้อนหลัง" onclick="previousDay()">
        <input type="date" id="date" onchange="changeDate()" <?php echo !empty($selected_date) ? 'value="' . $selected_date . '"' : ''; ?>>
        <input class="btn btn-danger1" style="padding: 5px;" type="button" value="ข้างหน้า" onclick="nextDay()">
    </form>
    <table>
        <thead>
            <tr>
                <th>โซน/ช่วงเวลา</th>
                <th>8:00-9:00</th>
                <th>9:00-10:00</th>
                <th>10:00-11:00</th>
                <th>11:00-12:00</th>
                <th>12:00-13:00</th>
                <th>13:00-14:00</th>
                <th>14:00-15:00</th>
                <th>15:00-16:00</th>
                <th>16:00-17:00</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Zone A</td>
                <?php
                echo $zone_a['8:00-9:00'] ?? "<td class='empty-slot' id='A-8:00-9:00' onclick=\"bookSlot('A', '8:00-9:00', '$selected_date', 'Zone A')\">ว่าง</td>";
                echo $zone_a['9:00-10:00'] ?? "<td class='empty-slot' id='A-9:00-10:00' onclick=\"bookSlot('A', '9:00-10:00', '$selected_date', 'Zone A')\">ว่าง</td>";
                echo $zone_a['10:00-11:00'] ?? "<td class='empty-slot' id='A-10:00-11:00' onclick=\"bookSlot('A', '10:00-11:00', '$selected_date', 'Zone A')\">ว่าง</td>";
                echo $zone_a['11:00-12:00'] ?? "<td class='empty-slot' id='A-11:00-12:00' onclick=\"bookSlot('A', '11:00-12:00', '$selected_date', 'Zone A')\">ว่าง</td>";
                echo $zone_a['12:00-13:00'] ?? "<td class='empty-slot' id='A-12:00-13:00' onclick=\"bookSlot('A', '12:00-13:00', '$selected_date', 'Zone A')\">ว่าง</td>";
                echo $zone_a['13:00-14:00'] ?? "<td class='empty-slot' id='A-13:00-14:00' onclick=\"bookSlot('A', '13:00-14:00', '$selected_date', 'Zone A')\">ว่าง</td>";
                echo $zone_a['14:00-15:00'] ?? "<td class='empty-slot' id='A-14:00-15:00' onclick=\"bookSlot('A', '14:00-15:00', '$selected_date', 'Zone A')\">ว่าง</td>";
                echo $zone_a['15:00-16:00'] ?? "<td class='empty-slot' id='A-15:00-16:00' onclick=\"bookSlot('A', '15:00-16:00', '$selected_date', 'Zone A')\">ว่าง</td>";
                echo $zone_a['16:00-17:00'] ?? "<td class='empty-slot' id='A-16:00-17:00' onclick=\"bookSlot('A', '16:00-17:00', '$selected_date', 'Zone A')\">ว่าง</td>";
                ?>
            </tr>
            <tr>
                <td>Zone B</td>
                <?php
                echo $zone_b['8:00-9:00'] ?? "<td class='empty-slot' id='B-8:00-9:00' onclick=\"bookSlot('B', '8:00-9:00', '$selected_date', 'Zone B')\">ว่าง</td>";
                echo $zone_b['9:00-10:00'] ?? "<td class='empty-slot' id='B-9:00-10:00' onclick=\"bookSlot('B', '9:00-10:00', '$selected_date', 'Zone B')\">ว่าง</td>";
                echo $zone_b['10:00-11:00'] ?? "<td class='empty-slot' id='B-10:00-11:00' onclick=\"bookSlot('B', '10:00-11:00', '$selected_date', 'Zone B')\">ว่าง</td>";
                echo $zone_b['11:00-12:00'] ?? "<td class='empty-slot' id='B-11:00-12:00' onclick=\"bookSlot('B', '11:00-12:00', '$selected_date', 'Zone B')\">ว่าง</td>";
                echo $zone_b['12:00-13:00'] ?? "<td class='empty-slot' id='B-12:00-13:00' onclick=\"bookSlot('B', '12:00-13:00', '$selected_date', 'Zone B')\">ว่าง</td>";
                echo $zone_b['13:00-14:00'] ?? "<td class='empty-slot' id='B-13:00-14:00' onclick=\"bookSlot('B', '13:00-14:00', '$selected_date', 'Zone B')\">ว่าง</td>";
                echo $zone_b['14:00-15:00'] ?? "<td class='empty-slot' id='B-14:00-15:00' onclick=\"bookSlot('B', '14:00-15:00', '$selected_date', 'Zone B')\">ว่าง</td>";
                echo $zone_b['15:00-16:00'] ?? "<td class='empty-slot' id='B-15:00-16:00' onclick=\"bookSlot('B', '15:00-16:00', '$selected_date', 'Zone B')\">ว่าง</td>";
                echo $zone_b['16:00-17:00'] ?? "<td class='empty-slot' id='B-16:00-17:00' onclick=\"bookSlot('B', '16:00-17:00', '$selected_date', 'Zone B')\">ว่าง</td>";
                ?>
            </tr>
            <tr>
                <td>Zone C</td>
                <?php
                echo $zone_c['8:00-9:00'] ?? "<td class='empty-slot' id='C-8:00-9:00' onclick=\"bookSlot('C', '8:00-9:00', '$selected_date', 'Zone C')\">ว่าง</td>";
                echo $zone_c['9:00-10:00'] ?? "<td class='empty-slot' id='C-9:00-10:00' onclick=\"bookSlot('C', '9:00-10:00', '$selected_date', 'Zone C')\">ว่าง</td>";
                echo $zone_c['10:00-11:00'] ?? "<td class='empty-slot' id='C-10:00-11:00' onclick=\"bookSlot('C', '10:00-11:00', '$selected_date', 'Zone C')\">ว่าง</td>";
                echo $zone_c['11:00-12:00'] ?? "<td class='empty-slot' id='C-11:00-12:00' onclick=\"bookSlot('C', '11:00-12:00', '$selected_date', 'Zone C')\">ว่าง</td>";
                echo $zone_c['12:00-13:00'] ?? "<td class='empty-slot' id='C-12:00-13:00' onclick=\"bookSlot('C', '12:00-13:00', '$selected_date', 'Zone C')\">ว่าง</td>";
                echo $zone_c['13:00-14:00'] ?? "<td class='empty-slot' id='C-13:00-14:00' onclick=\"bookSlot('C', '13:00-14:00', '$selected_date', 'Zone C')\">ว่าง</td>";
                echo $zone_c['14:00-15:00'] ?? "<td class='empty-slot' id='C-14:00-15:00' onclick=\"bookSlot('C', '14:00-15:00', '$selected_date', 'Zone C')\">ว่าง</td>";
                echo $zone_c['15:00-16:00'] ?? "<td class='empty-slot' id='C-15:00-16:00' onclick=\"bookSlot('C', '15:00-16:00', '$selected_date', 'Zone C')\">ว่าง</td>";
                echo $zone_c['16:00-17:00'] ?? "<td class='empty-slot' id='C-16:00-17:00' onclick=\"bookSlot('C', '16:00-17:00', '$selected_date', 'Zone C')\">ว่าง</td>";
                ?>
            </tr>
        </tbody>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <hr>
    <center class="animate__animated animate__fadeInUp">
        <button class="btn btn-danger1"><a href="history_booking.php">ประวัติการจองของฉัน</a></button>
        <button class="btn btn-danger1"><a href="cancel_booking.php">ข้อมูลการจองในปัจจุบัน</a></button>
        <button class="btn btn-danger1"><a href="report.php">แจ้งปัญหา & รีพอร์ต</a></button>
    </center>
    <script>
        // JavaScript เพื่อคลิกปุ่มวันก่อนหน้า
        function previousDay() {
            var currentDate = new Date(document.getElementById('date').value);
            currentDate.setDate(currentDate.getDate() - 1);
            var formattedDate = formatDate(currentDate);
            document.getElementById('date').value = formattedDate;
            changeDate();
        }

        // JavaScript เพื่อคลิกปุ่มวันถัดไป
        function nextDay() {
            var currentDate = new Date(document.getElementById('date').value);
            currentDate.setDate(currentDate.getDate() + 1);
            var formattedDate = formatDate(currentDate);
            document.getElementById('date').value = formattedDate;
            changeDate();
        }

        // JavaScript เพื่อเปลี่ยนวันที่เมื่อเลือกจาก Date Input
        function changeDate() {
            var selectedDate = document.getElementById('date').value;
            window.location.href = 'table_booking.php?date=' + selectedDate;
        }

        // JavaScript เพื่อจอง Slot
        // JavaScript เพื่อกำหนดรูปแบบวันที่
        function formatDate(date) {
        var day = date.getDate();
        var month = date.getMonth() + 1;
        var year = date.getFullYear();
        if (day < 10) {
            day = '0' + day;
        }
        if (month < 10) {
            month = '0' + month;
        }
        return year + '-' + month + '-' + day;
    }

    function bookSlot(zone, time, date, zoneName) {
        var elementId = zone + '-' + time;
        var element = document.getElementById(elementId);

        // ตรวจสอบว่าวันที่ที่ต้องการจองไม่ใช่วันในอดีต
        var selectedDate = new Date(date);
        var currentDate = new Date();
        if (selectedDate < currentDate) {
            // ใช้ SweetAlert แทน alert
            Swal.fire({
                icon: 'error',
                title: 'ไม่สามารถจอง Slot ในอดีตได้',
                text: 'โปรดเลือกวันที่อื่นที่ไม่ใช่วันในอดีต',
            });
            return;
        }

        if (element && !element.classList.contains('booked-slot')) {
            Swal.fire({
                icon: 'question',
                title: 'คุณต้องการจอง Slot ' + time + ' ในโซน ' + zoneName + ' ในวันที่ ' + date + ' ใช่หรือไม่?',
                showCancelButton: true,
                confirmButtonText: 'ใช่',
                cancelButtonText: 'ไม่',
            }).then((result) => {
                if (result.isConfirmed) {
                    // ส่งข้อมูลไปยังหน้าต่อไป
                    window.location.href = 'booking.php?zone=' + zone + '&time=' + time + '&date=' + date;
                }
            });
        }
    }

    </script>


    <script src="assets/js/main.js"></script>

</body>
<br><br><br>
<?php
include('component/footer.php');
?>

</html>