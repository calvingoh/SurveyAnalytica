<?php session_start(); ?>

<style>
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
      print_r ($_POST);
  }
?>

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
    <script src="http://localhost/surveylab/formbuilder/dist/form-render.min.js"></script>
    <script src="http://localhost/surveylab/formbuilder/dist/form-builder.min.js"></script>
    <!--==========================
    Intro Section
    ============================-->

  <main id="main">
    <section id="why-us" class="section-bg">
      <form action="action" method="post" enctype="multipart/form-data" id="form-form"
        class="form-horizontal">
      <div class = "ht-70"></div>
      <div class="errorName"></div>
      <input type="text" name="form_name" placeholder="Form Name" id="input-form-name"
        class="form-control" />
      <div><p></p></div>
      <div class="errorFormat"></div>
      <div id="form_builder" class="build-wrap"></div>
      <div class="fb-render">
    </div>

    <p align = "right"style="padding-top:10px"><a id="btn_submit" style="color: #fff;"
      onclick="submitForm()" class="btn btn-primary">
	     <i class="fa fa-plus"></i> &nbsp; Create Form</a>
    </p>
    </section>
  </main>

    <script>
    var options = {
      disableFields:['autocomplete','button','file','header','hidden','paragraph'],
      disabledActionButtons: ['data','save']
    };
    var formBuilder = $('#form_builder').formBuilder(options);
    
    function submitForm(){
      var form_name = $('#input-form-name').val();
      var form_format = [];
      var form_formatT =[];
      var check = 0 ;
      form_format = formBuilder.actions.getData();
      //form_format = JSON.stringify(form_format);
      if(form_name==""){
          var html = '  <div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> Please enter the form\'s name.</div>';
        $('.errorName').append(html);
      }
      else if(form_format.length<=0){
        var html = '  <div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> Please enter the form\'s format.</div>';
        $('.errorFormat').append(html);
      }
      else{
        form_format = JSON.stringify(form_format);
        $.ajax({
          type:'post',
          data : 'form_name=' + form_name + '&form_format=' + form_format + '&check=' + check,
          url:'survey.php',
          cahce:false,
          success: function(data) {
              window.location.href = 'newsuccess.php';
          }
        });
      }
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
	<!--<script src="lib/jquery/jquery.min.js"></script>-->
	<!--<script src="lib/jquery/jquery-migrate.min.js"></script>-->
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
