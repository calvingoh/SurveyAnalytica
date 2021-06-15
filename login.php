<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Login</title>
<?php include "head.php"; ?>

<body>
  <?php include "header.php"; ?>

  <div class="title-bar"><h1>Login Page</h1></div>

<?php
  if(isset($_POST['loginSubmitted'])){
    include "database.php";
    $lusername = mysqli_real_escape_string($conn, $_POST['login_user']);
    $lpassword = mysqli_real_escape_string($conn, $_POST['login_pwd']);

    $sql = "SELECT * FROM user WHERE user_username = '$lusername'";
    $result = mysqli_query($conn, $sql);

    if($row = mysqli_fetch_assoc($result)){
      $hashedPasswordCheck = password_verify($lpassword, $row['user_pwd']);
      if($lusername == $row['user_username'] && $hashedPasswordCheck == true){
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['user_username'] = $row['user_username'];
        $_SESSION['user_fullname'] = $row['user_fullname'];
        header("Location: index.php");
        exit();
      }
      else{
        echo '
        <div class="container main-container-padding-top" style="padding-bottom: 70px;">
          <div class="row">
            <div class="col-4"></div>
            <div class="col-4">
              <form action="login.php" method="post" name="loginForm">
                <h2 class="text-primary"><b>Login</b></h2>
                <p><input class="form-control input-text-css" type="text" placeholder="Username" required name="login_user"></p>
                <input class="form-control input-text-css" type="password" placeholder="Password" required name="login_pwd" id="inputPwd">
                <ul class="ks-cboxtags" style="padding: 12px 0 15px 0; margin: 0; font-size: 10px;">
                  <li><input type="checkbox" id="checkboxOne" onclick="showPwd()"><label for="checkboxOne">Show password</label></li>
                </ul>
                <span style="color: #f00;">Wrong username or password!</span>
                <p>
                  <button class="btn btn-primary form-control" type="submit">
                    LOG IN
                  </button>
                </p>
                <input type="hidden" name="loginSubmitted" value="true">
                Do not have an account yet? <a href="register.php">Register with us!</a>
              </form>
            </div>
            <div class="col-4"></div>
          </div>
        </div>';
      }
    }
    else{
      echo '
      <div class="container main-container-padding-top" style="padding-bottom: 70px;">
        <div class="row">
          <div class="col-4"></div>
          <div class="col-4">
            <form action="login.php" method="post" name="loginForm">
              <h2 class="text-primary"><b>Login</b></h2>
              <p><input class="form-control input-text-css" type="text" placeholder="Username" required name="login_user"></p>
              <input class="form-control input-text-css" type="password" placeholder="Password" required name="login_pwd" id="inputPwd">
              <ul class="ks-cboxtags" style="padding: 12px 0 15px 0; margin: 0; font-size: 10px;">
                <li><input type="checkbox" id="checkboxOne" onclick="showPwd()"><label for="checkboxOne">Show password</label></li>
              </ul>
              <span style="color: #f00;">Wrong username or password!</span>
              <p>
                <button class="btn btn-custom form-control" type="submit">
                  LOG IN
                </button>
              </p>
              <input type="hidden" name="loginSubmitted" value="true">
              Do not have an account yet? <a href="register.php">Register with us!</a>
            </form>
          </div>
          <div class="col-4"></div>
        </div>
      </div>';
    }
  }
  else {
    echo '
    <div class="container main-container-padding-top" style="padding-bottom: 70px;">
      <div class="row">
        <div class="col-4"></div>
        <div class="col-4">
          <form action="login.php" method="post" name="loginForm">
            <h2 class="text-primary"><b>Login</b></h2>
            <p><input class="form-control input-text-css" type="text" placeholder="Username" required name="login_user"></p>
            <input class="form-control input-text-css" type="password" placeholder="Password" required name="login_pwd" id="inputPwd">
            <ul class="ks-cboxtags" style="padding: 12px 0 15px 0; margin: 0; font-size: 10px;">
              <li><input type="checkbox" id="checkboxOne" onclick="showPwd()"><label for="checkboxOne">Show password</label></li>
            </ul>
            <p>
              <button class="btn btn-custom form-control" type="submit">
                LOG IN
              </button>
            </p>
            <input type="hidden" name="loginSubmitted" value="true">
            Do not have an account yet? <a href="register.php">Register with us!</a>
          </form>
        </div>
        <div class="col-4"></div>
      </div>
    </div>';
  }
?>

<?php include "footer.php"; ?>
</body>
</html>
<script>
function showPwd() {
  var x = document.getElementById("inputPwd");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
</script>
