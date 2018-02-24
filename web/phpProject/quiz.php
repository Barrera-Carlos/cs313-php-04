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
    if((isset($_POST['inputAnswer']) and !empty($_POST['inputAnswer'])) or (isset($_POST['inputQuestion']) and !empty($_POST['inputQuestion']))){
        if (isset($_POST['question']) or isset($_POST['answer'])){
            if (isset($_POST['question']) and (isset($_POST['inputQuestion']) and !empty($_POST['inputQuestion']))){
                echo "a question wants to be updated";
            }
            if (isset($_POST['answer']) and (isset($_POST['inputAnswer']) and !empty($_POST['inputAnswer']))){
                echo "a answer wants to be updated";
            }
        }
        elseif ((isset($_POST['inputAnswer']) and !empty($_POST['inputAnswer']))and (isset($_POST['inputQuestion']) and !empty($_POST['inputQuestion']))){


            $question = $_POST['inputQuestion'];
            $answer = $_POST['inputAnswer'];

            $questionInsertString = "INSERT INTO questions (question) VALUES ('".$question."')";
            $answerInsertString = "INSERT INTO answer (answer) VALUES ('".$answer."')";

            $db->query($questionInsertString);
            $db->query($answerInsertString);
            $questionId = $db->lastInsertId('questions_id_seq');
            $answerId = $db->lastInsertId('answer_id_seq');

            $insertQuestionId = "INSERT INTO bundle_questions(bundle_id,question_id) VALUES (".$_SESSION['bundleId'].','.$questionId.")";
            $insertAnswerId = "INSERT INTO question_answers(question_id,answer_id) VALUE (".$questionId.",".$answerId.")";
            $db->query($insertQuestionId);
            $db->query($insertAnswerId);
        }

    }
    else{
        if(isset($_POST['question']) or isset($_POST['answer'])){
            $questionIdArray = array();
            $answerIdArray = array();
            if(isset($_POST['question'])){
                $c= count($_POST['question']);
                for ($x=0; $x < $c; $x++){
                    $questionIdString = "SELECT id FROM questions WHERE question='".$_POST['question'][$x]."'";
                    foreach ($db->query($questionIdString) as $item){
                        array_push($questionIdArray,$item['id']);
                        $delete1 = "DELETE FROM bundle_questions WHERE question_id=".$item['id'];
                        $delete2 = "DELETE FROM question_answers WHERE question_id=".$item['id'];
                        $delete3 = "DELETE FROM questions WHERE id=".$item['id'];
                        $delete4 = "DELETE FROM answer WHERE id=".$item['id'];
                        $db->query($delete1);
                        $db->query($delete2);
                        $db->query($delete3);
                        $db->query($delete4);

                    }
                }
            }
            if(isset($_POST['answer'])){
                $c= count($_POST['answer']);
                for ($x=0; $x < $c; $x++){
                    $answerIdString = "SELECT id FROM answer WHERE answer='".$_POST['answer'][$x]."'";
                    foreach ($db->query($answerIdString) as $item){
                        array_push($answerIdArray,$item['id']);
                        $delete1 = "DELETE FROM bundle_questions WHERE question_id=".$item['id'];
                        $delete2 = "DELETE FROM question_answers WHERE question_id=".$item['id'];
                        $delete3 = "DELETE FROM questions WHERE id=".$item['id'];
                        $delete4 = "DELETE FROM answer WHERE id=".$item['id'];
                        $db->query($delete1);
                        $db->query($delete2);
                        $db->query($delete3);
                        $db->query($delete4);
                    }
                }
            }
        }
    }


    echo "<div class=\"container\" id='inputContainer'>";
    echo "<form action=\"quiz.php\" method='post'>";
    echo "<div class=\"row\">";
    echo "<div class=\"col-sm-12\" id='inputRow'><input type='text' name='inputQuestion'>";
    echo "<input type='text' name='inputAnswer'>";
    echo "<button onclick='clickSubmit()'>Mod You Questions</button></div>";
    echo "</div>";


    if(!isset($_POST['bundle']) or empty($_POST['bundle'])){
        $_POST['bundle'] = $_SESSION['bundleName'];
    }
    if(isset($_POST['bundle']) and !empty($_POST['bundle'])){
        $_SESSION['bundleName'] = $_POST['bundle'];
        $bundleSearchString = "SELECT id FROM bundle_name WHERE bundle_name='".$_POST['bundle']."'";
        foreach ($db->query($bundleSearchString) as $bundleId){
            $_SESSION['bundleId'] = $bundleId['id'];
            $questionSelectString = "SELECT question_id FROM bundle_questions WHERE bundle_id=".$bundleId['id'];
            foreach ($db->query($questionSelectString) as $questionId){
                $questionString = "SELECT * FROM questions WHERE id =".$questionId['question_id'];
                $answerString = "SELECT * FROM answer WHERE id =".$questionId['question_id'];
                foreach ($db->query($questionString) as $question)
                    foreach ($db->query($answerString) as $answer){
                        $questionValue = $question['id'];
                        $answerValue = $answer['id'];
                        echo "<div class='row'>";
                        echo "<div class=\"col-sm-6\"><input type='checkbox' value='".$questionValue."' name='question[]'>".$question['question']."</div>";
                        echo "<div class=\"col-sm-6\"><input type='checkbox' value='".$answerValue."' name='answer[]'>".$answer['answer']."</div>";
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
