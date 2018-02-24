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

    $questionJSON = json_encode($questionArray);
    $answerJSON = json_encode($answerArray);
    ?>
    <div id="questionDisplay" onclick="clickIt()"></div>

    <script type="text/javascript">
        var questions = JSON.parse('<?php echo $questionJSON;?>');
        var answers = JSON.parse('<?php echo $answerJSON;?>');
        var displayArray = [];
        var length = questions.length;
        for (var i = 0 ; i < length; i++){
            displayArray.push(questions[i]);
            displayArray.push(answers[i]);
        }

        document.getElementById('questionDisplay').innerHTML = displayArray[0];

        var clickCount = 0;
        function clickIt() {
            document.getElementById('questionDisplay').innerHTML = displayArray[clickCount];
            if(clickCount < displayArray.length) {
                document.getElementById('questionDisplay').innerHTML = displayArray[clickCount];
                clickCount++;
            }
            else
                //send them back to the first page or reset quiz
        }
    </script>
</body>
</html>