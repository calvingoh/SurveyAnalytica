<?php session_start(); ?>

<style>
/* body {
  background: #555;
} */

.content {
  max-width: 500px;
  height: 430px;
  margin: auto;
  background: white;
  padding-top:30px;
}

</style>

<?php
  function addNewForm(){
    session_start();
    echo 123;
    exit();
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>

  <title>Form</title>
  <?php include "head.php"; ?>

  <body>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.js"></script>
    <!-- <script src="https://formbuilder.online/assets/js/form-builder.min.js"></script>
    <script src="https://formbuilder.online/assets/js/form-render.min.js"></script> -->
    <script src="http://localhost/SurveyAnalytica/formbuilder/dist/form-render.min.js"></script>
    <script src="http://localhost/SurveyAnalytica/formbuilder/dist/form-builder.min.js"></script>
    <!--==========================
    Intro Section
    ============================-->

  <main id="main">
    <section id="why-us" class="section-bg">
      <form action="action" method="post" enctype="multipart/form-data" id="form-form" class="form-horizontal">
      <div class = "ht-70"></div>
      <input type="text" name="form_name" placeholder="Form Name" id="input-form-name" class="form-control" />
      <!-- <div id="form_builder" class="build-wrap"></div>
      <div class="fb-render"> -->
    </div>
    <p align = "right"style="padding-top:10px"><a id="btn_submit" onClick="submitForm()" class="btn btn-primary"><i class="fa fa-save"></i></a><p>
    </section>
  </main>


    <script>

    var formBuilder = $('#form_builder').formBuilder();

    // jQuery(function($) {
    //   var fbTemplate = document.getElementById('fb-template');
    //   $('.fb-render').formRender({
    //     dataType: 'xml',
    //     formData: fbTemplate.value
    //   });
    // });

    function submitForm(){
      var form_name = $('#input-form-name').val();
      var form_format = [];
      form_format = formBuilder.actions.getData();
      form_format = JSON.stringify(form_format);
      console.log(form_format);
         $.ajax({
           dataType:'json',
           type:'post',
           data : 'form_name=' + form_name + '&form_format=' + form_format,
           url:'newsurvey.php/addNewForm',
           // success: function(json) {
           //     window.location.href = json['redirect'];
           // }
         })

    }
    </script>

    <!--==========================
    Footer
    ============================-->
    <footer id="footer" class="section-bg">
      <div class="footer-top">
        <div class="container">
          <div class="row">
            <div class="col-lg-6">
              <div class="row">
                <div class="col-sm-6">
                  <div class="footer-info">
                    <h3>Survey Analytica</h3>
                    <p>Create your survey form with ease using Survey Analytica!</p>
                  </div>

                  <div class="footer-newsletter">
                    <h4>Our Newsletter</h4>
                    <p>Want to get constant update on our website? Subscribe now!</p>
                    <form action="" method="post">
                      <input type="email" name="email"><input type="submit"  value="Subscribe">
                    </form>
                  </div>

                </div>

                <div class="col-sm-6">
                  <div class="footer-links">
                    <h4>Useful Links</h4>
                    <ul>
                      <?php
                        if(isset($_SESSION['user_id'])){
                          echo '
                          <li><a href="newsurvey.php"><i class="fa fa-plus"></i>&nbsp;&nbsp;New Survey</a></li>
                          <li><a href="index.php">Home</a></li>
                          <li><a href="survey.php">My Surveys</a></li>
                          <li><a href="profile.php">My Profile</a></li>
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

                  <div class="footer-links">
                    <h4>Contact Us</h4>
                    <p>
                      A108 Adam Street <br>
                      New York, NY 535022<br>
                      United States <br>
                      <strong>Phone:</strong> +1 5589 55488 55<br>
                      <strong>Email:</strong> info@example.com<br>
                    </p>
                  </div>
                  <div class="social-links">
                    <a href="#" class="twitter"><i class="fa fa-twitter"></i></a>
                    <a href="#" class="facebook"><i class="fa fa-facebook"></i></a>
                    <a href="#" class="instagram"><i class="fa fa-instagram"></i></a>
                    <a href="#" class="linkedin"><i class="fa fa-linkedin"></i></a>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form">
                <h4>Send us a message</h4>
                <p>Do you have any questions or problems? Contact us now!</p>
                <form action="" method="post" role="form" class="contactForm">
                  <div class="form-group">
                    <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" data-rule="minlen:4" data-msg="Please enter at least 4 chars" />
                    <div class="validation"></div>
                  </div>
                  <div class="form-group">
                    <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" data-rule="email" data-msg="Please enter a valid email" />
                    <div class="validation"></div>
                  </div>
                  <div class="form-group">
                    <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" data-rule="minlen:4" data-msg="Please enter at least 8 chars of subject" />
                    <div class="validation"></div>
                  </div>
                  <div class="form-group">
                    <textarea class="form-control" name="message" rows="5" data-rule="required" data-msg="Please write something for us" placeholder="Message"></textarea>
                    <div class="validation"></div>
                  </div>

                  <div id="sendmessage">Your message has been sent. Thank you!</div>
                  <div id="errormessage"></div>

                  <div class="text-center"><button type="submit" title="Send Message">Send Message</button></div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="container">
        <div class="copyright">
          &copy; Copyright 2019 <strong>Survey Analytica</strong>. All Rights Reserved
        </div>
        <div class="credits">
          <!--
          All the links in the footer should remain intact.
          You can delete the links only if you purchased the pro version.
          Licensing information: https://bootstrapmade.com/license/
          Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/buy/?theme=Rapid
        -->
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
      </div>
    </div>
    </footer><!-- #footer -->

    <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>

    <div id="preloader"></div>

    <!-- JavaScript Libraries -->
    <!--<script src="lib/jquery/jquery.min.js"></script>
    <script src="lib/jquery/jquery-migrate.min.js"></script>-->
    <script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/mobile-nav/mobile-nav.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/isotope/isotope.pkgd.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <!-- Contact Form JavaScript File -->
    <script src="contactform/contactform.js"></script>

    <!-- Template Main Javascript File -->
    <script src="js/main.js"></script>


</body>
</html>
