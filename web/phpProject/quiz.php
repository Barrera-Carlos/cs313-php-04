<?php
/**
 * Created by PhpStorm.
 * User: Carlos
 * Date: 2/11/2018
 * Time: 7:35 PM
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
        function clickSubmit() {
            document.getElementById('mainFormSubmit').click();
        }
    </script>
</head>
<body>
<?php
    echo "<div class=\"container\" id='inputContainer'>";
    echo "<form action=\"quiz.php\" method='post'>";
    echo "<div class=\"row\">";
    echo "<div class=\"col-sm-12\" id='inputRow'><input type='text' name='inputAnswer'>";
    echo "<input type='text' name='inputQuestion'>";
    echo "</div>";
    echo "</div>";
    echo "<div class=\"row\">";
    echo "<div class=\"col-sm-12\" id='inputRow'>";
    echo "<button onclick='clickSubmit()'>Submit</button>";
    echo "<button onclick='clickSubmit()'>Update question/answer</button>";
    echo "<button onclick='clickSubmit()'>Delete question and answer</button>";
    echo "</div>";
    echo "</div>";


    if((!isset($_POST['bundle']) or empty($_POST['bundle'])) and (isset($_SESSION['bundleId']) and !empty($_SESSION['bundleId']))){
        $_POST['bundle'] = $_SESSION['bundleName'];
    }
    if(isset($_POST['bundle']) and !empty($_POST['bundle'])){
        $_SESSION['bundleName'] = $_POST['bundle'];
        $bundleSearchString = "SELECT id FROM bundle_name WHERE bundle_name='".$_POST['bundle']."'";
        foreach ($db->query($bundleSearchString) as $bundleId){
            $questionSelectString = "SELECT question_id FROM bundle_questions WHERE bundle_id=".$bundleId['id'];
            foreach ($db->query($questionSelectString) as $questionId){
                $questionString = "SELECT question FROM questions WHERE id =".$questionId['question_id'];
                $answerString = "SELECT answer FROM answer WHERE id =".$questionId['question_id'];
                foreach ($db->query($questionString) as $question)
                    foreach ($db->query($answerString) as $answer){
                        #echo $question['question'].' '.$answer['answer']."</br>";
                        echo "<div class='row'>";
                        echo "<div class=\"col-sm-6\"><input type='checkbox' value='".$answer['answer']."' name='bundle'>".$question['question']."</div>";
                        echo "<div class=\"col-sm-6\"><input type='checkbox' value='".$question['question']."'>".$answer['answer']."</div>";
                        echo "</div>";
                    }
            }

        }
        echo "<input type='submit' style='display: none' id='mainFormSubmit'>";
        echo "</form>";
        echo "</div>";
    }

?>
</body>
</html>
