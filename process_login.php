<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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

// ตรวจสอบว่ามีผู้ใช้นี้ในระบบหรือไม่ (คุณต้องป้อนคำสั่ง SQL ที่เหมาะสม)
$checkUserQuery = "SELECT * FROM tb_user WHERE user_id='$username' AND password='$password'";
$result = $connection->query($checkUserQuery);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $role = $row['Role'];

    $_SESSION['user_id'] = $username;
    $_SESSION['Role'] = $role;

    if ($role === 'A') {
        echo "<script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Login Successful. Redirecting to admin page...',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 2000, // 2 seconds
                    timerProgressBar: true,
                    onClose: () => {
                        window.location.href = 'adminIndex.php';
                    }
                });
            </script>";
    } elseif ($role === 'U') {
        echo "<script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Login Successful. Redirecting to user page...',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 2000, // 2 seconds
                    timerProgressBar: true,
                    onClose: () => {
                        window.location.href = 'index.php';
                    }
                });
            </script>";
    } else {
        echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Invalid or unspecified role',
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 2000, // 2 seconds
                    timerProgressBar: true,
                    onClose: () => {
                        window.location.href = 'login.php';
                    }
                });
            </script>";
    }
} else {
    echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Invalid username or password',
                icon: 'error',
                showConfirmButton: false,
                timer: 2000, // 2 seconds
                timerProgressBar: true,
                onClose: () => {
                    window.location.href = 'login.php';
                }
            });
        </script>";
}

// ปิดการเชื่อมต่อกับฐานข้อมูล
$connection->close();
?>
