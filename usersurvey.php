<?php session_start();
include "database.php";
if(isset($_GET['form'])){
  $id = $_GET['form'];
  $sql = "SELECT form_format FROM form WHERE form_id=$id";
  $sql2 = "SELECT form_name FROM form WHERE form_id=$id";
  $sql3 = "SELECT user_id FROM form WHERE form_id=$id";
  $sql4 = "SELECT form_status FROM form WHERE form_id=$id";

  $query = mysqli_query($conn,$sql);
  $query2 = mysqli_query($conn,$sql2);
  $query3 = mysqli_query($conn,$sql3);
  $query4 = mysqli_query($conn,$sql4);

  $row = mysqli_fetch_assoc($query);
  $row2 = mysqli_fetch_assoc($query2);
  $row3 = mysqli_fetch_assoc($query3);
  $row4 = mysqli_fetch_assoc($query4);

  $formFormat =implode($row);
  $formName = implode($row2);
  $formCreatorId = implode($row3);
  $formStatus = implode($row4);

  $noVisit = false;
  if(isset($_SESSION['user_id'])){
    if ($_SESSION['user_id'] == $formCreatorId)
      $noVisit = true;
  }

  if (!$noVisit){
    $sql = "UPDATE form SET form_visit = form_visit+1 WHERE form_id=$id";
    $conn->query($sql);
  }
  date_default_timezone_set('Asia/Kuala_Lumpur');
  $startTime = date_default_timezone_get();
  $startTime = round(microtime(true) * 1000);

  if (!$noVisit){
    if ($formStatus != 1)
      header("Location: usererror.php?form=$id");
  }
}
?>

<style>
.content {
  max-width: 500px;
  height: 430px;
  margin: auto;
  background: white;
  padding-top:30px;
}
</style>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Form</title>
  <?php include "head.php"; ?>
  <body>
    <?php include "header.php"; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.js"></script>
    <script src="https://formbuilder.online/assets/js/form-builder.min.js"></script>
    <script src="https://kevinchappell.github.io/formBuilder/assets/js/form-render.min.js"></script>
    <script src="http://localhost/SurveyAnalytica/formbuilder/dist/form-builder.min.js"></script>


    <!--==========================
    Intro Section
    ============================-->
    <main id="main">
        <section id="why-us" class="section-bg" style="font-size: 18px;">
          <div class="container">
            <div class = "ht-50"></div>
            <h2><b>Reponse Form</b></h2>
            <div class = 'ht-10'></div>
            <input type="hidden" id="formId" value ="<?php echo $id; ?>" ></input>
            <input type="hidden" id="startTime" value ="<?php echo $startTime; ?>" ></input>

            <h3><?php echo $formName; ?></h3>
            <div><form id="fb-render"></form></div>
            <?php
            if ($noVisit)
            {
               echo '<div align = "right">
               <button id="btn_submit" style="color: #fff;" class="btn btn-success" disabled>
               <i class="fa fa-paper-plane"></i> Submit</a></div>';
            }
            else
            {
              echo '<div align = "right" style="padding-top:10px">
              <a id="btn_submit" style="color: #fff;"onClick="submitForm()" class="btn btn-success">
              <i class="fa fa-paper-plane"></i> Submit</a></div>';
            }
            ?>
        </section>
      </main>
    <script>
    const getUserDataBtn = document.getElementById("get-user-data");
    const fbRender = document.getElementById("fb-render");

    jQuery(function($) {
      var formData = '<?php echo $formFormat;?>';
      $(fbRender).formRender({ formData });
    });

    function submitForm(){
      var form_id = $('#formId').val();
      var startTime = $('#startTime').val();
      var check = 3
      var userData = JSON.stringify($(fbRender).formRender("userData"));
      $.ajax({
        type: "POST",
        url: 'survey.php',
        data: 'form_input=' + userData + '&form_id=' + form_id +'&startTime='+ startTime + '&check=' + check,
        success: function(data){
          window.location.href = 'usersuccess.php';
        }
      });
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
