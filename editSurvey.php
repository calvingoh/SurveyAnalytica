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
    <!--<script src="https://formbuilder.online/assets/js/form-builder.min.js"></script>-->
    <script src="https://kevinchappell.github.io/formBuilder/assets/js/form-render.min.js"></script>
    <!-- <script src="http://localhost/surveylab/formbuilder/dist/form-render.min.js"></script> -->
    <script src="http://localhost/surveylab/formbuilder/dist/form-builder.min.js"></script>
    <!--==========================
    Intro Section
    ============================-->

    <main id="main">
        <section id="why-us" class="section-bg">
          <form action="action" method="post" enctype="multipart/form-data"
            id="form-form" class="form-horizontal">
          <div class = "ht-70"></div>
          <?php
            include "database.php";
            if(isset($_GET['form'])){
              $id=$_GET['form'];
              $sql = "SELECT form_format FROM form WHERE form_id=$id";
              $sql2 = "SELECT form_name FROM form WHERE form_id=$id";
              $query = mysqli_query($conn,$sql);
              $query2 = mysqli_query($conn,$sql2);
              $row = mysqli_fetch_assoc($query);
              $row2 = mysqli_fetch_assoc($query2);
              $formFormat =implode($row);
              $formName = implode($row2);
            }
          ?>
          <input type="hidden" id="formId" value ="<?php echo $id; ?>" ></input>
          <input type="text" name="form_name" value= "<?php echo $formName; ?>"
            placeholder="Form Name" id="input-form-name" class="form-control" />
          <div id="form_builder" class="build-wrap"></div>
          <div id="build-wrap"></div>

		      <p align = "right" style="padding-top:10px">
    			  <a id="btn_submit" onClick="submitForm()" href = "viewsurvey.php?form=<?php echo $id;?>" class="btn btn-primary" style = "color: #fff;">
    				<i class="fa fa-save"></i> Save</a>
		      </p>
        </section>
      </main>

  <script>
  //form functions
  const getUserDataBtn = document.getElementById("get-user-data");
  const fbRender = document.getElementById("fb-render");

  jQuery(function($) {
   var formData = '<?php echo $formFormat;?>';
    $(fbRender).formRender({ formData });
  });

  var options = {
    disableFields:['autocomplete','button','file','header','hidden','paragraph'],
    disabledActionButtons: ['data']
  };
  var formBuilder = $('#form_builder').formBuilder(options);

  $(function() {
     var fbTemplateS = '<?php echo $formFormat;?>';
     var fbTemplateA = fbTemplateS.split();
     formBuilder.actions.setData(fbTemplateA);
  });

  function submitForm(){
    var form_id = $('#formId').val();
    var form_format = [];
    var form_name = $('#input-form-name').val();
    var check = 1;
    form_format = formBuilder.actions.getData();
    form_format = JSON.stringify(form_format);
       $.ajax({
         type:'POST',
         data : 'form_name=' + form_name + '&form_format=' + form_format +
                '&form_id=' + form_id + '&check=' + check,
         url:'survey.php',
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
	<!--<script src="lib/jquery/jquery.min.js"></script>-->
	<!--<script src="lib/jquery/jquery-migrate.min.js"></script>-->

  <!-- Bootstrap JS File -->
  <script src="lib/bootstrap/js/bootstrap.min.js"></script>
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

  <!-- Share Methods JavaScript File -->
  <script src="js/copy_link.js"></script>

	<!-- Template Main Javascript File -->
	<script src="js/main.js"></script>


	</body>
</html>
