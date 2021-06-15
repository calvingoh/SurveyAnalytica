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

    $sql = "SELECT user_username FROM user";
    $result = mysqli_query($conn,$sql);

    $pwdDiff = $invalidName = $emptyGender = $invalidEmail = $emailDiff = "";
    $registerSameUsername = false;
    $success = false;

    if(isset($_POST['registerSubmit'])){
      if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
          $registerUsername = $row['user_username'];
          if($_POST['login_username'] == $registerUsername){
            $registerSameUsername = true;
          }
        }
      }
      if(($_POST['login_pwd'] != $_POST['login_pwd_confirm']) || (!preg_match("/^[a-zA-Z ]*$/",$_POST['user_fullname']))
      || (empty($_POST['user_gender'])) || (!filter_var($_POST['login_email'], FILTER_VALIDATE_EMAIL)) || ($_POST['login_email'] != $_POST['login_email_confirm'])
      || ($registerSameUsername == true)){
        if($_POST['login_pwd'] != $_POST['login_pwd_confirm']) {
          $pwdDiff = '<br><span style="color:#f00;">The passwords are not the same!</span>';
        }
        if(!preg_match("/^[a-zA-Z ]*$/",$_POST['user_fullname'])) {
          $invalidName = '<br><span style="color: #f00;">Please enter a valid name!</span>';
        }
        if(empty($_POST['user_gender'])){
          $emptyGender = '<br><span style="color: #f00;">Please choose your gender!</span>';
        }
        if(!filter_var($_POST['login_email'], FILTER_VALIDATE_EMAIL)) {
          $invalidEmail = '<br><span style="color: #f00;">Invalid email format!</span>';
        }
        if($_POST['login_email'] != $_POST['login_email_confirm']){
          $emailDiff = '<br><span style="color:#f00;">The emails are not the same!</span>';
        }
        if($registerSameUsername == true){
          $sameUsername = '<br><span style="color:#f00;">The username is taken!</span>';
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
        else{
          $user_fullname = mysqli_real_escape_string($conn, $_POST['user_fullname']);
          $user_age = date('Y') - $_POST['user_byear'];
          $user_gender = $_POST['user_gender'];
          $user_username = mysqli_real_escape_string($conn, $_POST['login_username']);
          $user_email = $_POST['login_email'];
          $user_pwd = $_POST['login_pwd'];

          $hashedPassword = password_hash($user_pwd, PASSWORD_DEFAULT);

          $sql = "INSERT INTO user (user_fullname, user_gender, user_username, user_email, user_pwd) VALUES
          ('$user_fullname', '$user_gender', '$user_username', '$user_email', '$hashedPassword')";
          mysqli_query($conn, $sql);
          $success = false;
          header("Location: login.php?registration=success");
          exit();
        }

      }
    }
  ?>

  <div class="title-bar"><h1>Register</h1></div>
  <div class="container main-container-padding-top" style="padding-bottom:70px;">
    <div class="row">
      <div class="col-1"></div>
      <div class="col-5">
        <form action="register.php" method="post" name="registerForm">
          <h3><b>PERSONAL DETAILS</b></h3>
          <p><input type="text" class="form-control input-text-css" placeholder="Full Name" name="user_fullname"><?php echo $invalidName; ?></p>

          <h5><b>Gender</b></h5>
          <p>
            <div class="row">
              <div class="radiobtn col-md-6">
                <input type="radio" id="Male" name="user_gender" value="Male">
                <label for="Male">Male</label>
              </div>
              <div class="radiobtn col-md-6">
                <input type="radio" id="Female" name="user_gender" value="Female">
                <label for="Female">Female</label>
              </div>
            </div><?php echo $emptyGender; ?>
          </p>

        </div>
        <div class="col-5">
          <h3><b>ACCOUNT DETAILS</b></h3>
          <p><input type="text" class="form-control input-text-css" placeholder="Username" name="login_username" required></p>
          <p><input type="text" class="form-control input-text-css" placeholder="Email" name="login_email" required><?php echo $invalidEmail; ?></p>
          <p><input type="text" class="form-control input-text-css" placeholder="Confirm Email" name="login_email_confirm" required><?php echo $emailDiff; ?></p>
          <p><input type="password" class="form-control input-text-css" placeholder="Password" name="login_pwd" required></p>
          <p><input type="password" class="form-control input-text-css" placeholder="Confirm Password" name="login_pwd_confirm" required><?php echo $pwdDiff; ?></p>
        </div>
        <div class="col-1"></div>
      </div><!--end row-->
      <div>
        <input type="hidden" name="registerSubmit" value="true">
        <button class="btn btn-primary" type="submit" style="padding: 15px 32px 15px 32px; margin-left:95px;">Register</button>
      </div>
    </form>
  </div>

<?php include "footer.php"; ?>
</body>
</html>
