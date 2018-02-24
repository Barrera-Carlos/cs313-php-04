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
    <script>

    </script>
</head>
<body>
    <?php

    $questionArray = array();
    $answerArray = array();

    $bundleId = $_SESSION['bundleId'];
    $questionSelectString = "SELECT question_id FROM bundle_questions WHERE bundle_id=".$bundleId;
    foreach ($db->query($questionSelectString) as $questionId){

        $answer = "SELECT answer FROM answer WHERE id =".$questionId['question_id'];
        $question = "SELECT question FROM question WHERE id =".$questionId['question_id'];
        echo $answer;
        echo $question;
        foreach ($db->query($question) as $item){
            array_push($questionArray, $item['question']);

        }
        foreach ($db->query($answer) as $item){
            array_push($answerArray,$item['answer']);

        }
    }
        foreach ($questionArray as $item){

        }

    ?>
</body>
</html>