<?php
// เริ่ม session
session_start();

// ล้างค่า session ทั้งหมด
session_unset();

// ทำลาย session
session_destroy();

// ส่งกลับไปยังหน้าหลักหรือหน้าล็อกอิน (แก้ไข URL ตามที่คุณต้องการ)
header("Location: index.php"); // เปลี่ยน URL ตามที่คุณต้องการให้กลับไป

// ออกจากสคริปต์
exit();
?>
