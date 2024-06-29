<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

</head>
<body>
    
</body>
</html>

<?php
session_start();
include('db_connection.php'); // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['user_id'])) {
    // ถ้าไม่ได้เข้าสู่ระบบ ให้เปลี่ยนเส้นทางไปยังหน้า login
    header("Location: login.php");
    exit();
}

// ตรวจสอบค่าที่ส่งมาจากฟอร์มและเก็บไว้ในตัวแปร
$user_id = $_SESSION['user_id']; // ดึงค่า user_id จากเซสชัน

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $day = $_POST['day'];
    $time = $_POST['time'];
    $title = $_POST['title'];

    // ตรวจสอบค่า title และกำหนดโซนตามเงื่อนไข
    if ($title === "Meeting") {
        $zones = array("A", "B", "C");
    } else {
        // ตรวจสอบก่อนว่าคีย์ "zone_preference" มีอยู่ใน $_POST
        $zones = isset($_POST['zone_preference']) ? $_POST['zone_preference'] : array();
    }

    $currentDate = date("Y-m-d");
    if ($day < $currentDate) {
        showErrorAlert("Booking Error", "ไม่สามารถจองห้องย้อนหลังได้");
    }
    

    // ตรวจสอบความครบถ้วนของข้อมูลการจอง
    if (empty($day) || empty($time) || empty($title) || empty($zones)) {
        showErrorAlert("Incomplete Booking", "กรุณาเลือกข้อมูลการจองให้ครบถ้วน");
    }

    // แปลงค่า zone จากอาร์เรย์เป็นสตริงเพื่อบันทึกลงในฐานข้อมูล
    $zone_string = implode(", ", $zones);

    // ตรวจสอบการจองที่มีโซนและเวลาเดียวกัน
    $existing_booking_sql = "SELECT * FROM booking WHERE day = '$day' AND time = '$time'";
    $existing_booking_result = $connection->query($existing_booking_sql);

    $conflicting_zones_list = []; // เก็บรายชื่อโซนที่ซ้ำกัน

    while ($row = $existing_booking_result->fetch_assoc()) {
        $existing_zones = explode(", ", $row['zone']);
        $intersection = array_intersect($zones, $existing_zones);

        if (!empty($intersection)) {
            $conflicting_zones_list = array_merge($conflicting_zones_list, $intersection);
        }
    }
    if (!empty($conflicting_zones_list)) {
        $conflicting_zones = implode(", ", array_unique($conflicting_zones_list));
        showErrorAlert("Booking Conflict", "ไม่สามารถจองได้เนื่องจากโซนที่คุณเลือกได้แก่โซน ($conflicting_zones) ถูกจองแล้ว");
    }
    
    

    // สร้างคำสั่ง SQL ในการเพิ่มข้อมูลการจอง
    $insert_booking_sql = "INSERT INTO booking (user_id, day, time, title, status, zone, usage_status)
        VALUES ('$user_id', '$day', '$time', '$title', 1, '$zone_string', 1)";

    if ($connection->query($insert_booking_sql) === TRUE) {
        // ส่งข้อความไปยัง Line
        if (isset($_POST['submit'])) {
            sendLineNotification($user_id, $day, $time, $title, $zones);
        }

        showSuccessAlert("Booking Successful", "Your booking has been successfully completed!");
    } else {
        showErrorAlert("Database Error", "Error: " . $insert_booking_sql . "<br>" . $connection->error);
    }
}

// ปิดการเชื่อมต่อกับฐานข้อมูล
$connection->close();

// ฟังก์ชันสำหรับแสดง SweetAlert2 แบบ error
function showErrorAlert($title, $text)
{
    echo '<script>
        Swal.fire({
            icon: "error",
            title: "' . $title . '",
            text: "' . $text . '",
        }).then(function() {
            window.location.href = "table_booking.php";
        });
    </script>';
    exit();
}

// ฟังก์ชันสำหรับแสดง SweetAlert2 แบบ success
function showSuccessAlert($title, $text)
{
    echo '<script>
        Swal.fire({
            icon: "success",
            title: "' . $title . '",
            text: "' . $text . '",
        }).then(function() {
            window.location.href = "table_booking.php";
        });
    </script>';
    exit();
}

// ฟังก์ชันสำหรับส่งข้อความ Line Notification
function sendLineNotification($user_id, $day, $time, $title, $zones)
{
    $newDateFormat = date("d F Y", strtotime($day));
    $sToken = "ffiBh6c9A2DrxCETp9H3Pj6xz8XKw8ZmARFD2390oI1";
    $sMessage = "\nมีการจองห้องใหม่จาก\n";
    $sMessage .= "[ผู้ใช้]\n";
    $sMessage .= $user_id . "\n";
    $sMessage .= "วันที่จอง: " . $newDateFormat . "\n";
    $sMessage .= "ช่วงเวลา :" . $time . "\n";
    $sMessage .= "หัวข้อ      :" . $title . "\n";
    $sMessage .= "โซนที่นั่ง :" . implode(", ", $zones) . "\n";

    $chOne = curl_init();
    curl_setopt($chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify");
    curl_setopt($chOne, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($chOne, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($chOne, CURLOPT_POST, 1);
    curl_setopt($chOne, CURLOPT_POSTFIELDS, "message=" . $sMessage);
    $headers = array('Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer ' . $sToken . '', );
    curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($chOne, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($chOne);

    //Result error 
    if (curl_error($chOne)) {
        echo 'error:' . curl_error($chOne);
    } else {
        $result_ = json_decode($result, true);
        echo "status : " . $result_['status'];
        echo "message : " . $result_['message'];
    }
    curl_close($chOne);

    // Include SweetAlert2
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>';
}
?>
