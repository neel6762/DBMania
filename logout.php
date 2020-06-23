<?php
    session_start();
    require_once 'dbConnection.php';
    if(isset($_SESSION['loggedInUser'])){
    $team_id = $_SESSION['team_id'];
    $college = $_SESSION['college'];
    $score = $_SESSION['score'];
    $start_time = $_SESSION['start_time'];
    date_default_timezone_set("Asia/Calcutta");
    if(empty($_SESSION['end_time']))
  {
    $_SESSION['end_time'] = date('H:i:s');
  }
    $end_time =  $_SESSION['end_time'];
    $start_time_new = new DateTime($start_time);
    $end_time_new = new DateTime($end_time);
    $duration =  $start_time_new->diff($end_time_new);
    $duration = (string)$duration->format("%H:%I:%S");
    $query = "UPDATE test SET score='$score',end_time='$end_time',time_taken='$duration' WHERE team_id='$team_id'";
    $result = mysqli_query($con,$query);
    }
    session_destroy();
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thank You</title>
    <link rel="stylesheet" href="src/css/bootstrap.css">
    <style>
        body{
            height: 100%;
            background-image: url('src/images/bg-landscape.jpg');
        }
        .container {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        h1,h3{
            color: #62F1D3;
        }

    </style>
</head>
<body>

    <div class="container">
        <div class="row">
            <div class="col">
                <img src="src/images/thank_you.png" alt="Thank_you" class="img-fluid">
                <h1 align="center">Results will be declared soon !</h1>
                <h3 align="center">Have a Good Day</h3>
            </div>
        </div>
    </div>

</body>
</html>
