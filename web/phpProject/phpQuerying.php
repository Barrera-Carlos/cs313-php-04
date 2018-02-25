<?php
/**
 * Created by PhpStorm.
 * User: Carlos
 * Date: 2/7/2018
 * Time: 2:59 PM
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
<html>
<head>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

	<title>Scripture List</title>

    <style>
        input[type=submit]{
            width: 100%;
            background-color: #737d8c;
            border: none;
            color: snow;
            float: left;
            transition-duration: 0.4s;
        }
        input[type=submit]:hover{
            background-color: #003d99;
        }
        #inputRow{
            padding-top: 100px;
            background-color: deepskyblue;
        }
        input[type=text]{
            border: none;
            width: 75%;
            padding: 10px;
            float: left;
            font-size: 16px;
        }
        button{
            padding: 8px;
            width: 25%;
            background: gray;
            color: snow;
            float: left;
            text-align: center;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
    <script>
        function submitItem() {
            var input = document.getElementById('inputText').value;
            if(input.length > 0){
                document.getElementById("submit").click();
            }
        }
        function changeSubmit() {
            var itemCheck = false;
            var form = document.getElementById('form');

            for (var i = 0; i < form.length; i++){
                alert(form.element[0].value);
            }
            if (itemCheck)
                form.action = "phpQuerying.php";

        }
        function chooseItem(){
            var itemCheck = false;
            var form = document.getElementById('form');
            alert(form.length)
            /*for (var i = 0; i < form.length; i++){
                if(form.element[i].checked){
                    itemCheck = true;
                }
            }
            if (itemCheck){
                form.action = "QuestionSelect.php";
                form.getElementById("submit").click();
            }*/

        }
    </script>
</head>

<body>
<?php

    $username = $_POST["username"];
    $logInPsw = $_POST["psw"];
    $displayName = '';
    $sameUser = false;

if(array_key_exists('userId',$_SESSION) && !empty($_SESSION['userId'])){
    $sameUser = true;
    $displayName = $_SESSION["displayname"];
    /*if($username === $_SESSION['username'] && $logInPsw === $_SESSION['psw'])
    {
        $sameUser = true;
        $displayName = $_SESSION["displayname"];
    }*/
}

if(!$sameUser){
    foreach ($db->query('SELECT * FROM public.user') as $row){
        if($row['username'] == $username and  $row['password'] == $logInPsw){
            $_SESSION["userId"] = $row['id'];
            $_SESSION['psw'] = $row['password'];
            $_SESSION["username"] = $row['username'];
            $_SESSION["displayname"] = $row['display_name'];
            $displayName = $row['display_name'];
        }
    }
}

if($displayName == ''){
    echo 'Your not a valid user';
}
else{

    echo "<div class=\"container\" id='buttonContainer'>";
    echo "<div class=\"row\">";
    echo "<div class=\"col-sm-12\" id='inputRow'><button onclick='changeSubmit()'>Delete subject</button><button onclick='chooseItem()'>Choose Subject</button></div>";
    echo "<input type='submit' style='display: none' id='submit'>";
    echo "</div>";
    echo "</div>";

    if(array_key_exists("subject",$_POST) && !empty($_POST["subject"][0])) {

        $subjectIdArray = array();
        $bundleIdArray = array();
        $questionIdArray = array();
        $answerIdArray = array();

            array_push($subjectIdArray,$_POST["subject"][0]);
            $bundleId = "SELECT bundle_id FROM public.subject_bundles WHERE subject_id=".$_POST["subject"][0];
            if($db->query($bundleId) == true){
                foreach ($db->query($bundleId) as $removeItem){
                    $removeItemScript = "SELECT question_id FROM public.bundle_questions WHERE bundle_id =".$removeItem["bundle_id"];
                    foreach ($db->query($removeItemScript) as $removeQuestion){
                        $removeAnswerScript = "SELECT answer_id FROM public.question_answers WHERE answer_id =".$removeQuestion['question_id'];
                        foreach ($db->query($removeAnswerScript) as $removeAnswer){
                            array_push($answerIdArray,(int)$removeAnswer["answer_id"]);
                        }
                        array_push($questionIdArray,(int)$removeQuestion['question_id']);
                    }
                    array_push($bundleIdArray,(int)$removeItem["bundle_id"]);
                }
            }



        if(empty($answerIdArray)){
            echo"<h1>No answers to delete</h1>";
        }else{
            foreach ($answerIdArray as $value){
                $deleteQuestionAnswers = "DELETE FROM public.question_answers WHERE answer_id=".$value;
                $deleteAnswers = "DELETE FROM public.answer WHERE id=".$value;
                $db->query($deleteQuestionAnswers);
                $db->query($deleteAnswers);
                #echo $deleteQuestionAnswers;
            }

        }
        if(empty($questionIdArray)){
            echo"<h1>No questions to delete</h1>";
        }else{
            foreach ($questionIdArray as $value){
                $deleteBundleQuestions = "DELETE FROM public.bundle_questions WHERE question_id=".$value;
                $deleteQuestions = "DELETE FROM public.questions WHERE id=".$value;
                $db->query($deleteBundleQuestions);
                $db->query($deleteQuestions);
                #echo $deleteBundleQuestions;
            }
        }
        if(empty($bundleIdArray)){
            echo"<h1>No question bundles to delete</h1>";
        }else{
            foreach ($bundleIdArray as $value){
                $deleteSubjectBundle = "DELETE FROM public.subject_bundles WHERE bundle_id =".$value;
                $deleteBundle = "DELETE FROM public.bundle_name WHERE id =".$value;
                $db->query($deleteSubjectBundle);
                $db->query($deleteBundle);
                #echo $deleteSubjectBundle;
            }
        }
        if(!empty($subjectIdArray)){
            $deleteUserSubject = "DELETE FROM public.user_subjects WHERE subject_id =".$subjectIdArray[0];
            $deleteSubject = "DELETE FROM public.subject WHERE id=".$subjectIdArray[0];
            $db->query($deleteUserSubject);
            $db->query($deleteSubject);
            #echo $deleteUserSubject."</br>";
            #echo $deleteSubject;
        }


    }


    $postInputStringLength = '';
    $postInputStringLength = (string)$_POST['input'];
    if(!$postInputStringLength == ''){
        $insertSqlSubject = "INSERT INTO public.subject (subject_name) VALUES ('".$postInputStringLength."')";
        if($db->query($insertSqlSubject) == true){
            (int)$newId = $db->lastInsertId('subject_id_seq');
            (int)$userId = $_SESSION["userId"];
            $insertToUserSubjectName = "INSERT INTO public.user_subjects (user_id, subject_id) VALUES(".$userId.",".$newId.")";
            $db->query($insertToUserSubjectName);
            echo $insertToUserSubjectName;

        }
    }

    echo "<div class=\"container\" id='inputContainer'>";
    echo "<form action=\"phpQuerying.php\" method='post'>";
    echo "<div class=\"row\">";
    echo "<div class=\"col-sm-12\" id='inputRow'><input type='text' name='input'><button onclick='submitItem()'>Add Subject</button></div>";
    echo "<input type='submit' style='display: none' id='submit'>";
    echo "</div>";
    echo "</form>";
    echo "</div>";

    echo "<div class=\"container\" id='displayContainer'>";
    echo "<form action='QuestionSelect.php' method='post' id='form'>";
    $sqlSubjectId = "SELECT subject_id FROM public.user_subjects WHERE user_id =".$_SESSION["userId"];
    foreach ($db->query($sqlSubjectId) as $row) {
       $sqlSubject = "SELECT * FROM public.subject WHERE id =".$row["subject_id"];
       foreach ($db->query($sqlSubject) as $column){
           echo "<div class=\"row\">";
           echo "<div class=\"col-sm-12\" id='displayRow'><input type='checkbox' value=".$column["id"]." name='subject[]'>".$column["subject_name"]."</div>";
           #echo "<div class=\"col-sm-12\" id='displayRow'><input type='submit' value=".$column["subject_name"]." name='subject[]'></div>";
           #echo "<div class=\"col - sm - 12\" id='displayRow'><button onclick=\"changeSubmit()\">".$column["subject_name"]."<button onclick='changeSubmit()'>hex</button></button></div>";
           echo "</div>";
        }
    }
    echo "<input type='submit' style='display: none' id='submitSubject'>";
    echo "</from>";
    echo "</div>";
}
?>

</body>
</html>