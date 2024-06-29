<?php
include('component/navbar.php');
include('db_connection.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['date']) && isset($_GET['zone']) && isset($_GET['time'])) {
    $selected_date = $_GET['date'];
    $selected_zone = $_GET['zone'];
    $selected_time = $_GET['time'];
} else {
    $selected_date = "";
    $selected_zone = "";
    $selected_time = "";
}
?>

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
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/booking.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <style>
        
        .elem-group {
            display: flex;
            flex-direction: row;
            align-items: center;
        }

        .elem-group label {
            margin-right: 10px;
        }

        .elem-group input[type="checkbox"] {
            display: none;
        }

        .elem-group input[type="checkbox"] + label::before {
            content: "\2022";
            display: inline-block;
            width: 16px;
            height: 16px;
            font-size: 18px;
            line-height: 16px;
            text-align: center;
            margin-right: 5px;
            border: 1px solid #ccc;
            border-radius: 50%;
            cursor: pointer;
        }

        .elem-group input[type="checkbox"]:checked + label::before {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
        }

        .elem-group label::after {
            content: attr(data-label);
            display: inline-block;
            border: 1px solid #ccc;
            cursor: pointer;
            width: 81.5%;
        }

        .elem-group input[type="checkbox"]:checked + label::after {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
            align-items: center;
            
        }

        .zone-image {
            width: 100px; /* ขนาดของรูปโซน */
            height: auto;
            filter: grayscale(100%); /* ทำให้รูปภาพเป็นสีเทาเมื่อ hover */
        }

        /* เพิ่มสไตล์เมื่อเลือกโซน */
        .elem-group input[type="checkbox"]:checked + label .zone-image {
            opacity: 0.9; /* ทำให้รูปภาพดูเงาเล็กน้อย */
            filter: grayscale(0%); /* ทำให้รูปภาพเป็นสีเทาเมื่อ hover */
            
        }

        /* เพิ่มเอฟเฟกต์เมื่อไม่เลือกโซน */
        .elem-group input[type="checkbox"]:not(:checked) + label:hover .zone-image {
            filter: grayscale(0%); /* ทำให้รูปภาพเป็นสีเทาเมื่อ hover */
            opacity: 1; /* ทำให้รูปภาพกลับมาสว่างเมื่อ hover */
        }
    </style>
</head>
<body>
<form action="process_booking.php" method="post" class="booking-body" onsubmit="return validateForm();">
    <br><br><br>
    <center><h2>Booking</h2></center>
    <div class="booking-elem-group">
        <label for="day" class="booking-label">Select Date</label>
        <input type="date" id="day" name="day" class="booking-input" value="<?php echo $selected_date; ?>" required>
    </div>

    <div class="booking-elem-group">
        <label class="booking-label">Select Time</label>
        <select id="time" name="time" class="booking-select" required>
            <option value="" <?php if ($selected_time === '') echo 'selected'; ?>>Choose your time</option>
            <option value="8:00-9:00" <?php if ($selected_time === '8:00-9:00') echo 'selected'; ?>>8:00-9:00</option>
            <option value="9:00-10:00" <?php if ($selected_time === '9:00-10:00') echo 'selected'; ?>>9:00-10:00</option>
            <option value="10:00-11:00" <?php if ($selected_time === '10:00-11:00') echo 'selected'; ?>>10:00-11:00</option>
            <option value="11:00-12:00" <?php if ($selected_time === '11:00-12:00') echo 'selected'; ?>>11:00-12:00</option>
            <option value="12:00-13:00" <?php if ($selected_time === '12:00-13:00') echo 'selected'; ?>>12:00-13:00</option>
            <option value="13:00-14:00" <?php if ($selected_time === '13:00-14:00') echo 'selected'; ?>>13:00-14:00</option>
            <option value="14:00-15:00" <?php if ($selected_time === '14:00-15:00') echo 'selected'; ?>>14:00-15:00</option>
            <option value="15:00-16:00" <?php if ($selected_time === '15:00-16:00') echo 'selected'; ?>>15:00-16:00</option>
            <option value="16:00-17:00" <?php if ($selected_time === '16:00-17:00') echo 'selected'; ?>>16:00-17:00</option>
        </select>
    </div>

    <div class="booking-elem-group">
        <label class="booking-label">Select Title</label>
        <select id="title" name="title" class="booking-select" required>
            <option value="">Choose your Title</option>
            <option value="Meeting">Meeting - [ ALL ]</option>
            <option value="Sub-Meeting">Sub-Meeting</option>
            <option value="Discussion">Discussion</option>
        </select>
    </div>
    <label>Select Zone:</label><br>
    <div class="elem-group">
        
        <input type="checkbox" id="zone_a" name="zone_preference[]" value="A" <?php if ($selected_zone === 'A') echo 'checked'; ?>>
        <label for="zone_a" data-label="Zone A">
            <img src="assets/img/portfolio/zoneA.jpg" alt="Zone A" class="zone-image">
        </label>
        <input type="checkbox" id="zone_b" name="zone_preference[]" value="B" <?php if ($selected_zone === 'B') echo 'checked'; ?>>
        <label for="zone_b" data-label="Zone B">
            <img src="assets/img/portfolio/zoneB.jpg" alt="Zone B" class="zone-image">
        </label>
        <input type="checkbox" id="zone_c" name="zone_preference[]" value="C" <?php if ($selected_zone === 'C') echo 'checked'; ?>>
        <label for="zone_c" data-label="Zone C">
            <img src="assets/img/portfolio/zoneC.jpg" alt="Zone C" class="zone-image">
        </label>
    </div>

    <br>
    <button type="submit" name="submit" class="booking-button">Book The Rooms</button>
</form>

<script src="assets/js/booking.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // เลือกองค์ประกอบ HTML
        var titleSelect = document.getElementById("title");
        var zoneA = document.getElementById("zone_a");
        var zoneB = document.getElementById("zone_b");
        var zoneC = document.getElementById("zone_c");

        // ตรวจสอบค่าเริ่มต้นของ title
        if (titleSelect.value === "Meeting") {
            zoneA.checked = true;
            zoneB.checked = true;
            zoneC.checked = true;
            zoneA.disabled = true;
            zoneB.disabled = true;
            zoneC.disabled = true;
        }

        // เพิ่มตัวตรวจสอบเหตุการณ์เมื่อเลือก Title
        titleSelect.addEventListener("change", function () {
            if (titleSelect.value === "Meeting") {
                zoneA.checked = true;
                zoneB.checked = true;
                zoneC.checked = true;
                zoneA.disabled = true;
                zoneB.disabled = true;
                zoneC.disabled = true;
            } else {
                zoneA.disabled = false;
                zoneB.disabled = false;
                zoneC.disabled = false;
            }
        });
    });

    function validateForm() {
    var day = document.getElementById("day").value;
    var time = document.getElementById("time").value;
    var title = document.getElementById("title").value;
    var zones = document.querySelectorAll('input[name="zone_preference[]"]:checked');

    if (day === "" || time === "" || title === "" || zones.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'กรุณากรอกข้อมูลให้ครบถ้วน',
            text: 'โปรดเลือกวันที่, เวลา, หัวข้อ และโซน',
        });
        return false;
    }

    // เพิ่มตรวจสอบเพิ่มเติมสำหรับการเลือกโซนที่เป็นรูปภาพ
    var zoneImages = document.querySelectorAll('.zone-image');
    var isZoneSelected = false;

    for (var i = 0; i < zoneImages.length; i++) {
        if (zones[i].checked) {
            isZoneSelected = true;
            break;
        }
    }

    if (!isZoneSelected) {
        Swal.fire({
            icon: 'error',
            title: 'กรุณาเลือกโซน',
            text: 'โปรดเลือกโซนที่ต้องการจอง',
        });
        return false;
    }
}

</script>

</body>
<?php include('component/footer.php'); ?>
</html>
