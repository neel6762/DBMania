<?php
    session_start();
    require_once "dbConnection.php";
    if(!$_SESSION['loggedInUser']){
        header('Location:index.php');
    }

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin</title>
    <link rel="stylesheet" href="src/css/bootstrap.css">
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        html, body {
            height: 100%;
            width: 100%;
        }

        body {
            background: url("src/images/bg-landscape.jpg");
            font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
    <div class="row">
        <div class="col-lg-2 d-none d-lg-block" style="padding-left: 30px">
            <img src="src/images/SPCE_logo.png" alt="SPCE_logo" height="150">
        </div>
        <div class="col-lg-8 text-light text-center">
            <hr class="bg-light">
            <h1 class="white-color"><b>SARDAR PATEL COLLEGE OF ENGINEERING</b></h1>
            <h4 class="main-Color"><b>Department of Information Techonology</b></h4>
            <hr class="bg-light">
        </div>
        <div class="col-lg-2 d-none d-lg-block">
            <img src="src/images/SPECTECH_logo.png" alt="SPECTECH_logo" height="150">
        </div>
    </div>
</div>
    <div class="container my-4 bg-light p-2">
        <div class="row">
            <div class="col">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th>Team_id</th>
                            <th>College</th>
                            <th>Score</th>
                            <th>Start_time</th>
                            <th>End_time</th>
                            <th>Time_taken</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $query = "SELECT * FROM test ORDER BY score DESC";
                            $result = mysqli_query($con, $query);

                            if(mysqli_num_rows($result) > 0){
                                while($rows = mysqli_fetch_assoc($result)){
                                    $team_id = $rows['team_id'];
                                    $college = $rows['college'];
                                    $score = $rows['score'];
                                    $start_time = $rows['start_time'];
                                    $end_time = $rows['end_time'];
                                    $time_taken = $rows['time_taken'];
                                    ?>
                                    <tr>
                                        <td><?php echo $team_id; ?></td>
                                        <td><?php echo $college; ?></td>
                                        <td><?php echo $score; ?></td>
                                        <td><?php echo $start_time; ?></td>
                                        <td><?php echo $end_time; ?></td>
                                        <td><?php echo $time_taken; ?></td>
                                    </tr>
                                <?php
                                }
                            }
                         ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>
