<?php
/**
 * Created by PhpStorm.
 * User: Carlos
 * Date: 2/23/2018
 * Time: 10:39 PM
 */
    session_start();

    $dbURL = getenv('DATABASE_URL');
    $dbopts = parse_url($dbURL);


    try{
        $dbHost = $dbopts["host"];
        $dbPort = $dbopts["port"];
        $dbUser = $dbopts["user"];
        $dbPassword = $dbopts["pass"];
        $dbName = ltrim($dbopts["path"],'/');

        $db = new PDO("pgsql:host=$dbHost;port=$dbPort;dbname=$dbName", $dbUser, $dbPassword);
    }
    catch (PODException $ex){
        echo 'Error!: ' . $ex->getMessage();
        die();
    }
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
    </style>
</head>
<body>
    <?php

    $questionArray = array();
    $answerArray = array();

    $bundleId = $_SESSION['bundleId'];
    $questionSelectString = "SELECT question_id FROM bundle_questions WHERE bundle_id=".$bundleId;
    foreach ($db->query($questionSelectString) as $questionId){

        $answer = "SELECT answer FROM answer WHERE id =".$questionId['question_id'];
        $question = "SELECT question FROM questions WHERE id =".$questionId['question_id'];

        foreach ($db->query($question) as $item){
            array_push($questionArray, $item['question']);
        }
        foreach ($db->query($answer) as $item){
            array_push($answerArray,$item['answer']);
        }
    }

    $questionJSON = json_encode($question);
    $answerJSON = json_encode($answer);
    ?>
    <div id="questionDisplay"></div>

    <script>
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var myObj = JSON.parse(this.responseText);
                var arraySize = myObj.length;
                for (var i = 0; i < arraySize; i++){
                    document.getElementById("questionDisplay").innerHTML = myObj[i];
                }
            }
        };
        xmlhttp.open("GET", "flashCardqQuiz.php", true);
        xmlhttp.send();


    </script>
</body>
</html>