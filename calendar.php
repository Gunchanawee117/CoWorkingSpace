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
  <link href="assets/css/style.css" rel="stylesheet">
  <link href="assets/css/calendar.css" rel="stylesheet">
</head>

<body>

<?php
include('component/navbar.php');
?>


  <div class="container1">
    <div class="calendar">
      <div class="front">
        <div class="current-date">
          <h1>Friday 15th</h1>
          <h1>January 2016</h1>
        </div>

        <div class="current-month">
          <ul class="week-days">
            <li>MON</li>
            <li>TUE</li>
            <li>WED</li>
            <li>THU</li>
            <li>FRI</li>
            <li>SAT</li>
            <li>SUN</li>
          </ul>

          <div class="weeks">
            <div class="first">
              <span class="last-month">28</span>
              <span class="last-month">29</span>
              <span class="last-month">30</span>
              <span class="last-month">31</span>
              <span>01</span>
              <span>02</span>
              <span>03</span>
            </div>

            <div class="second">
              <span>04</span>
              <span>05</span>
              <span class="event">06</span>
              <span>07</span>
              <span>08</span>
              <span>09</span>
              <span>10</span>
            </div>

            <div class="third">
              <span>11</span>
              <span>12</span>
              <span>13</span>
              <span>14</span>
              <span class="active">15</span>
              <span>16</span>
              <span>17</span>
            </div>

            <div class="fourth">
              <span>18</span>
              <span>19</span>
              <span>20</span>
              <span>21</span>
              <span>22</span>
              <span>23</span>
              <span>24</span>
            </div>

            <div class="fifth">
              <span>25</span>
              <span>26</span>
              <span>27</span>
              <span>28</span>
              <span>29</span>
              <span>30</span>
              <span>31</span>
            </div>
          </div>
        </div>

      </div>

      <div class="back">
        <input placeholder="What's the event?">
        <div class="info">
          <div class="date">
            <p class="info-date">
              Date: <span>Jan 15th, 2016</span>
            </p>
            <p class="info-time">
              Time: <span>6:35 PM</span>
            </p>
          </div>
          <div class="address">
            <p>
              Address: <span>129 W 81st St, New York, NY</span>
            </p>
          </div>
          <div class="observations">
            <p>
              Observations: <span>Be there 15 minutes earlier</span>
            </p>
          </div>
        </div>

        <div class="actions">
          <button class="save">
            Save <i class="ion-checkmark"></i>
          </button>
          <button class="dismiss">
            Dismiss <i class="ion-android-close"></i>
          </button>
        </div>
      </div>

    </div>

  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    // Wrap your JavaScript code in a document ready function
    $(document).ready(function () {
      var app = {
        settings: {
          container: $('.calendar'),
          calendar: $('.front'),
          days: $('.weeks span'),
          form: $('.back'),
          input: $('.back input'),
          buttons: $('.back button')
        },

        init: function () {
          instance = this;
          settings = this.settings;
          this.bindUIActions();
        },

        swap: function (currentSide, desiredSide) {
          settings.container.toggleClass('flip');

          currentSide.fadeOut(900);
          currentSide.hide();
          desiredSide.show();
        },

        bindUIActions: function () {
          settings.days.on('click', function () {
            instance.swap(settings.calendar, settings.form);
            settings.input.focus();
          });

          settings.buttons.on('click', function () {
            instance.swap(settings.form, settings.calendar);
          });
        }
      }

      app.init();
    });
  </script>


  <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="footer-top">
      <div class="container">
        <div class="row">

          <div class="col-lg-3 col-md-6">
            <div class="footer-info">
              <h3>Sailor</h3>
              <p>
                A108 Adam Street <br>
                NY 535022, USA<br><br>
                <strong>Phone:</strong> +1 5589 55488 55<br>
                <strong>Email:</strong> info@example.com<br>
              </p>
              <div class="social-links mt-3">
                <a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>
                <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
                <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
                <a href="#" class="google-plus"><i class="bx bxl-skype"></i></a>
                <a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>
              </div>
            </div>
          </div>

          <div class="col-lg-2 col-md-6 footer-links">
            <h4>Useful Links</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Home</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">About us</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Services</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Terms of service</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Privacy policy</a></li>
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Our Services</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Web Design</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Web Development</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Product Management</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Marketing</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Graphic Design</a></li>
            </ul>
          </div>

          <div class="col-lg-4 col-md-6 footer-newsletter">
            <h4>Our Newsletter</h4>
            <p>Tamen quem nulla quae legam multos aute sint culpa legam noster magna</p>
            <form action="" method="post">
              <input type="email" name="email"><input type="submit" value="Subscribe">
            </form>

          </div>

        </div>
      </div>
    </div>

    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong><span>Sailor</span></strong>. All Rights Reserved
      </div>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/sailor-free-bootstrap-theme/ -->
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
      </div>
    </div>
  </footer>
</body>

</html>