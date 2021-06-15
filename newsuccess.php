<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Profile</title>
  <style>
  h3{
    display: inline-block;
  }

  </style>
  <?php include "head.php"; ?>

  <body>
    <?php include "header.php"; ?>

    <div class="title-bar" style="height:610px">
      <div class = 'ht-100'></div>
      <h2><b>CREATION SUCCESSFUL</b></h2>
      <h3>Your form was created successfully.</h3>
      <div class = 'ht-20'></div>
      <a class='btn btn-primary' style="color:#fff" href="survey.php">
        <i class="fa fa-files-o"></i> &nbsp; My Surveys</a>
    </div>


    <?php include "footer.php"; ?>
  </body>
  </html>
