<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Admin Index</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
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
  <?php
  include('component/navbar.php');
  ?>



  <br>
  <!-- overlay -->
  <div id="sidebar-overlay" class="overlay w-100 vh-100 position-fixed d-none"></div>

  <!-- sidebar -->




  <!-- main content -->
  <main class="p-4 min-vh-100">


    <section class="row">

      <div class="jumbotron jumbotron-fluid rounded bg-white border-0 shadow-sm border-left px-4">
        <div class="container">
          <h1 class="display-4 mb-2 text-primary">CoWorkingSpace</h1>

        </div>
      </div>
      <br><br><br><hr>
      <div class="col-md-6 col-lg-4">
        <article class="p-4 rounded shadow-sm border-left mb-4">
          <a href="admin_alluser.php" class="d-flex align-items-center">
            <span class="bi bi-person h5"></span>
            <h5 class="ml-2">. ผู้ใช้ทั้งหมด</h5>
          </a>
        </article>
      </div>

      <div class="col-md-6 col-lg-4">
        <article class="p-4 rounded shadow-sm border-left mb-4">
          <a href="admin_allbooking.php" class="d-flex align-items-center">
            <span class="bi bi-clock-history h5"></span>
            <h5 class="ml-2">. การจองทั้งหมด</h5>
          </a>
        </article>
      </div>



      <div class="col-md-6 col-lg-4">
        <article class="p-4 rounded shadow-sm border-left mb-4">
          <a href="admin_usage.php" class="d-flex align-items-center">
            <span class="bi bi-person-check h5"></span>
            <h5 class="ml-2">. ตรวจสอบการใช้งานห้อง </h5>
          </a>
        </article>
      </div>

      <div class="col-md-6 col-lg-4">
        <article class="p-4 rounded shadow-sm border-left mb-4">
          <a href="admin_allreport.php" class="d-flex align-items-center">
            <span class="bi bi-box h5"></span>
            <h5 class="ml-2">. การรีพอร์ตทั้งหมด</h5>
          </a>
        </article>
      </div>


    </section>


  </main>
  </div>
  <br>
  <br>
  <br>



  <?php
  include('component/footer.php');
  ?>
</body>

</html>