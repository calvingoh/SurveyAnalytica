<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Survey result</title>
<?php include "head.php"; ?>

<body>
    <?php include "header.php"; ?>

    <?php
    include "database.php";
    // $surveyId = $_GET['surveyid'];

    $sql = "SELECT duration FROM user_reply WHERE form_id = '7'";
    $result = mysqli_query($conn, $sql);
    $sql2 = "SELECT form_visit FROM form WHERE form_id = '7'";
    $durations = array();
    while ($row = mysqli_fetch_assoc($result)){
      $durations[]=$row;
    }
    $amoutOfReply = sizeof($durations);
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

      $averageTimeMiSe = $totalMillisec/$amoutOfReply;
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
      $result2 = mysqli_query($conn, $sql2);
      while ($row = mysqli_fetch_assoc($result2)){
        $totalVisitA=$row;
        echo $totalVisitA;
      }
      $totalVisit = implode("",$totalVisitA);
      $completion= ($amoutOfReply/$totalVisit)*100;
      $stringForCompletion = "%";
      $format2 = '%.2f %s' ;
      $completionRate = sprintf($format2, $completion,$stringForCompletion);
    ?>



    <div id="result" class="container">
      <header class="section-header">
        <h3>Survey</h3>
      </header>

      <div class="row">
        <div class="col wow bounceInRight" data-wow-duration="1.4s">
          <div class="box">
            <div class="icon" style="background: #fff0da;"><i class="ion-android-list" style="color: #e98e06;"></i></div>
            <h4 class="title">Total Questions</h4>
            <p class="description">0</p>
          </div>
        </div>
        <div class="col wow bounceInRight" data-wow-duration="1.4s">
          <div class="box">
            <div class="icon" style="background: #fff0da;"><i class="ion-android-create" style="color: #e98e06;"></i></div>
            <h4 class="title">Total Responses</h4>
            <p class="description"><?php echo $amoutOfReply?></p>
          </div>
        </div>
        <div class="col wow bounceInRight" data-wow-duration="1.4s">
          <div class="box">
            <div class="icon" style="background: #fff0da;"><i class="ion-eye" style="color: #e98e06;"></i></div>
            <h4 class="title">Total Visits</h4>
            <p class="description"><?php echo $totalVisit?></p>
          </div>
        </div>
      </div> <!--end div row-->

      <div class="row">
        <div class="col wow bounceInLeft" data-wow-duration="1.4s">
          <div class="box">
            <div class="icon" style="background: #fff0da;"><i class="ion-android-time" style="color: #e98e06;"></i></div>
            <h4 class="title">Average Completion Time</h4>
            <p class="description"><?php echo $averageTime?></p>
          </div>
        </div>
        <div class="col wow bounceInLeft" data-wow-duration="1.4s">
          <div class="box">
            <div class="icon" style="background: #fff0da;"><i class="ion-checkmark" style="color: #e98e06;"></i></div>
            <h4 class="title">Completion Rate</h4>
            <p class="description"><?php echo $completionRate?></p>
          </div>
        </div>
      </div> <!--end div row-->

      </div>
    <?php include "footer.php"; ?>
  </body>
  </html>
