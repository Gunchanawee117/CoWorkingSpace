<?php
session_start();
include ('db_connection.php');

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $issue_date = $_POST['issue_date'];
    $issue_type = $_POST['issue_type'];
    $issue_subtype = $_POST['issue_subtype'];

    // ตรวจสอบว่า "อื่นๆ" ถูกเลือกและใช้ค่าจาก input field
    if ($issue_subtype === "อื่นๆ") {
        $issue_subtype = $_POST['other_issue_subtype'];
    }

    $issue_description = $_POST['issue_description'];

    $sql = "INSERT INTO reports (user_id, issue_date, issue_type, issue_subtype, issue_description, created_at, issue_status)
            VALUES ('$user_id', '$issue_date', '$issue_type', '$issue_subtype', '$issue_description', current_timestamp(), '1')";

    if ($connection->query($sql) === TRUE) {
        if (isset($_POST['submit'])) {
            $user_id = $_SESSION['user_id'];
            $issue_date = $_POST['issue_date'];
            $newDateFormat = date("d F Y", strtotime($issue_date));

            $sToken = "ffiBh6c9A2DrxCETp9H3Pj6xz8XKw8ZmARFD2390oI1";
            $sMessage = "\nแจ้งเตือนการรายงาน\n";
            $sMessage .= "[ผู้ใช้]\n";
            $sMessage .=  $user_id . "\n";
            $sMessage .= "วันที่เกิดปัญหา: \n" . $newDateFormat . "\n";
            $sMessage .= "ประเภทปัญหา :" . $issue_type . "\n";
            $sMessage .= "ปัญหาเกี่ยวกับ:" . $issue_subtype . "\n";
            $sMessage .= "เนื้อหา:" . $issue_description . "\n";

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
            
            // Display SweetAlert2 success message and redirect
            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'รายงานปัญหาเรียบร้อยแล้ว',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location.href = 'table_booking.php';
                    });
                </script>";
        }
    } else {
        // Display SweetAlert2 error message and redirect
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'เกิดข้อผิดพลาด: " . $connection->error . "'
                }).then(function() {
                    window.location.href = 'table_booking.php'; // You can redirect to an appropriate page
                });
            </script>";
    }
} else {
    // Display SweetAlert2 message and redirect
    echo "<script>
            Swal.fire({
                icon: 'info',
                title: 'กรุณาเข้าสู่ระบบก่อนรายงานปัญหา'
            }).then(function() {
                window.location.href = 'index.php'; // You can redirect to the login page
            });
        </script>";
}

$connection->close();
?>
