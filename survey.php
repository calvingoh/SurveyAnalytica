<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>My Surveys</title>
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

    <div class="title-bar"><h1>My Surveys</h1></div>
      <section id="services" class="section-bg" style="background:#fff;">
        <div class="container" >

          <?php
            include "database.php";
            $userID = $_SESSION['user_id'];
            $draft = 0;
            $published = 0;
            $expired = 0;

            if((!empty($_POST))&&($_POST['check']==0)){
              $form_name = $_POST['form_name'];
              $form_format = $_POST['form_format'];

              $sql = "INSERT INTO form(form_name,form_format,user_id)
              VALUES('$form_name','$form_format','$userID')";
              $queryForm = mysqli_query($conn,$sql);
              if($queryForm){
                $successMsg = "form has been sucessfully added!";
                echo($successMsg);
              } else {
                $errorMsg = "form adding has failed!";
                echo($successMsg);
              }
            }
            else if((!empty($_POST))&&($_POST['check']==1)){
              $form_id = $_POST['form_id'];
              $form_name = $_POST['form_name'];
              $form_format = $_POST['form_format'];
              $sql2 = "UPDATE form SET form_name= '$form_name', form_format= '$form_format' WHERE form_id= '$form_id'";
              $queryFormEdit = mysqli_query($conn,$sql2);
              if($queryFormEdit) // will return true if succefull else it will return false
              {
                echo "successful";
              }
              else {
                echo "fail";
              }
            }
            else if((!empty($_POST))&&($_POST['check']==2)){
              $form_id = $_POST['form_id'];
              $form_name = $_POST['form_name'];
              $form_format = $_POST['form_format'];
              $start_date = $_POST['start_date'];
              $end_date = $_POST['end_date'];
              $form_status = 1;

              $sql2 = "UPDATE form SET form_name= '$form_name', form_format= '$form_format', form_status= '$form_status',
                       start_date = '$start_date', end_date = '$end_date' WHERE form_id= '$form_id'";
              $queryFormEdit = mysqli_query($conn,$sql2);
              if($queryFormEdit) // will return true if successful else it will return false
              {
                echo "successful";
              }
              else {
                echo "fail";
              }
            }
            else if((!empty($_POST))&&($_POST['check']==3)){
              $form_id = $_POST['form_id'];
              $user_input_arrays = $_POST['form_input'];//here get the data from database

              date_default_timezone_set('Asia/Kuala_Lumpur');
              $startTime = $_POST['startTime'];
              $endTime = date_default_timezone_get();
              $endTime = round(microtime(true) * 1000);
              $duration = $endTime - $startTime;
              $seconds = floor($duration / 1000);
              $minutes = floor($seconds / 60);
              $hours = floor($minutes / 60);
              $milliseconds = $duration % 1000;
              $seconds = $seconds % 60;
              $minutes = $minutes % 60;

              $format = '%u:%02u:%02u';
              $time = sprintf($format, $hours, $minutes, $seconds);

              $sqlUser = "INSERT INTO user_reply(user_reply_format,form_id,duration)
              VALUES('$user_input_arrays','$form_id','$time')";
              $queryForm = mysqli_query($conn,$sqlUser);
            }
            else if((!empty($_POST))&&($_POST['check']==4)){
                $form_id = $_POST['form_id'];
                $sqlDelete = "DELETE FROM form WHERE form_id='$form_id'";
                $queryDelete = mysqli_query($conn,$sqlDelete);
                if($queryDelete) // will return true if successful else it will return false
                {
                  $sqlDeleteReply = "DELETE FROM user_reply WHERE form_id='$form_id'";
                  $queryDeleteReply = mysqli_query($conn,$sqlDeleteReply);
                }
                else {
                  echo "fail";
                }
            }
            else if((!empty($_POST))&&($_POST['check']==5)){
                $form_id = $_POST['form_id'];
                $start_date = $_POST['start_date'];
                $end_date = $_POST['end_date'];
                $sqlDuration = "UPDATE form SET start_date = '$start_date', end_date = '$end_date' WHERE form_id= '$form_id'";
                $queryDuration = mysqli_query($conn,$sqlDuration);
                if($queryDuration) // will return true if successful else it will return false
                {
                  echo "successful";
                }
                else {
                  echo "fail";
                }
            }


            //Update form status if expired
            $curDate = date("Y-m-d");
            $formsToUpdate = array();
            $sqlDate = "SELECT form_id,end_date FROM form WHERE form_status = 1 AND user_id = $userID";
            $queryDate = mysqli_query($conn,$sqlDate);
            while ($rows = mysqli_fetch_assoc($queryDate)){
              if ($rows['end_date'] == $curDate)
                $formsToUpdate[] = $rows['form_id'];
            }

            if (!(empty($formsToUpdate))){
              $noOfUpdate =  count($formsToUpdate);
              for ($i = 0; $i < $noOfUpdate; $i++ ){
                $toUpdate = $formsToUpdate[$i];
                $sqlUpdate = "UPDATE form SET form_status = 2 WHERE form_id= '$toUpdate'";
                $queryUpdate = mysqli_query($conn,$sqlUpdate);
              }
            }
            unset($formsToUpdate);

            //Draft array
            $sqlDraft = "SELECT form_id,form_name FROM form WHERE form_status = 0 AND user_id = $userID ORDER BY form_id DESC";
            $queryDraftForm = mysqli_query($conn,$sqlDraft);
            $formDrafts = array();
            while ($rows = mysqli_fetch_assoc($queryDraftForm)){
              $formDrafts[] = $rows;
            }
            $draft = sizeof($formDrafts);

            //Published array
            $sqlPublish = "SELECT form_id,form_name FROM form WHERE form_status = 1 AND user_id = $userID ORDER BY form_id DESC";
            $queryPublishForm = mysqli_query($conn,$sqlPublish);
            $formPublisheds = array();
            while ($rows = mysqli_fetch_assoc($queryPublishForm)){
              $formPublisheds[] = $rows;
            }
            $published = sizeof($formPublisheds);

            //Expired array
            $sqlExpired = "SELECT form_id,form_name FROM form WHERE form_status = 2 AND user_id = $userID ORDER BY form_id DESC";
            $queryExpiredForm = mysqli_query($conn,$sqlExpired);
            $formExpireds = array();
            while ($rows = mysqli_fetch_assoc($queryExpiredForm)){
              $formExpireds[] = $rows;
            }
            $expired = sizeof($formExpireds);
          ?>



          <!--New Survey-->
          <h3><b>Create New Survey</b></h3>
          <div class="row">
            <div class="col-md-6 col-lg-3 wow bounceInUp" data-wow-duration="1.4s">
              <div class="box">
                <div class="icon" style="background: #d2f1f9;"><i class="fa fa-plus" style="color: #49c6e9;"></i></div>
                <h4 class="title"><a href="newsurvey.php">New Survey</a></h4>
                <p class="description"></p>
              </div>
            </div>
          </div>


          <!-- Published -->
          <div style="background: #fff; padding-top: 30px; margin-bottom: 30px;">
            <div class="container">
          <h3><b>Published</b></h3>
          <?php
          if($published > 4){
            echo '
            <button type="button" data-toggle="collapse" class="viewMoreBtn" href="#publishedCollapse" style="float:right;"><i class="fa fa-bars"></i>&nbsp;View More</button>
            ';
          }
          ?>
          <div class="row">
            <?php
            if($published > 4){
              $pubCount = 0;
            }
            if(!empty($formPublisheds)){
              foreach($formPublisheds as $formPublished){
                $id = $formPublished['form_id'];
                $name = $formPublished['form_name'];
                if($published > 4){
                  if($pubCount < 4){
                    echo "<div class='col-md-6 col-lg-3 wow bounceInUp' data-wow-duration='1.4s'>
                          <div class='box'>
                            <div class='icon' style='background: #d2f1f9;'><i class='fa fa-file-text' style='color: #49c6e9;'></i></div>
                            <h4 class='title'><a href='viewSurvey.php?form=$id'>$name</a></h4>
                            <p class='description'></p>
                          </div>
                        </div>";
                    $pubCount++;
                  }
                }
                else{
                  echo "<div class='col-md-6 col-lg-3 wow bounceInUp' data-wow-duration='1.4s'>
                        <div class='box'>
                          <div class='icon' style='background: #d2f1f9;'><i class='fa fa-file-text' style='color: #49c6e9;'></i></div>
                          <h4 class='title'><a href='viewSurvey.php?form=$id'>$name</a></h4>
                          <p class='description'></p>
                        </div>
                      </div>";
                    }
              }
            }
            else{
              echo "<p align='center' style='color:red; flex:1; font-family:'><b>There are no forms to display!</b></p>";
            }
            ?>
          </div>

          <?php
            if($published > 4){
              $publishedCount = $published - 4;
              echo '
              <div class="collapse" id="publishedCollapse">
                <div class="row">
                ';
                if(!empty($formPublisheds)){
                  foreach($formPublisheds as $formPublished){
                    $id = $formPublished['form_id'];
                    $name = $formPublished['form_name'];
                    if($publishedCount >= $id){
                      echo "<div class='col-md-6 col-lg-3'>
                            <div class='box'>
                              <div class='icon' style='background: #d2f1f9;'><i class='fa fa-file-text' style='color: #49c6e9;'></i></div>
                              <h4 class='title'><a href='viewSurvey.php?form=$id'>$name</a></h4>
                              <p class='description'></p>
                            </div>
                          </div>";
                      }
                  }
                }
              echo '
                </div>
              </div>
            ';
            }
          ?>

          </div>
          </div>


          <!-- Draft -->
          <div class="container">
              <h3><b>Draft</b></h3>
              <?php
              if($draft > 4){
                echo '
                <button type="button" data-toggle="collapse" class="viewMoreBtn" href="#draftCollapse" style="float:right;"><i class="fa fa-bars"></i>&nbsp;View More</button>
                ';
              }
              ?>
              <div class="row">
                <?php
                if($draft > 4){
                  $draCount = 0;
                }
                if(!empty($formDrafts)){
                  foreach($formDrafts as $formDraft){
                    $id = $formDraft['form_id'];
                    $name = $formDraft['form_name'];
                    if($draft > 4){
                      if($draCount < 4){
                        echo"<div class='col-md-6 col-lg-3 wow bounceInUp' data-wow-duration='1.4s'>
                        <div class='box'>
                        <div class='icon' style='background: #d2f1f9;'><i class='fa fa-file-text' style='color: #49c6e9;'></i></div>
                        <h4 class='title'><a href='editSurvey.php?form=$id'>$name</a></h4>
                        <p class='description'></p>
                        </div>
                        </div>";
                        $draCount++;
                      }
                    }
                    else{
                      echo"<div class='col-md-6 col-lg-3 wow bounceInUp' data-wow-duration='1.4s'>
                      <div class='box'>
                      <div class='icon' style='background: #d2f1f9;'><i class='fa fa-file-text' style='color: #49c6e9;'></i></div>
                      <h4 class='title'><a href='viewSurvey.php?form=$id'>$name</a></h4>
                      <p class='description'></p>
                      </div>
                      </div>";
                    }
                  }
                }
                else{
                  echo "<p align='center'style='color:red; flex:1;'><b>There are no forms to display!</b></p>";
                }
                ?>
              </div>

              <?php
                if($draft > 4){
                  $draftCount = $draft - 4;
                  echo '
                  <div class="collapse" id="draftCollapse">
                    <div class="row">
                    ';
                    if(!empty($formDrafts)){
                      foreach($formDrafts as $formDraft){
                        $id = $formDraft['form_id'];
                        $name = $formDraft['form_name'];
                        if($draftCount >= $id){
                          echo"<div class='col-md-6 col-lg-3'>
                                <div class='box'>
                                  <div class='icon' style='background: #d2f1f9;'><i class='fa fa-file-text' style='color: #49c6e9;'></i></div>
                                  <h4 class='title'><a href='editSurvey.php?form=$id'>$name</a></h4>
                                  <p class='description'></p>
                                </div>
                              </div>";
                          }
                      }
                    }
                  echo '
                    </div>
                  </div>
                ';
                }
              ?>

            </div>


            <!-- Expired -->
            <div style="background: #fff; padding-top: 30px; margin-bottom: 30px;">
              <div class="container">
            <h3><b>Expired</b></h3>
            <?php
            if($expired > 4){
              echo '
              <button type="button" data-toggle="collapse" class="viewMoreBtn" href="#expiredCollapse" style="float:right;"><i class="fa fa-bars"></i>&nbsp;View More</button>
              ';
            }
            ?>
            <div class="row">
              <?php
              if($expired > 4){
                $expCount = 0;
              }
              if(!empty($formExpireds)){
                foreach($formExpireds as $formExpired){
                  $id = $formExpired['form_id'];
                  $name = $formExpired['form_name'];
                  if($expired > 4){
                    if($expCount < 4){
                      echo "<div class='col-md-6 col-lg-3 wow bounceInUp' data-wow-duration='1.4s'>
                            <div class='box'>
                              <div class='icon' style='background: #fceef3;'><i class='ion-ios-analytics-outline' style='color: #ff689b;'></i></div>
                              <h4 class='title'><a href='viewSurvey.php?form=$id'>$name</a></h4>
                              <p class='description'></p>
                            </div>
                          </div>";
                      $expCount++;
                    }
                  }
                  else{
                    echo "<div class='col-md-6 col-lg-3'>
                          <div class='box'>
                            <div class='icon' style='background: #d2f1f9;'><i class='fa fa-file-text' style='color: #49c6e9;'></i></div>
                            <h4 class='title'><a href='viewSurvey.php?form=$id'>$name</a></h4>
                            <p class='description'></p>
                          </div>
                        </div>";
                    }
                }
              }
              else{
                echo "<p align='center'style='color:red; flex:1;'><b>There are no forms to display!</b></p>";
              }
              ?>
            </div>

            <?php
              if($expired > 4){
                $expiredCount = $expired - 4;
                echo '
                <div class="collapse" id="expiredCollapse">
                  <div class="row">
                  ';
                  if(!empty($formExpireds)){
                    foreach($formExpireds as $formExpired){
                      $id = $formExpired['form_id'];
                      $name = $formExpired['form_name'];
                      if($expiredCount >= $id){
                        echo "<div class='col-md-6 col-lg-3'>
                              <div class='box'>
                                <div class='icon' style='background: #d2f1f9;'><i class='fa fa-file-text' style='color: #49c6e9;'></i></div>
                                <h4 class='title'><a href='viewSurvey.php?form=$id'>$name</a></h4>
                                <p class='description'></p>
                              </div>
                            </div>";
                        }
                    }
                  }
                echo '
                  </div>
                </div>
              ';
              }
            ?>

            </div>
          </div>
        </div>
      </section>

    <?php include "footer.php"; ?>
    <script>
      function viewForm(){
        var formId = <?php echo $id;?>;
        console.log(formId);
        $.ajax({
          dataType:'json',
          type:'post',
          data : 'form_name=' + form_name + '&form_format=' + form_format,
          url:'survey.php',
          success: function(data) {
              window.location.href = 'survey.php'
          }
        });
      }
    </script>

  </body>
  </html>
