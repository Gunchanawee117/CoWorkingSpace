<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>
    
</body>
</html>

<?php
include('db_connection.php');
session_start();

// รับข้อมูลจากแบบฟอร์ม
$username = $_POST["user_id"];
$password = $_POST["password"];
$repassword = $_POST["repassword"]; // เพิ่มการรับรหัสผ่านซ้ำ
$Fname = $_POST["Fname"];
$Lname = $_POST["Lname"];
$email = $_POST["email"];
$phone = $_POST["phone"];

// ตรวจสอบว่ารหัสผ่านและรหัสผ่านที่ระบุซ้ำตรงกันหรือไม่
if ($password !== $repassword) {
    echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'รหัสผ่านไม่ตรงกัน',
                icon: 'error',
                showConfirmButton: false,
                timer: 2000, // 2 seconds
                timerProgressBar: true,
                onClose: () => {
                    window.location.href = 'login.php';
                }
            });
          </script>";
    exit; // ยกเลิกการดำเนินการหลังจากแสดง SweetAlert
}

// ตรวจสอบความสมบูรณ์ของชื่อผู้ใช้ (username)
if (preg_match('/[^A-Za-z0-9]/', $username)) {
    echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'ชื่อผู้ใช้มีอักขระพิเศษ',
                icon: 'error',
                showConfirmButton: false,
                timer: 2000, // 2 seconds
                timerProgressBar: true,
                onClose: () => {
                    window.location.href = 'login.php';
                }
            });
          </script>";
    exit; // ยกเลิกการดำเนินการหลังจากแสดง SweetAlert
}

// ตรวจสอบว่ามีผู้ใช้นี้ในระบบหรือยัง (คุณต้องป้อนคำสั่ง SQL ที่เหมาะสม)
$checkUserQuery = "SELECT * FROM tb_user WHERE user_id='$username'";
$result = $connection->query($checkUserQuery);

// ตรวจสอบว่าอีเมลหรือโทรศัพท์ซ้ำกันหรือไม่
$checkDuplicateQuery = "SELECT * FROM tb_user WHERE email='$email' OR phone='$phone'";
$duplicateResult = $connection->query($checkDuplicateQuery);

if ($result->num_rows > 0) {
    echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'มีชื่อผู้ใช้คนนี้แล้ว ไม่สามารถสมัครได้',
                icon: 'error',
                showConfirmButton: false,
                timer: 2000, // 2 seconds
                timerProgressBar: true,
                onClose: () => {
                    window.location.href = 'login.php';
                }
            });
          </script>";
} elseif ($duplicateResult->num_rows > 0) {
    echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'อีเมลหรือโทรศัพท์นี้มีคนใช้แล้ว',
                icon: 'error',
                showConfirmButton: false,
                timer: 2000, // 2 seconds
                timerProgressBar: true,
                onClose: () => {
                    window.location.href = 'login.php';
                }
            });
          </script>";
} else {
    // เพิ่มข้อมูลผู้ใช้ใหม่ลงในฐานข้อมูล (คุณต้องป้อนคำสั่ง SQL ที่เหมาะสม)
    $role = "U"; // กำหนดค่า role เป็น "U"
    $insertUserQuery = "INSERT INTO tb_user (user_id, password, Fname, Lname , email, phone, Role) VALUES ('$username', '$password', '$Fname', '$Lname', '$email', '$phone', '$role')";

    if ($connection->query($insertUserQuery) === TRUE) {
        echo "<script>
                Swal.fire({
                    title: 'Success!',
                    text: 'สมัครสมาชิกสำเร็จ กรุณาเข้าสู่ระบบ',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 2000, // 2 seconds
                    timerProgressBar: true,
                    onClose: () => {
                        window.location.href = 'login.php';
                    }
                });
             </script>";
    } else {
        echo "Error: " . $insertUserQuery . "<br>" . $connection->error;
    }
}

// ปิดการเชื่อมต่อกับฐานข้อมูล
$connection->close();
?>
