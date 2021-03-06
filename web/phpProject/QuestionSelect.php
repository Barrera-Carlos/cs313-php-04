<?php
/**
 * Created by PhpStorm.
 * User: Carlos
 * Date: 2/9/2018
 * Time: 4:08 PM
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
        .container{
            margin-top: 20%;
        }
        input[type=submit]{
            border-radius: 12%;
            width: 100%;
            color: black;
        }

        .col-sm-12{
            padding-bottom: 20px;
        }
    </style>
    <script>
        var deleteBundle = false;
        function changeSubmit() {
            if(!deleteBundle){
                document.getElementById("bundleSubmit").action = "QuestionSelect.php"
            }
            else {
                document.getElementById("bundleSubmit").action = "quiz.php"
            }
        }
    </script>
</head>
<body>
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Flash Quiz</a>
        </div>
        <ul class="nav navbar-nav">
            <li><a href="phpQuerying.php">Subjects</a></li>
        </ul>
    </div>
</nav>
<?php

    echo "<button onclick='changeSubmit()'>Delete Bundle</button>";
    if(isset($_POST['bundle']) and  !empty($_POST['bundle'])){
        $bundleArray = array();
        $questionArray = array();
        $answerArray = array();
        if(isset( $_SESSION['subjectId']) and !empty( $_SESSION['subjectId'])){
            $bundleIDScript = "SELECT id FROM public.bundle_name WHERE bundle_name ='".$_POST['bundle']."'";
            foreach ($db->query($bundleIDScript) as $bId){
                $subjectIdScript = "SELECT bundle_id FROM public.subject_bundles WHERE subject_id='".$_SESSION['subjectId']."'";
                foreach ($db->query($subjectIdScript) as $userId) {
                    if($userId['bundle_id'] === $bId['id']){
                        $questionSelectScript = "SELECT question_id FROM public.bundle_questions WHERE bundle_id=".$bId['id'];
                        foreach ($db->query($questionSelectScript) as $value){
                            $answerSelectScript = "SELECT answer_id FROM public.question_answers WHERE question_id=".$value['question_id'];
                            foreach ($db->query($answerSelectScript) as $answerID){
                                array_push($answerArray,$answerID['answer_id']);
                            }
                            array_push($questionArray,$value['question_id']);
                        }
                        array_push($bundleArray,$bId['id']);
                    }
                }
            }

            if(!empty($answerArray)){
                foreach ($answerArray as $value){
                    $deleteQuestionAnswer = "DELETE FROM question_answers WHERE answer_id=".$value;
                    $deleteAnswer = "DELETE FROM answer WHERE id=".$value;
                    $db->query($deleteQuestionAnswer);
                    $db->query($deleteAnswer);
                }
            }
            if(!empty($questionArray)){
                foreach ($questionArray as $value){
                    $deleteBundleQuestion = "DELETE FROM bundle_questions WHERE question_id=".$value;
                    $deleteQuestion = "DELETE FROM questions WHERE id=".$value;
                    $db->query($deleteBundleQuestion);
                    $db->query($deleteQuestion);
                }
            }
            if(!empty($bundleArray)){
                foreach ($bundleArray as $value){
                    $deleteSubjectBundle = "DELETE FROM subject_bundles WHERE bundle_id=".$value;
                    $deleteBundle = "DELETE FROM bundle_name WHERE id=".$value;
                    $db->query($deleteSubjectBundle);
                    $db->query($deleteBundle);
                }
            }
        }
    }

    $postInputStringLength = '';
    $postInputStringLength = (string)$_POST['inputQuestion'];
    if(!$postInputStringLength == ''){
        if(isset($_SESSION['subjectId']) and !empty($_SESSION['subjectId'])){
            $insertSqlBundle = "INSERT INTO public.bundle_name (bundle_name) VALUES ('".$postInputStringLength."')";
            if($db->query($insertSqlBundle) == true){
                (int)$newId = $db->lastInsertId('bundle_name_id_seq');
                (int)$subjectId = $_SESSION["subjectId"];
                $insertToUserSubjectName = "INSERT INTO public.subject_bundles (subject_id, bundle_id) VALUES(".$subjectId.",".$newId.")";
                $db->query($insertToUserSubjectName);


            }
        }

}

    echo "<div class=\"container\" id='inputContainer'>";
    echo "<form action=\"QuestionSelect.php\" method='post'>";
    echo "<div class=\"row\">";
    echo "<div class=\"col-sm-12\" id='inputRow'><input type='text' name='inputQuestion'><button onclick='submitItem()'>Add Subject</button></div>";
    echo "<input type='submit' style='display: none' id='submit'>";
    echo "</div>";
    echo "</form>";
    echo "</div>";

    echo "<div class=\"container\">";
    echo "<form action=\"quiz.php\" method='post' id='bundleSubmit'>";
    if(empty($_POST['subject']))
    {
        $_POST['subject'][0]=$_SESSION['subjectId'];
    }
                $select = "SELECT bundle_id FROM public.subject_bundles WHERE subject_id =".$_POST['subject'][0];
                $_SESSION['subjectId'] = $_POST['subject'][0];
                #$_SESSION['SubjectPostHolder'] = $_POST['subject'][0];
                foreach ($db->query($select) as $row){
                    $bundle = "SELECT bundle_name FROM public.bundle_name WHERE id =".$row[0];
                    foreach ($db->query($bundle) as $name){
                        echo "<div class=\"row\">";
                        #echo "<div class=\"col-sm-12\"><input type='checkbox' value='".$name[0]."' name='bundle'>$name[0]</div>";
                        echo "<div class=\"col-sm-12\"><input type='submit' value='".$name[0]."' name='bundle'></div>";
                        echo "</div>";
                    }
                }



    echo "</form>";
echo "</div>";
?>
</form>
</body>
</html>


