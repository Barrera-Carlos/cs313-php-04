<?php
/**
 * Created by PhpStorm.
 * User: Carlos
 * Date: 2/24/2018
 * Time: 11:07 AM
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <title>Title</title>
    <style>
        .container{
            margin-top: 20%;
        }
    </style>
    <script>
        function changeSubmit(id) {
            if (id === 1){
                document.getElementById('form').action = "phpQuerying.php";
                document.getElementById('submit').click();
            }
            else if(id === 2){
                document.getElementById('form').action = "signIn.php";
                document.getElementById('submit').click();
            }
        }

    </script>
</head>
<body>
<div class="container">
    <form action="phpQuerying.php" method="post" id="form">
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">Username <br><input type="text" name="username"><br></div>
            <div class="col-sm-4"></div>

        </div>
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">Password <br><input type="password" name="psw"><br></div>
            <div class="col-sm-4"></div>
        </div>
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4"><button onclick="changeSubmit(1)">Login</button><button onclick="changeSubmit(2)">Sign up</button></div>
            <div class="col-sm-4"></div>
        </div>
        <input type="submit" style="display: none" id="submit">
    </form>
</div>
</body>
</html>
