<?php include ('component/navbar.php');?>
<!DOCTYPE html>
<html>
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
<style>
  form {
    margin-top: 20px;
    background-color: #f7f7f7;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    margin-left: 10%;
    margin-right: 10%;
    padding-bottom: 25px;
  }

  label {
    display: block;
    margin-bottom: 10px;
  }

  input[type="date"],
  select,
  textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
  }
</style>
</head>
<body>
  <!-- ======= Breadcrumbs ======= -->
  <section id="breadcrumbs" class="breadcrumbs">
    <div class="container">
      <hr>
      <div class="d-flex justify-content-between align-items-center">
        <h2>รีพอร์ต</h2>
        <ol>
          <li><a href="index.php">Home</a></li>
          <li><a href="table_booking.php">Timetable</a></li>
          <li>report</li>
        </ol>
      </div>
    </div>
  </section><!-- End Breadcrumbs -->
  <form method="post" action="process_report.php">
    <label for="issue_date">วันที่:</label>
    <input type="date" name="issue_date" required><br><br>

    <label for="issue_type">ประเภทปัญหา:</label>
    <select name="issue_type" id="issue_type" required>
      <option value="">กรุณาเลือกประเภทปัญหา</option>
      <option value="เฟอนิเจอร์">เฟอนิเจอร์</option>
      <option value="เครื่องใช้ไฟฟ้า">เครื่องใช้ไฟฟ้า</option>
    </select>

    <label for="issue_subtype">ปัญหาที่เกิด:</label>
    <select name="issue_subtype" id="issue_subtype" required>
      <option value="">กรุณาเลือกประเภทปัญหา</option>
      <option value="other">อื่นๆ</option> <!-- เพิ่ม option สำหรับ "อื่นๆ" -->
    </select>

    <!-- เพิ่ม input element สำหรับกรอกข้อความ (เริ่มต้นซ่อนไว้) -->
    <input type="text" name="other_issue_subtype" id="other_issue_subtype" style="display: none;" placeholder="กรุณาระบุปัญหาที่เกิด">

    <label for="issue_description">เนื้อหา:</label><br>
    <textarea name="issue_description" rows="4" cols="50" required></textarea><br><br>
    <input class="btn btn-danger1" type="submit" name="submit" value="ส่งรายงาน">
    <a href="history_report.php" class="btn btn-danger1" style="margin-left: 50px;">ประวัติการ report</a>
  </form>

  <script>
    document.getElementById("issue_type").addEventListener("change", function() {
      var issueType = document.getElementById("issue_type").value;
      var issueSubtypeSelect = document.getElementById("issue_subtype");
      var otherIssueSubtypeInput = document.getElementById("other_issue_subtype"); // เพิ่มตัวแปรสำหรับ input element

      // Clear existing options
      issueSubtypeSelect.innerHTML = "";

      // Populate options based on the selected issue_type
      if (issueType === "เฟอนิเจอร์") {
        var furnitureOptions = ["โต๊ะ", "เก้าอี้", "เพดาน", "พื้น", "ชั้นวางของ", "อื่นๆ"];
        for (var i = 0; i < furnitureOptions.length; i++) {
          var option = document.createElement("option");
          option.text = furnitureOptions[i];
          issueSubtypeSelect.appendChild(option);
        }
      } else if (issueType === "เครื่องใช้ไฟฟ้า") {
        var electricalOptions = ["เครื่องปรับอากาศ", "ปลั๊กไฟ", "หลอดไฟ", "ทีวี", "อื่นๆ"];
        for (var i = 0; i < electricalOptions.length; i++) {
          var option = document.createElement("option");
          option.text = electricalOptions[i];
          issueSubtypeSelect.appendChild(option);
        }
      } else if (issueType === "other") {
        // เมื่อผู้ใช้เลือก "อื่นๆ" แสดง input element สำหรับกรอกข้อความ
        otherIssueSubtypeInput.style.display = "block";
        otherIssueSubtypeInput.setAttribute("name", "issue_subtype"); // ตั้งชื่อ input element เป็น "issue_subtype"
      } else {
        // หากไม่เลือก "อื่นๆ" ซ่อน input element
        otherIssueSubtypeInput.style.display = "none";
        otherIssueSubtypeInput.removeAttribute("name"); // ลบชื่อ input element
      }
    });
  </script>
</body>
<?php
include ('component/footer.php');?>
</html>
