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
</head>
<body>
<div class="container">
    <form action="phpQuerying.php" method="post">
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
            <div class="col-sm-4"><input type="submit" value="Submit"></div>
            <div class="col-sm-4"></div>
        </div>
    </form>
</div>
</body>
</html>
