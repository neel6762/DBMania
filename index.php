<?php

    require_once "dbConnection.php";

    session_start();
    global $loginError;
    if(isset($_POST["submit"])){
        $formid = $_POST['team_id'];
        $formpass = $_POST['team_pass'];

        // Already registered user

        $test_query = "SELECT * FROM test";
        $test_result = mysqli_query($con,$test_query);

        if(mysqli_num_rows($test_result) > 0){
            while($rows = mysqli_fetch_assoc($test_result)){
                $reg_user = $rows['login_id'];
            }
        }
         if($formid == $reg_user){
            $loginError =  "<div class='alert alert-danger mt-2' role='alert'>Already Registered !</div>";
        }
        else{

        // Normal Login Section

        $query = "SELECT * from it WHERE id='$formid'";
        $result = mysqli_query($con, $query);

        if(mysqli_num_rows($result) > 0){

            while($row = mysqli_fetch_assoc($result)){
                $id = $row['id'];
                $pass = "dbmania";
            }
            if($formid == 'admin' && $pass == 'admin'){
                $_SESSION['loggedInUser'] = $id;
                header('Location:admin.php');
            }
            else if($formpass == $pass){
                $_SESSION['loggedInUser'] = $id;
                $query = "SELECT * FROM it WHERE id=" . $_SESSION['loggedInUser'];
                $result = mysqli_query($con,$query);

                if(mysqli_num_rows($result) > 0){
                    while($rows = mysqli_fetch_assoc($result)){
                        $team_id = $rows['team_id'];
                        $college = $rows['college_name'];
                        $student1 = $rows['f_name'] .' '.$rows['l_name'];
                        $_SESSION['team_id'] = $team_id;
                        $_SESSION['college'] = $college;
                    }
                }
                $login_id = $_SESSION['loggedInUser'];
                $query = "INSERT INTO test(team_id,login_id,college) VALUES('$team_id','$login_id','$college')";
                $result = mysqli_query($con, $query);
                header('Location:questions.php');
            }else{
                // error message
                $loginError = "<div class='alert alert-danger mt-2' role='alert'>
                Wrong Password!
                </div>";
            }

        }

        else{ // no results
                $loginError = "<div class='alert alert-danger mt-2' role='alert'>
                Please Try Again !
            </div>";
            }
        }
        mysqli_close($con);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="src/css/bootstrap.css">

    <style>
        body{
            width: 100%;
            height:100%;
            margin: 0px;
            background-image: url("src/images/bg-landscape.jpg");
            background-repeat: no-repeat;
            background-size: cover;
            font-family: "montserrat";
        }

        .container {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login_section{
            margin-top: 45px;
        }
        .custom-color{
            color: #042536;
        }
        .custom-button{
            color: #042433;
            background-color:#62F1D3;
            border-color:#62F1D3;
        }
        .custom-button:hover{
            color: #042433;
            background-color: #62F1D3;
            border-color: #62F1D3;

        }
        .spce{
            margin-right: 20px;
        }
        .spectech{
            margin-left: 20px;
        }
    </style>
</head>
<body>
    <div class="container login_section  rounded">
        <form action="index.php" method="post">
            <div class="row text-center">
                <div class="col">
                    <img src="src/images/SPCE_logo.png" height="150px" width="150px">
                </div>
                <div class="col">
                    <img src="src/images/SPECTECH_logo.png" height="150px" width="200px">
                </div>
            </div>
            <div class="row my-2">
                <div class="col custom-color">
                    <h2 class="text-center text-light h3"><b>Sardar Patel College Of Engineering</b></h2>
                    <p class="text-center text-light lead"> Information Technology Department</p>
                </div>
            </div>

            <div class="row my-1">
                <div class="col">
                    <input type="text" class="form-control my-2" placeholder="Registration - Id" name="team_id">
                </div>
            </div>

            <div class="row my-1">
                <div class="col">
                    <input type="password" class="form-control my-2" placeholder="Password" name="team_pass">
                </div>
            </div>

            <div class="row my-1">
                <div class="col">

                </div>
            </div>

            <div class="row my-1">
                <div class="col">
                    <button class="btn custom-button btn-block my-2" name = "submit">L o g i n</button>
                </div>
            </div>

            <?php echo $loginError; ?>
        </form>
    </div>

</body>
</html>
