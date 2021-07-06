<?php session_start();
  include 'Question.php';
  include "database.php";
  if(isset($_GET['form'])){
    $id=$_GET['form'];
    $sql = "SELECT form_format FROM form WHERE form_id=$id";  //get form format
    $sql2 = "SELECT form_name FROM form WHERE form_id=$id";  //get form name
    $sql3 = "SELECT user_reply_format FROM user_reply WHERE form_id=$id";  //get user replies for particular form
    $sql4 = "SELECT form_status FROM form WHERE form_id=$id";  //get form form_status
    $sql5 = "SELECT duration FROM user_reply WHERE form_id=$id";  //get duration of each user reply
    $sql6 = "SELECT form_visit FROM form WHERE form_id=$id";  //get noOfVisits for form

    $query = mysqli_query($conn,$sql);
    $query2 = mysqli_query($conn,$sql2);
    $query3 = mysqli_query($conn,$sql3);
    $query4 = mysqli_query($conn,$sql4);
    $query5 = mysqli_query($conn,$sql5);
    $query6 = mysqli_query($conn,$sql6);

    $row1 = mysqli_fetch_assoc($query);
    $row2 = mysqli_fetch_assoc($query2);

    while ($row3 = mysqli_fetch_assoc($query3)){  //loop through each user reponses
      foreach ($row3 as $key) {
        $replyFormat[] = implode($row3);  //store each user reponse in array
      }
    }

    $row4 = mysqli_fetch_assoc($query4);

    $durations = array();
    while ($row = mysqli_fetch_assoc($query5)){
      $durations[] = $row;
    }

    while ($row = mysqli_fetch_assoc($query6)){
      $strVisits = $row;
    }

    $formFormat = implode($row1);
    $formName = implode($row2);
    $publishStatus = implode($row4);

    if (!empty($replyFormat))
    {
      //for average duration
      //---------------
      $noOfResponse = sizeof($durations);
      $totalMillisec = 0.0;
      foreach($durations as $duration){
        $durationSingle = $duration['duration'];
        $timeString = '00:38:42';
        $dateTime   = DateTime::createFromFormat('H:i:s', $durationSingle);

        $milliseconds =
            $dateTime->format('H') * 60 * 60 * 1000 +  // milliseconds in an hour
            $dateTime->format('i')      * 60 * 1000 +  // milliseconds in a minute
            $dateTime->format('s')           * 1000;  // milliseconds in a second

        $totalMillisec+=$milliseconds;
      }

      $averageTimeMiSe = $totalMillisec/$noOfResponse;
      $seconds = floor($averageTimeMiSe/ 1000);
      $minutes = floor($seconds / 60);
      $hours = floor($minutes / 60);
      $milliseconds = $milliseconds % 1000;
      $seconds = $seconds % 60;
      $minutes = $minutes % 60;
      if($hours == 0){
        $format = '%02u minutes %02u seconds';
        $averageTime = sprintf($format, $minutes, $seconds);
      }
      else{
        $format = '%u hour %02u minutes %02u seconds';
        $averageTime = sprintf($format, $hours, $minutes, $seconds);
      }
    }

    //for total visits
    //---------------
    $totalVisit = implode("",$strVisits);
    if (!empty($replyFormat))
      $completion = ($noOfResponse/$totalVisit)*100;
    else
      $completion = 0;
    $stringForCompletion = "%";
    $format2 = '%.2f %s' ;
    $completionRate = sprintf($format2, $completion,$stringForCompletion);

    //for form format
    //---------------
    $arrFormFormat = json_decode($formFormat, true);
    $questionName = array();
    $counter = 0;
    //to get question name and get label from string array
    foreach($arrFormFormat as $arrs){
      if ($arrs['type'] == "checkbox-group" ||
          $arrs['type'] == "radio-group" ||
          $arrs['type'] == "select"){
        $values[$counter] = $arrs['values'];
      }
      else
        $values[$counter] = array();

      array_push($questionName, $arrs['label']);
      $counter++;
    }

    $noOfQuestions = count($questionName);
    //to get no of questions and set question name
    $question = array();
    for ($i = 0; $i < $noOfQuestions; $i++){
      $question[$i] = new Question();
      $question[$i]->set_name($questionName[$i]);

    }
    $counter = NULL;

    //to break down array and get individual labels
    for ($i = 0; $i < $noOfQuestions; $i++){
      $noOfLabels = count ($values[$i]);
      for ($j = 0; $j < $noOfLabels; $j++){
        //get individual label and store in object
        foreach($values[$i][$j] as $key => $val){
          if ($key == "value"){
            if (end($question[$i]->label) != $val){
              $question[$i]->label[] = $val;
              $question[$i]->count[] = 0;
            }
          }
        }
      }
    }
    //---------------
    //form format end

    if (!empty($replyFormat))
    {
      //declare array to be converted to excel format
      $arrExcel = array();
      for ($i = 0; $i < $noOfResponse; $i++){
        foreach($questionName as $ques){
          $arrExcel[$i][$ques] = "";
        }
      }

      //for reply format
      //----------------
      $numOfResponse = count($replyFormat);
      $emptyArray = array(0 => "");
      for($i = 0; $i < $numOfResponse; $i++){  //loop through each user reponse
        $arr = json_decode($replyFormat[$i], true); //change the data from string to array

        foreach($arr as $arrs){
          //this loop stores data in 2d array
          if (empty($arrs['userData'])){
            $data['userResponse'][] = array('userData' => $emptyArray);
          }
          else {
            $data['userResponse'][] = array('userData' => $arrs['userData']);
          }
        }

        $noOfQuestions = 0;
        foreach($data['userResponse'] as $singleQues){
          //load values into excel array
            $arrValues = implode(", ",$singleQues['userData']);
            $questionName = $question[$noOfQuestions]->name;
            $arrExcel[$i][$questionName] = $arrValues;
          //this loop gets the data of each question
          $noOfData = count($singleQues['userData']);
          $k = 0;
          $successCount = 0;
          while ($successCount != $noOfData){
            if ($singleQues['userData'][$successCount] == "")
                break;

            if ($question[$noOfQuestions]->label != array()){
              if ($singleQues['userData'][$successCount] == $question[$noOfQuestions]->label[$k]){
                //stopped here last night, questions in excel arr already created, juust need to pump values into it rn
                $dataCount = $question[$noOfQuestions]->count[$k];
                $dataCount++;
                $question[$noOfQuestions]->count[$k] = $dataCount;
                $successCount++;
              }
            }
            else {
              $question[$noOfQuestions]->strValue[] = $singleQues['userData'][$successCount];
              $successCount++;
            }
            $k++;
          }
          $noOfQuestions++;
        }
        $data = array(); //clear data for every user response
      } //end for loop
    }
    if (!empty($arrExcel)){
      file_put_contents("arrExcel.json", json_encode($arrExcel));
      file_put_contents("excelName.json", json_encode($formName));
    }
  } //end isset($_GET['form'])
  $excelData = json_decode(file_get_contents('arrExcel.json'), true);
  $excelName = json_decode(file_get_contents('excelName.json'), true);
  if(isset($_POST["ExportType"]))
  {
  	$filename = $excelName. ".xls";
          header("Content-Type: application/vnd.ms-excel");
  	header("Content-Disposition: attachment; filename=\"$filename\"");
  	ExportFile($excelData);
          exit();
  }

  function ExportFile($records) {
  	$heading = false;
  		if(!empty($records))
  		  foreach($records as $row) {
  			if(!$heading) {
  			  // display field/column names as a first row
  			  echo implode("\t\t", array_keys($row)) . "\n";
  			  $heading = true;
  			}
  			echo implode("\t\t", array_values($row)) . "\n";
  		  }
  		exit;
  }
?>

<style>
.content {
  max-width: 500px;
  height: 430px;
  margin: auto;
  background: white;
  padding-top:30px;
}

#btn_bar {
  font-size:12px;
}

#btn_pie {
  font-size:12px;
  background:#FF0000;
  border-color:#FF0000;
}

#btn_pie:hover{
  background:#e60000;
  border-color:#e60000;
}

#btn_polar {
  font-size:12px;
  background:#ffae42;
  border-color:#ffae42;
}

#btn_polar:hover{
  background:#ff9c1a;
  border-color:#ff9c1a;
}

#btn_publish {
  font-size:12px;
  background:#008F11;
  border-color:#008F11;
}

#btn_publish:hover{
  background:#00800F;
  border-color:#00800F;
}

#btn_share {
  font-size:12px;
  background:#be70f5;
  border-color:#be70f5;
}

#btn_share:hover{
  background:#a840f2;
  border-color:#a840f2;
}

#export-to-excel {
  font-size:12px;
  background:#1d6f42;
  border-color:#1d6f42;
}

#export-to-excel:hover{
  background:#155130;
  border-color:#155130;
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
    <script src="https://kevinchappell.github.io/formBuilder/assets/js/form-render.min.js"></script>
    <script src="http://localhost/surveylab/formbuilder/dist/form-builder.min.js"></script>
    <!-- Chart.js Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.0/dist/Chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.0/dist/Chart.bundle.min.js"></script>
    <!--==========================
    Intro Section
    ============================-->
    <main id="main">
      <section id="why-us" class="section-bg" style="font-size: 17px;">
        <div class="container">
          <div class = "ht-70"></div>
          <h2><b>Form Preview</b></h2>

          <input type="hidden" id="formId" value ="<?php echo $id; ?>"></input>
          <div id="form_builder" class="build-wrap"></div>
          <h3><?php echo $formName; ?></h3>

          <div><form id="fb-render"></form></div>
          <div class = 'ht-10'></div>
          <?php echo "<a class='btn btn-primary' id='btn_bar' style='font-size:14px;'
            href='usersurvey.php?form=$id' target='_blank'>
            <i class='fa fa-comment'></i> &nbsp;View Response Form </a>&nbsp;&nbsp;";

            if ($publishStatus != 1){
              echo "<a class='btn btn-primary' id='btn_publish' style='font-size:14px; color:#fff'
              data-toggle='modal' data-target='#preview'>
              <i class='fa fa-upload'></i> &nbsp;Publish Form</a>&nbsp;&nbsp;";

              if ($publishStatus == 0){
                echo "<a class='btn btn-primary' id='btn_polar' style='font-size:14px;'
                href='editsurvey.php?form=$id'>
                <i class='fa fa-pencil'></i> &nbsp;Edit Form</a>&nbsp;&nbsp;";
              }

              echo "<a class='btn btn-primary' id='btn_pie' style='font-size:14px; color:#fff'
              data-toggle='modal' data-target='#delete'>
              <i class='fa fa-trash'></i> &nbsp;Delete Form</a>&nbsp;&nbsp;";
            }

            if ($publishStatus == 1 ){
              echo "<a class='btn btn-primary' id='btn_publish' style='font-size:14px; color:#fff'
              data-toggle='modal' data-target='#durationB'>
              <i class='fa fa-clock-o'></i> &nbsp;Change Publish Duration</a>&nbsp;&nbsp;";

              echo "<a class='btn btn-primary' id='btn_polar' style='font-size:14px; color:#fff'
              data-toggle='modal' data-target='#share'>
              <i class='fa fa-share-alt'></i> &nbsp;Share Form</a>&nbsp;&nbsp;";
            }
          ?>
         </div>
        </section>

        <!--form details-->
        <div class = 'ht-30'></div>
        <div id="result" class="container">
          <header class="section-header">
            <h3>Form Details</h3>
          </header>
          <div class = 'ht-20'></div>
          <div class="row">
            <div class="col wow bounceInRight" data-wow-duration="1.4s">
              <div class="box">
                <div class="icon" style="background: #fff0da;"><i class="ion-android-list"
                  style="color: #e98e06;"></i></div>
                <h4 class="title">Total Questions</h4>
                <p class="description"><b><?php echo $noOfQuestions?></b></p>
              </div>
            </div>
            <div class="col wow bounceInRight" data-wow-duration="1.4s">
              <div class="box">
                <div class="icon" style="background: #fff0da;"><i class="ion-android-create"
                  style="color: #e98e06;"></i></div>
                <h4 class="title">Total Responses</h4>
                <p class="description">
                  <b><?php if (!empty($replyFormat)) echo $noOfResponse; else echo "0";?></b>
                </p>
              </div>
            </div>
            <div class="col wow bounceInRight" data-wow-duration="1.4s">
              <div class="box">
                <div class="icon" style="background: #fff0da;">
                  <i class="ion-eye" style="color: #e98e06;"></i>
                </div>
                <h4 class="title">Total Visits</h4>
                <p class="description">
                  <b><?php if (!empty($replyFormat)) echo $totalVisit; else echo "0";?></b>
                </p>
              </div>
            </div>
          </div> <!--end div row-->

          <div class="row">
            <div class="col wow bounceInLeft" data-wow-duration="1.4s">
              <div class="box">
                <div class="icon" style="background: #fff0da;">
                  <i class="ion-android-time" style="color: #e98e06;"></i>
                </div>
                <h4 class="title">Average Completion Time</h4>
                <p class="description">
                  <b><?php if (!empty($replyFormat)) echo $averageTime; else echo "00 minutes 00 seconds"?></b>
                </p>
              </div>
            </div>
            <div class="col wow bounceInLeft" data-wow-duration="1.4s">
              <div class="box">
                <div class="icon" style="background: #fff0da;">
                  <i class="ion-checkmark" style="color: #e98e06;"></i></div>
                <h4 class="title">Completion Rate</h4>
                <p class="description"><b><?php echo $completionRate?></b></p>
              </div>
            </div>
          </div> <!--end div row-->
        </div>

        <!--Response Analysis-->
        <section id="why-us" class="section-bg" style="background:#fff;">
          <div class="container">
            <h2 style = "display: inline-block;"><b>Response Analysis</b></h2>
            <?php if (!empty($replyFormat))
              echo "<button class='btn btn-primary' id='export-to-excel' style='font-size:14px; color:#fff; float:right'>
              <i class='fa fa-table'></i><a href='#'></a> &nbsp;Export Data to Excel</button>&nbsp;&nbsp;"; ?>
              <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" id="export-form">
  						<input type="hidden" value='' id='hidden-type' name='ExportType'/>
  					  </form>
            <div class = 'ht-30'></div>
              <?php
                for ($i = 0; $i < $noOfQuestions; $i++){
                  echo "<h4>";
                  echo "Question ".(++$i);
                  echo "</h4>";
                  $i--;
                  $quesName = $question[$i]->get_name();
                  echo "<div class = 'ht-5'></div>
                        <div class='row' style='height:360px;'>";
                  if ($question[$i]->label != array()){
                      echo "<div class='col-6'>";
                        echo "<div style ='font-size:21px;'><b>$quesName</b></div>";
                        echo "<div class = 'ht-20'></div>";
                        $counter = 0;
                        foreach ($question[$i]->get_label() as $val) {
                          $counter++;
                          $choiceStr = "Option ".$counter.": ".$val;
                          echo "<div style ='font-size:17px;'>$choiceStr</div>";
                          echo "<div class = 'ht-10'></div>";
                        }
                      echo "</div>
                      <div class='col-6'>";
                      echo "<canvas id='canvas$i' height='150'></canvas>
                      </div>"; //end col-6
                  }
                  else
                  {
                    echo "<div class='col-6'>
                      <div style ='font-size:21px;'><b>$quesName</b></div>
                      <div class = 'ht-5'></div>";
                      echo "<div style ='font-size:17px;'>User Responses: </div>";
                      echo "<div class = 'ht-10'></div>";
                      $counter = 0;
                      foreach ($question[$i]->get_strValue() as $val) {
                        $counter++;
                        $choiceStr = "Response ".$counter." : &nbsp;".$val;
                        echo "<div style ='font-size:17px;'>$choiceStr</div>";
                        echo "<div class = 'ht-10'></div>";
                      }
                    echo"</div>"; //end col-6
                    $noGraph = 'Unfortunately, data analysis for this question is not available.';
                    echo "<div class='col-6'>
                      <div class = 'ht-160'></div>
                      <div style ='font-size:14px; color:#808080; text-align:center;'>$noGraph</div>
                    </div>";
                  }
                  echo "</div>";
                  echo "<div class = 'ht-30'></div>";
                }
              ?>
          </div>
        </section>
      </main>

      <!--==========================
      Confirm Delete Form
      ============================-->
      <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header text-center">
              <h4 class="modal-title w-90 font-weight-bold">Delete Form</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body mx-3">
              <h5>Are you sure you want to delete the form <?php echo $formName ?>?</h5>
            </div>
            <div class="modal-footer d-flex justify-content-center">
              <input type='button' value ="Delete" class="btn btn-danger" onclick ='deleteConfirmed()'></input>
              <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
            </div>
          </div>
        </div>
      </div>

      <!--==========================
    	Form Preview
    	============================-->
      <div class="modal fade" id="preview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header text-center">
              <h4 class="modal-title w-90 font-weight-bold">Preview Form</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body mx-3">
              <h3><span id="myText"></span></h3>
              <div class="fb-render"></div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
              <input type='button' value ="Next" class='btn btn-success'
    	   data-toggle="modal" data-target="#duration" data-dismiss="modal" ></input>
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
          </div>
        </div>
      </div>


    <!--==========================
  	Set duration
  	============================-->
	  <div class="modal fade" id="duration" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
		aria-hidden="true">
		<div class="modal-dialog" role="document">
		  <div class="modal-content">
			<div class="modal-header text-center">
			  <h4 class="modal-title w-90 font-weight-bold">Publish Form</h4>
			  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			  </button>
			</div>
			<div class="modal-body mx-3">
          <div>
            <h3>Set Duration of Survey</h3>
            <select required id="expDays" class="form-control">
              <option value="1">1 day</option>
              <?php
                for($i=2; $i <= 30; $i++)
                  echo "<option value=\"$i\">$i days</option>";
              ?>
              <option value="999">Forever</option>
            </select>
            <br>
          </div>
  			</div>
  			<div class="modal-footer d-flex justify-content-center">
  			  <button type="button" onclick ='publishConfirmed()' class="btn btn-success"
          data-toggle="modal" data-target="#share" data-dismiss="modal">Confirm</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        </div>
		  </div>
		</div>
	  </div>

    <!--==========================
  	Change Duration
  	============================-->
	  <div class="modal fade" id="durationB" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
		aria-hidden="true">
		<div class="modal-dialog" role="document">
		  <div class="modal-content">
			<div class="modal-header text-center">
			  <h4 class="modal-title w-90 font-weight-bold">Publish Form</h4>
			  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			  </button>
			</div>
			<div class="modal-body mx-3">
          <div>
            <h3>Change Duration of Survey</h3>
            <select required id="expDaysB" class="form-control">
              <option value="1">1 day</option>
              <?php
                for($i=2; $i <= 30; $i++)
                  echo "<option value=\"$i\">$i days</option>";
              ?>
              <option value="999">Forever</option>
            </select>
            <br>
          </div>
  			</div>
  			<div class="modal-footer d-flex justify-content-center">
  			  <button type="button" onclick ='setDuration()' class="btn btn-success"
          data-dismiss="modal">Confirm</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        </div>
		  </div>
		</div>
	  </div>

  	<!--==========================
  	Share methods
  	============================-->
	  <div class="modal fade" id="share" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
		aria-hidden="true">
		<div class="modal-dialog" role="document">
		  <div class="modal-content">
			<div class="modal-header text-center">
			  <h4 class="modal-title w-90 font-weight-bold">Share Form</h4>
			  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			  </button>
			</div>
			<div class="modal-body mx-3">
				<section id="services" style="padding: 10px 0px 0px 0px;">
				  <div class="row">
  					<div class="col-md-6">
  					  <div class="box" data-toggle="modal" data-target="#link_share"
              data-dismiss="modal" aria-label="Close" style="padding: 15px;" >
  						<div class="icon"><i class="fa fa-link" style="color: #a6a6a6;"></i></div>
  						<h4 class="title"><a>URL Link</a></h4>
  					  </div>
  					</div>
            <div class="col-md-6">
  					  <div class="box" data-toggle="modal" data-target="#email_share"
              data-dismiss="modal" aria-label="Close" style="padding: 15px;">
  						<div class="icon"><i class="fa fa-envelope" style="color: #a6a6a6;"></i></div>
  						<h4 class="title"><a>Email</a></h4>
  					  </div>
  					</div>
				  </div>
          <!-- <div class="row">
            <div class="col-md-3"></div>
  					<div class="col-md-6">
              <php $tweetLink = "https://twitter.com/intent/tweet?original_referer=http%3A%2F%2Flocalhost%2FsurveylabLatest%2FviewSurvey.php%3Fform%3D11&ref_src=twsrc%5Etfw&text=Check%20out%20my%20latest%20form%20created%20at%20Survey%20Analytica!%20Show%20me%20some%20support%20by%20filling%20it%20in!&tw_p=tweetbutton&url=http%3A%2F%2Flocalhost%2Fsurveylab%2Fusersurvey.php%3Fform%3D$id";
  					  echo "<div class='box' data-dismiss='modal' aria-label='Close' style='padding: 15px;' >
    						<div class='icon'><i class='fa fa-twitter' style='color: #00acee;'></i></div>
    						<h4 class='title'><a href = $tweetLink>Twitter</a></h4>
  					  </div>"; >
  					</div>
          </div> -->
				</section>
			</div>
			<div class="modal-footer d-flex justify-content-center">
			  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		  </div>
		</div>
	  </div>

	<!--==========================
	Share via Link
	============================-->
	  <div class="modal fade" id="link_share" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
		aria-hidden="true">
		<div class="modal-dialog" role="document">
		  <div class="modal-content">
			<div class="modal-header text-center">
			  <h4 class="modal-title w-90 font-weight-bold">Share via Link</h4>
			  <button type="button" class="close" data-dismiss="modal" aria-label="Close"
        data-toggle="modal" data-target="#share">
				<span aria-hidden="true">&times;</span>
			  </button>
			</div>
			<div class="modal-body mx-3">
          <div class = "ht-30"></div>
          <h5>Link</h5>
          <?php
            echo "<input type='text' id='copyLink' class='form-control input-text-css'
            value='http://localhost/surveylab/usersurvey.php?form=$id' name='share_link'>";
          ?>
          <div class = "ht-10"></div>
          <a id="copyBtn" class="btn btn-primary"  style="color: #fff; font-size: 12px;" >
            <i class="fa fa-link"></i> Copy Link
          </a>

			</div>
			<div class="modal-footer d-flex justify-content-center">
			  <button type="button" class="btn btn-danger" data-dismiss="modal" data-toggle="modal"
        data-target="#share">Close</button>
			</div>
		  </div>
		</div>
	  </div>

    <!--==========================
  	Share via Email
  	============================-->
  	  <div class="modal fade" id="email_share" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  		aria-hidden="true">
  		<div class="modal-dialog" role="document">
  		  <div class="modal-content">
  			<div class="modal-header text-center">
  			  <h4 class="modal-title w-90 font-weight-bold">Share via Email</h4>
  			  <button type="button" class="close" data-dismiss="modal" aria-label="Close"
          data-toggle="modal" data-target="#share">
  				<span aria-hidden="true">&times;</span>
  			  </button>
  			</div>
        <div class="modal-body mx-3">
            <div class = "ht-10"></div>
            <h6>Enter receipient's email address</h6>
              <textarea class='form-control input-text-css' name='email_receipient'
                id='email_receipient' rows="2"
                placeholder='e.g. johncena@gmail.com, student@kdu-online.com,' required></textarea>
            <div class = "ht-30"></div>

            <h6>Subject</h6>
              <?php echo"<input type='text' class='form-control input-text-css' name='email_subject'
              id='email_subject' value = '$formName' required></input>"
              ?>
            <div class = "ht-30"></div>

            <h6>Message</h6>
              <?php echo"
              <textarea class='form-control input-text-css' name='email_message' id='email_message' rows='3'
                required>Check out my latest survey created at Survey Lab! Show me some support by filling it in!
                http://localhost/surveylabLatest/usersurvey.php?form=$id
              </textarea>"
              ?>
            <div class = "ht-10"></div>
            <a class="btn btn-info email_button" style="color: #fff; font-size: 12px; float: right;"
            onclick="sendEmail()" id = "email_button" >Send Email</a>

  			</div>
  			<div class="modal-footer d-flex justify-content-center">
  			  <button type="button" class="btn btn-danger" data-dismiss="modal"
          data-toggle="modal" data-target="#share">Close</button>
  			</div>
  		  </div>
  		</div>
  	  </div>


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
      document.getElementById("form_builder").style.display = "none";
      var formName = <?php echo json_encode($formName); ?>;
      $(function() {
         var fbTemplateS = '<?php echo $formFormat;?>';
         var fbTemplateA = fbTemplateS.split();

         formBuilder.actions.setData(fbTemplateA);
      });

      $(document).on('show.bs.modal', '#preview', function (e) {
        jQuery(function($) {
          var form_format = [];
          var form_name = $('#input-form-name').val();
          form_format = formBuilder.actions.getData();
          form_format = JSON.stringify(form_format);
          document.getElementById("myText").innerHTML = formName;
          console.log(form_format);
          $('.fb-render').formRender({
            dataType: 'json',
            formData: form_format
          });
        });
      });

      function submitForm(){
        var form_id = $('#formId').val();
        var form_format = [];
        var form_name = $('#input-form-name').val();
        var check = 1;
        form_format = formBuilder.actions.getData();
        form_format = JSON.stringify(form_format);
        console.log("published");
        console.log(form_id);
        console.log(form_name);
        console.log(form_format);
           $.ajax({
             dataType:'json',
             type:'post',
             data : 'form_name=' + form_name + '&form_format=' + form_format +
                    '&form_id=' + form_id + '&check=' + check,
             url:'survey.php',
           });
      }

      function deleteConfirmed(){
        var form_id = $('#formId').val();
        console.log(form_id);
        var check = 4;
           $.ajax({
             type:'post',
             data : '&form_id=' + form_id + '&check=' + check,
             url:'survey.php',
             cahce:false,
             success: function(data) {
                 window.location.href = 'deletesuccess.php';
             }
           });
      }

      function setDuration(){
        //set date
        var days = parseInt(document.getElementById("expDaysB").value);
        console.log(days);
        function addDays(noOfDays) {
            var result = new Date();
            result.setDate(result.getDate() + noOfDays);
            return result;
        }

        function formatDate(date) {
          var year = '' + date.getFullYear();
          var month = '' + (date.getMonth() + 1);
          var day = '' + date.getDate();
          if (month.length < 2)
            month = '0' + month;
          if (day.length < 2)
              day = '0' + day;
            return year+'-'+month+'-'+day;
        }
        //get end date
        var endDate = formatDate(addDays(days));
        //get current date
        var today = new Date();
        var curDate = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
        console.log(curDate);
        console.log(endDate);
        var form_id = $('#formId').val();
        var check = 5;
           $.ajax({
             type:'post',
             data :'&form_id=' + form_id + '&start_date=' + curDate + '&end_date=' + endDate + '&check=' + check,
             url:'survey.php',
             success: function(data){
               window.location.href = 'survey.php';
             }
           });
      }

      function publishConfirmed(){
        //check form
        var form_id = $('#formId').val();
        var form_format = [];
        var form_name = formName;
        var check = 2;
        form_format = formBuilder.actions.getData();
        form_format = JSON.stringify(form_format);
        console.log(form_id);
        console.log(form_name);
        console.log(form_format);

        //set date
        var days = parseInt(document.getElementById("expDays").value);

        console.log(days);
        function addDays(noOfDays) {
            var result = new Date();
            result.setDate(result.getDate() + noOfDays);
            return result;
        }

        function formatDate(date) {
          var year = '' + date.getFullYear();
          var month = '' + (date.getMonth() + 1);
          var day = '' + date.getDate();
          if (month.length < 2)
            month = '0' + month;
          if (day.length < 2)
              day = '0' + day;
            return year+'-'+month+'-'+day;
        }

        //get end date
        var endDate = formatDate(addDays(days));

        //get current date
        var today = new Date();
        var curDate = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();

           $.ajax({
             dataType:'json',
             type:'post',
             data : 'form_name=' + form_name + '&form_format=' + form_format + '&form_id=' + form_id +
                    '&start_date=' + curDate + '&end_date=' + endDate + '&check=' + check,
             url:'survey.php',
           });
      }

      </script>

    <script>
    //share via email
    function sendEmail(){
      var email = document.getElementById('email_receipient').value;
      var subject = document.getElementById('email_subject').value;
      var message = document.getElementById('email_message').value;
      var id  = document.getElementById('email_button').id;
      var email_data = [[]];
      var emailArr = email.split(',');

      for (i = 0; i < emailArr.length; i++) {
        email_data.push([emailArr[i],subject,message]);
        }

      if (email_data[1][0] == "")
        $('#'+id).text('Send Failed');
      else
      {
        $.ajax({
          url:"send_mail.php",
          method:"POST",
          data:{email_data:email_data},
          beforeSend:function(){
            $('#'+id).html('Sending...');
            $('#'+id).addClass('btn-danger');
          },
          success:function(data){
            if(data == 'ok')
            {
              $('#'+id).text('Email Sent');
              $('#'+id).removeClass('btn-danger');
              $('#'+id).removeClass('btn-info');
              $('#'+id).addClass('btn-success');
            }
            else
            {
              $('#'+id).text('Send Failed');
            }
            $('#'+id).attr('disabled', false);
          }
        })
      }

    }

    //share via link
    document.getElementById("copyBtn").addEventListener("click", function() {
        copyToClipboard(document.getElementById("copyLink"));
        document.getElementById('copyBtn').innerHTML = "Link copied!"
    });
    </script>

    <script>
    //construct chart script
    var data = <?php echo json_encode($question); ?>;

    //get color length
    function random_rgba() {
        var o = Math.round, r = Math.random, s = 255;
        return 'rgba(' + o(r()*s) + ',' + o(r()*s) + ',' + o(r()*s) + ', 0.3)';
    }

    var colorLength = 0;
    var bgColor = [];
    for (i = 0; i < data.length; i++){
      if (data[i].label.length > colorLength)
        colorLength = data[i].label.length;
    }

    for (i = 0; i < colorLength; i++){
      bgColor.push(random_rgba());
    }
    console.log(bgColor);


    //Create charts
    for (i = 0; i < data.length; i++)
    {
      if (data[i].label != 0){
        var id = "canvas" + i;
        var ctx = document.getElementById(id).getContext('2d');
        var chartdata = {
            labels: data[i].label, //question label
            datasets: [
                {
                    label: data[i].name, //question name
                    data: data[i].count, //question count
                    backgroundColor: bgColor,
                    borderColor: bgColor,
                    borderWidth: 0
                }
            ]
        };
      }

      var myChart = new Chart(ctx, {
          type: 'bar',
          data: chartdata,
          options: {
              scales: {
                  yAxes: [{
                      ticks: {
                          beginAtZero: true,
                          stepSize: 1
                      }
                  }]
              }
          }
      });
    }
    </script>
    <script>
    $(document).ready(function() {
    jQuery('#export-to-excel').bind("click", function() {
    	console.log(123);
    var target = $(this).attr('id');
    	$('#hidden-type').val(target);
    	//alert($('#hidden-type').val());
    	$('#export-form').submit();
    	$('#hidden-type').val('');
    });
        });
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
    <!--<script src="lib/jquery/jquery.min.js"></script>
    <script src="lib/jquery/jquery-migrate.min.js"></script>-->
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
    <script src="js/twitter.js"></script>
    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

    <!-- Template Main Javascript File -->
    <script src="js/main.js"></script>


</body>
</html>
