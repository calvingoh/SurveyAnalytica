<?php
  session_start();
  session_unset();
  session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Home</title>
  <?php include "head.php"; ?>

  <body>
    <?php include "header.php"; ?>

    <div class="title-bar" style="height:610px">
      <div class = 'ht-100'></div>
      <h2><b>LOGOUT SUCCESSFUL</b></h2>
      <h3>You have been logged out successfully!</h3>
      <div class = 'ht-20'></div>
      <a href="index.php" class="btn btn-primary">
        <i class="fa fa-home" ></i>&nbsp;&nbsp;<b>Back to Home</b>
      </a>
    </div>

<?php include "footer.php"; ?>
</body>
</html>
