<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Register</title>
  <style>
  button.viewMoreBtn{
    display:inline-block;
    padding:0.35em 1.2em;
    border:0.1em solid #000;
    margin:0 0.3em 0.3em 0;
    border-radius:0.12em;
    box-sizing: border-box;
    text-decoration:none;
    font-family:'Roboto',sans-serif;
    font-weight:300;
    color:#000;
    text-align:center;
    transition: all 0.2s;
    background-color: #fff;
  }
  button.viewMoreBtn:hover{
    color:#fff;
    background-color:#000;
  }
  button.viewMoreBtn:focus{
    outline: none;
    box-shadow: 0px 0px 20px 1px rgba(0, 0, 0, 0.5);
  }
  @media all and (max-width:30em){
    button.viewMoreBtn{
      display:block;
      margin:0.4em auto;
    }
  }

  h3{
    display: inline-block;
  }
  </style>
<?php include "head.php"; ?>

<body>
  <?php include "header.php"; ?>
  <?php
    $pwdIncorrect = $invalidName = $emptyGender = $invalidEmail = "";
    $success = false;
    $user_id = $_SESSION['user_id'];

    include "database.php";
    if(!$conn){
      echo "Database error!";
    }
    else
    {
      $sql = "SELECT user_fullname, user_gender, user_username, user_email, user_pwd FROM user WHERE (user_id = '$user_id')";
      $result = mysqli_query($conn, $sql);
      if($row = mysqli_fetch_assoc($result)){
  			$user_fullname = $row['user_fullname'];
  			$user_gender = $row['user_gender'];
  			$user_username = $row['user_username'];
  			$user_email = $row['user_email'];
  			$hashedPassword = $row['user_pwd'];
  		}
  		else
  		{
          echo "Database error!";
  		}
    }

    if(isset($_POST['editSubmit'])){
      if((!(password_verify($_POST['edit_password'], $hashedPassword))) || (!preg_match("/^[a-zA-Z ]*$/",$_POST['user_fullname'])) || (empty($_POST['user_gender']))
          || (!filter_var($_POST['login_email'], FILTER_VALIDATE_EMAIL)))
      {
        if(!preg_match("/^[a-zA-Z ]*$/",$_POST['user_fullname'])) {
          $invalidName = '<br><span style="color: #f00;">Please enter a valid name!</span>';
        }
        if(empty($_POST['user_gender'])){
          $emptyGender = '<br><span style="color: #f00;">Please choose your gender!</span>';
        }
        if(!filter_var($_POST['login_email'], FILTER_VALIDATE_EMAIL)) {
          $invalidEmail = '<br><span style="color: #f00;">Invalid email format!</span>';
        }
        if(!(password_verify($_POST['edit_password'], $hashedPassword))) {
          $pwdIncorrect = '<br><span style="color:#f00;">Incorrect password entered. No changes are made.</span>';
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
          $user_fullname = mysqli_real_escape_string($conn, $_POST['user_fullname']);
          $user_gender = $_POST['user_gender'];
          $user_username = mysqli_real_escape_string($conn, $_POST['login_username']);
          $user_email = $_POST['login_email'];

          $sql = "UPDATE user SET user_fullname = '$user_fullname', user_gender = '$user_gender',
                  user_username = '$user_username', user_email = '$user_email' WHERE user_id = $user_id";
          mysqli_query($conn, $sql);
          $success = false;
          header("Location: index.php?edit=success");
          exit();
        }
    }
  }
  ?>

  <div class="title-bar"><h1>Edit Profile</h1></div>
  <div class="container main-container-padding-top" style="padding-bottom:70px;">
    <div class="row">
      <div class="col-1"></div>
      <div class="col-5">
        <form action="editprofile.php" method="post" name="registerForm">
          <h3><b>PERSONAL DETAILS</b></h3>
          <p>
            <h6>Full Name</h6>
            <?php echo "<input type='text' class='form-control input-text-css' placeholder='Full Name' name='user_fullname' value='$user_fullname' required readonly>"?>
          </p>

          <h5><b>Gender</b></h5>
          <p>
            <div class="row">
              <div class="radiobtn col-md-6">
                <input type="radio" id="Male" name="user_gender" value="Male" <?php if($user_gender == "Male") echo "checked"?>>
                <label for="Male">Male</label>
              </div>
              <div class="radiobtn col-md-6">
                <input type="radio" id="Female" name="user_gender" value="Female"<?php if($user_gender == "Female") echo "checked"?>>
                <label for="Female">Female</label>
              </div>
            </div><?php echo $emptyGender; ?>
          </p>

        </div><!--end personal details-->
        <div class="col-5">
          <h3><b>ACCOUNT DETAILS</b></h3>
          <p><h6>Username</h6><input type="text" class="form-control input-text-css" placeholder="Username" name="login_username" value="<?php echo $user_username; ?>" required></p>
          <p><h6>Email</h6><input type="text" class="form-control input-text-css" placeholder="Email" name="login_email" value="<?php echo $user_email; ?>" required><?php echo $invalidEmail; ?></p>
          <h6>Password
            <a href="changepwd.php"><button type="button" class="viewMoreBtn" style="float:right">
              <i class="fa fa-unlock-alt"></i>&nbsp;Change Password</button>
            </a>
          </h6>
          <p><input type="password" class="form-control input-text-css" placeholder="********" name="login_pwd" disabled >
            <span style="font-size:12px">Note: This is not the actual representation of your password</span>
          </p>
        </div><!--end account details-->
      </div>
      <div class="ht-30"></div>
      <div class="row">
        <div class="col-1"></div>
          <div class="col-6">
            <h4><b>Confirm password to make changes</b></h4>
          </div>
      </div><!--end confirm password text-->

      <div class="row">
        <div class="col-1"></div>
        <div class="col-5">
          <h6>Confirm Password</h6>
          <input type="text" class="form-control input-text-css" name="edit_password" required>
          <?php echo $pwdIncorrect; ?>
        </div>
        <div class="col-5"></div>
        <div class="col-1"></div>
      </div><!--end confirm password input-->
      <div class="ht-30"></div>

      <div>
        <input type="hidden" name="editSubmit" value="true">
        <button class="btn btn-primary" type="submit" style="padding: 15px 32px 15px 32px; margin-left:95px;">
          Make Changes
        </button>
      </div>
    </form>
  </div>

<?php include "footer.php"; ?>
</body>
</html>
