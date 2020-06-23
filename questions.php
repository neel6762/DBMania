<?php
error_reporting(0);
session_start();
require_once "dbConnection.php";
date_default_timezone_set("Asia/Calcutta");
if (empty($_SESSION['start_time'])) {
    $_SESSION['start_time'] = date('H:i:s');
    $start_time = $_SESSION['start_time'];
    $team_id = $_SESSION['team_id'];
    $query = "UPDATE test SET start_time='$start_time' WHERE team_id='$team_id'";
    $result = mysqli_query($con, $query);
}

// echo date("H:i:s", strtotime($_SESSION['start_time'])) . "<br>";
global $final_time;
$final_time = date("H:i:s", strtotime("+30 minutes", strtotime($_SESSION['start_time'])));

if (!$_SESSION['loggedInUser']) {
      header('Location:index.php');
}
function getRamdomArray($start, $end)
{
    $array = array();
    for ($i = 1; $i <= $end; $i++) {
        $flag = true;
        $number = rand($start, $end);
        for ($j = 0; $j < count($array); $j++) {
            if ($array[$j] == $number) {
                $flag = false;
                break;
            }
        }
        if ($flag) {
            array_push($array, $number);
        } else {
            $i--;
        }
    }
    return $array;
}

function checkAnswer($con)
{
    $score = 0;
    $query = "select *from question_bank;";
    $result = mysqli_query($con, $query);
    if ($result) {
        while ($row = mysqli_fetch_array($result)) {
            $id = $row['srno'];
            $pre = "ans" . $id;
            if (isset($_SESSION[$pre])) {
                if ($_SESSION[$pre] == $row['answer']) {
                    $score++;
                }
            }
        }
    }
    return $score;
}
const totalquestion = 30;
function getQuestion($con, $number)
{
    $number = (int)$number;
    $query = "select *from question_bank where srno=" . $number . ";";
    $result = mysqli_query($con, $query);
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);
            return $row;
        } else {
            return null;
        }
    } else {
        return null;
    }
}
const prev_qn = "prev";
const currentQuestion = "currentquestion";
const questionOrderArray = "questionarray";
const summbit_question = "submit_exam";
const next = "next";
const answerPrefix = "ans";
const attempetedArray = "apt";
function getIndex($mainArray, $number)
{
    for ($i = 0; $i < count($mainArray); $i++) {
        if ($mainArray[$i] == $number) {
            return $i;
        }
    }
    return -1;
}

$question_Index = 0;
if (!isset($_POST[currentQuestion])) {
    $_SESSION[questionOrderArray] = getRamdomArray(1, totalquestion);
} else if (isset($_POST[prev_qn])) {
    $current = (int)$_POST[currentQuestion];
    if ($current > 0) {
        $question_Index = $current - 1;
    }
} else {
    $current = (int)$_POST[currentQuestion];
    $question_Index = $current + 1;
}
if (isset($_POST['option'])) {
    $current = (int)$_POST[currentQuestion];
    $name = answerPrefix . $_SESSION[questionOrderArray][$current];
    $_SESSION[$name] = $_POST['option'];
    $_SESSION[attempetedArray . ($current + 1)] = "yes";
}

if (isset($_POST[summbit_question])) {
    $score = (int)checkAnswer($con);
    $_SESSION['score'] = $score;
    header('Location:logout.php');
    exit();
}
$que = getQuestion($con, $_SESSION[questionOrderArray][$question_Index]);
$select_number = -1;
$flag = true;
if (isset($_SESSION[answerPrefix . $_SESSION[questionOrderArray][$question_Index]])) {
    $value = $_SESSION[answerPrefix . $_SESSION[questionOrderArray][$question_Index]];
    if ($value == 'a') {
        $select_number = 1;
    }
    if ($value == 'b') {
        $select_number = 2;
    }
    if ($value == 'c') {
        $select_number = 3;
    }
    if ($value == 'd') {
        $select_number = 4;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Round - 1</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css"> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
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

        .main-Color {
            color: #63F2D6;
        }

        .black-color {
            color: black;
        }

        .white-color {
            color: white;
        }

        .bg-red {
            color: red;
        }

        .bg-custom {
            background-color: #052A3C;
        }

        .form-check-label {
            font-size: 20px;
        }

        .bg-custom-light {
            background-color: #62F1D3;
        }

        .question {
            width: 35px;
            height: 30px;
            margin: 5px;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        mg-left {
            margin-left: 30px;
        }

        mg-right {
            margin-right: 30px;
        }

        form {
            height: 100%;
            width: 100%;
        }

        .fullsize {
            height: 100%;
            width: 100%;

        input {
            background: transparent;
            border: 0px;
        }

        h1 {
            text-align: center;
        }

        .flex-centre {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .attenpt {
            background-color: #62F1D3;
        }

        li, label {
            cursor: pointer;
        }
    </style>
    <script>
        function formatDate(date) {
            var monthNames = [
                "January", "Feb", "March",
                "April", "May", "June", "July",
                "August", "September", "October",
                "November", "December"
            ];

            var day = date.getDate();
            var monthIndex = date.getMonth();
            var year = date.getFullYear();

            return monthNames[monthIndex] + ' ' + day + ', ' + year + ' ';
        }

        // Set the date we're counting down to
        var final_time = "<?php echo $final_time; ?>";
        today = formatDate(new Date());

        var datetime = new Date(today + final_time);

        var countDownDate = new Date(datetime).getTime();


        var x = setInterval(function () {


            var now = new Date().getTime();
            var distance = countDownDate - now;
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);


            document.getElementById("demo").innerHTML = minutes + "m " + seconds + "s ";


            if (distance < 0) {
                clearInterval(x);
                <?php

                $score = (int)checkAnswer($con);
                $_SESSION['score'] = $score;

                ?>
                window.location.href = "logout.php";
            }
        }, 1000);
    </script>


</head>
<body onload="countdownt()">
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
<div class="container mt-2">
    <?php

    $query = "SELECT * FROM it WHERE id=" . $_SESSION['loggedInUser'];
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($rows = mysqli_fetch_assoc($result)) {
            $team_id = $rows['team_id'];
            $college = $rows['college_name'];
            $student1 = $rows['f_name'] . ' ' . $rows['l_name'];
            $_SESSION['team_id'] = $team_id;
            $_SESSION['college'] = $college;
        }
    }

    $query = "SELECT * FROM it WHERE team_id='$team_id' and id!=" . $_SESSION['loggedInUser'];
    $result = mysqli_query($con, $query);
    if (mysqli_num_rows($result) > 0) {
        while ($rows = mysqli_fetch_assoc($result)) {
            $student2 = $rows['f_name'] . ' ' . $rows['l_name'];
        }
    }


    ?>
    <div class="row">
        <div class="col-lg-6 text-light">
            <h3 class="main-Color">Team - Id : <span class="text-light white-color"><?php echo $team_id; ?></span></h3>
        </div>
        <div class="col-lg-6">
            <h3 class="main-Color">College : <span class="text-light white-color"><?php echo $college; ?></span></h3>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-lg-6 text-light">
            <h3 class="main-Color">Student - 1 : <span class="text-light white-color"><?php echo $student1; ?></span>
            </h3>
        </div>
        <div class="col-lg-6">
            <h3 class="main-Color">Student - 2 : <span class="text-light white-color"><?php echo $student2; ?></span>
            </h3>
        </div>
    </div>
</div>


<div class="container">
    <div class="row">
        <div class="col-lg-3 col-sm-4 bg-custom mt-3 d-none d-lg-block">
            <div class="row">
                <div class="col">
                    <hr class="bg-light">
                    <p class="text-center mt-3 text-light h5">Question Attempted</p>
                    <hr class="bg-light">
                </div>
            </div>
            <div class="row">
                <!--<div class="col-lg-2 col-sm-4">
                    <div class="black-color question">
                        <form>
                            <input type="hidden" name="" value="">
                            <input type="submit" name="" class="fullsize" value="1">
                        </form>
                    </div>
                </div>-->

                <?php
                for ($i = 1; $i <= totalquestion; $i++) {
                    $bgClass = "";
                    if (isset($_SESSION[attempetedArray . $i])) {
                        $bgClass = "attenpt";
                    }

                    echo '<div class="col-lg-3 col-sm-4 d-none d-lg-block">';
                    if ($bgClass == "") {
                        echo '<div class="white-color question" style="background: #1c7430">';
                    } else {
                        echo '<div class="black-color question" style="background: #62F1D3">';
                    }
                    echo '<form method="post" action="">';
                    echo '<input type="hidden" name="' . currentQuestion . '" value="' . ($i - 2) . '">';
                    echo '<input type="submit" name="" style="background:transparent;border :0px" class="fullsize" value="' . $i . '">';
                    echo "</form>";
                    echo "</div>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>
        <div class="col-lg-7 mt-3">
            <div class="card">
                <div class="card-header bg-custom">
                    <h2 class="bg-red"><?php echo ($question_Index + 1) . "." . $que['question']; ?></h2>
                </div>

                <div class="card-body bg-custom">
                    <form method="post" style="padding-left: 30px" id="form1">
                        <input type="hidden" name="<?php echo currentQuestion ?>" value="<?php echo $question_Index ?>">
                        <input id="option1" <?php if ($select_number == 1) {
                            echo "checked";
                        } ?> type="radio" name="option" value="a">
                        <label for="option1" class="white-color form-check-label"><?php echo $que['op_one'] ?></label>
                        <br>
                        <input id="option2" <?php if ($select_number == 2) {
                            echo "checked";
                        } ?> type="radio" name="option" value="b">
                        <label for="option2" class="white-color form-check-label"><?php echo $que['op_two'] ?></label>
                        <br>
                        <input id="option3" <?php if ($select_number == 3) {
                            echo "checked";
                        } ?> type="radio" name="option" value="c">
                        <label for="option3" class="white-color form-check-label"><?php echo $que['op_three'] ?></label>
                        <br>
                        <input id="option4" <?php if ($select_number == 4) {
                            echo "checked";
                        } ?> type="radio" name="option" value="d">
                        <label for="option4"
                               class="white-color form-check-label mr-5"><?php echo $que['op_four'] ?></label>
                        <br>
                        <div class="row my-2" style="margin-left: 30px">
                            <div class="col text-center">


                                <?php
                                if ($question_Index > 0) {
                                    echo '<input class="btn btn-primary"  type="submit" value="Prev" name="' . prev_qn . '">';
                                }
                                if ($question_Index == (totalquestion - 1)) {
                                    echo '<input id="form1" class="btn btn-primary" style="margin-left: 30px"  type="submit" value="Sumbit" name="' . summbit_question . '">';
                                } else {
                                    echo '<input class="btn btn-primary" style="margin-left: 30px" type="submit" value="Next" name="' . next . '">';
                                }
                                ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


        </div>
        <div class="col-lg-2 mt-3 d-none d-lg-block">
            <div class="row">
                <div class="col">
                    <hr class="bg-light">
                    <p class="text-center mt-3 text-light h5">Time Remaining</p>

                    <hr class="bg-light">
                    <p id="demo" class="lead text-center text-danger" style="font-size: 35px;"></p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="text-center bg-custom-light mt-4 p-3">
    <span>© 2019 <font color="#a42a3c">SPECTECH 7E3</font>. All rights reserved. | Developed  by <font color="#a42a3c">IT DEPARTMENT</font></span>
</div>
</body>
</html>

