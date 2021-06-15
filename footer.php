<!--==========================
Footer
============================-->
<footer id="footer" class="section-bg">
  <div class="footer-top">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <div class="footer-info">
            <h3>Survey Analytica</h3>
            <p>Create your survey form with ease using Survey Analytica!</p>
          </div>
        </div>

        <div class="col-lg-3">
          <div class="footer-links">
            <h4>Useful Links</h4>
            <ul>
              <?php
                if(isset($_SESSION['user_id'])){
                  echo '
                  <li><a href="newsurvey.php"><i class="fa fa-plus"></i>&nbsp;&nbsp;New Survey</a></li>
                  <li><a href="index.php">Home</a></li>
                  <li><a href="survey.php">My Surveys</a></li>
                  <li><a href="editprofile.php">Edit Profile</a></li>
                  ';
                }
                else{
                  echo '
                  <li><a href="index.php">Home</a></li>
                  <li><a href="register.php">Register</a></li>
                  <li><a href="login.php">Login</a></li>
                  ';
                }
              ?>
            </ul>
          </div>
        </div>

        <div class="col-lg-3">
          <div class="footer-links">
            <h4>Contact Us</h4>
            <p>
              1-Z Lebuh Bukit Jambul<br>
              Bukit Jambul, 11900 Bayan Lepas<br>
              Penang, Malaysia <br>
              <strong>Phone:</strong> +604-631 0138<br>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="copyright">
      &copy; Copyright 2019 <strong>Survey Analytica</strong>. All Rights Reserved
    </div>
  </div>
</footer><!-- #footer -->

<a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>

<div id="preloader"></div>

<!-- JavaScript Libraries -->
<script src="lib/jquery/jquery.min.js"></script>
<script src="lib/jquery/jquery-migrate.min.js"></script>
<script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="lib/easing/easing.min.js"></script>
<script src="lib/mobile-nav/mobile-nav.js"></script>
<script src="lib/wow/wow.min.js"></script>
<script src="lib/waypoints/waypoints.min.js"></script>
<script src="lib/counterup/counterup.min.js"></script>
<script src="lib/owlcarousel/owl.carousel.min.js"></script>
<script src="lib/isotope/isotope.pkgd.min.js"></script>
<script src="lib/lightbox/js/lightbox.min.js"></script>

<!-- Chart.js Libraries -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.0/dist/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.0/dist/Chart.bundle.min.js"></script>

<!-- Contact Form JavaScript File -->
<script src="contactform/contactform.js"></script>

<!-- Template Main Javascript File -->
<script src="js/main.js"></script>
