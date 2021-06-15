<!--==========================
Header
============================-->
<header id="header">
  <div id="topbar">
    <div class="container">
      <div class="social-links">
        <?php
        if(isset($_SESSION['user_id'])){
          echo "<b>Hi, ".$_SESSION['user_fullname']."</b>";
        }
        ?>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="logo float-left">
      <h1 class="text-light"><a href="index.php"><span>Survey Analytica</span></a></h1>
    </div>

    <nav class="main-nav float-right d-none d-lg-block">
      <ul>
        <?php
          if(isset($_SESSION['user_id'])){
            echo '
            <li><a href="newsurvey.php">New Survey</a></li>
            <li><a href="index.php">Home</a></li>
            <li><a href="survey.php">My Surveys</a></li>
            <li><a href="editprofile.php">Edit Profile</a></li>
			      <li><a href="logout.php">Logout</a></li>
            ';
          }
          else{
            echo '
            <li><a href="index.php">Home</a></li>
            <li><a href="register.php">Register</a></li>
            <li><a href="#footer">Contact Us</a></li>
			      <li><a href="login.php">Login</a></li>
            ';
          }
        ?>
      </ul>
    </nav><!-- .main-nav -->

  </div>
</header><!-- #header -->
