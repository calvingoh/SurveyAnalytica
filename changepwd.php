<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Register</title>
<?php include "head.php"; ?>

<body>
  <?php include "header.php"; ?>
  <?php
    include "database.php";
    $pwdDifferent = $pwdOldIncorrect = "";
    $user_id = $_SESSION['user_id'];
    $success = false;
    if(!$conn){
      echo "Database error!";
    }
    else
    {
      $sql = "SELECT user_pwd FROM user WHERE (user_id = '$user_id')";
      $result = mysqli_query($conn, $sql);
      if($row = mysqli_fetch_assoc($result))
      {
  			$hashedPassword = $row['user_pwd'];
  		}
  		else
  		{
          echo "Database error!";
  		}
    }

    if(isset($_POST['chgPwdSubmit']))
    {
      if(!(password_verify($_POST['old_pwd'], $hashedPassword)) || ($_POST['new_pwd'] != $_POST['new_pwd_confirm'])){
        if(!(password_verify($_POST['old_pwd'], $hashedPassword))){
          $pwdOldIncorrect = '<br><span style="color:#f00;">Incorrect old password entered. No changes are made.</span>';
        }
        if($_POST['new_pwd'] != $_POST['new_pwd_confirm']){
          $pwdDifferent = '<br><span style="color:#f00;">The entered passwords are different!</span>';
        }
      }
      else
      {
        $success = true;
      }


      if($success == true){
        include "database.php";
        if(!$conn){
          echo "Database error!";
        }
        else
        {
          $user_pwd = $_POST['new_pwd'];
          $hashedPassword = password_hash($user_pwd, PASSWORD_DEFAULT);

          $sql = "UPDATE user SET user_pwd = '$hashedPassword' WHERE user_id = $user_id";
          mysqli_query($conn, $sql);
          $success = false;
          header("Location: editprofile.php?editPwd=success");
          exit();
        }
      }
    }
  ?>

  <div class="title-bar"><h1>Change Password</h1></div>
    <section id="services" class="section-bg"  style="background: #fff;">
      <form action="changepwd.php" method="post">
      <div class="container">
          <div class="row">
            <div class="col-3"></div>
            <div class="col-6"><h3><b>Old Password</b></h3></div>
          </div><!--end row1-->

          <div class="row">
            <div class="col-3"></div>
            <div class="col-6">
              <input type="text" class="form-control input-text-css" name="old_pwd" placeholder="Enter your old password here">
              <?php echo $pwdOldIncorrect; ?>
            </div>

          </div><!--end row2-->
          <div class="ht-80"></div>

          <div class="row">
            <div class="col-3"></div>
            <div class="col-6"><h3><b>New Password</b></h3></div>
          </div><!--end row3-->

          <div class="row">
            <div class="col-3"></div>
            <div class="col-6">
              <input type="text" class="form-control input-text-css" name="new_pwd" placeholder="Enter your new password here">
            </div>
          </div><!--end row4-->
          <div class="ht-40"></div>

          <div class="row">
            <div class="col-3"></div>
            <div class="col-6">
              <input type="text" class="form-control input-text-css" name="new_pwd_confirm" placeholder="Confirm new password">
              <?php echo $pwdDifferent; ?>
            </div>
          </div><!--end row5-->
          <div class="ht-40"></div>

          <div class="row">
            <div class="col-2"></div>
            <div class="col-6">
              <input type="hidden" name="chgPwdSubmit" value="true">
              <button class="btn btn-primary" type="submit" style="padding: 15px 32px 15px 32px; margin-left:95px;">
              <i class="fa fa-pencil"></i>&nbsp; Change Password</button>
            </div>
          </div><!--end row6-->
        </form>
      </div> <!--end container-->
    </section> <!--end section-->

  <?php include "footer.php"; ?>

</body>
</html>
