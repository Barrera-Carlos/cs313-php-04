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
    <script src="submitScript.js"></script>
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
</head>

<body>
<?php
    /*echo "<div class=\"container\" id='inputContainer'>";
    echo "<form action=\"QuestionSelect.php\" method='post'>";
    echo "<div class=\"row\">";
    echo "<div class=\"col-sm-12\" id='inputRow'><input type='text'><button style='text-align: center'>Add Subject</button></div>";
    echo "</div>";*/

    $username = $_POST["username"];
    $logInPsw = $_POST["psw"];
    $displayName = 'empty';

foreach ($db->query('SELECT * FROM public.user') as $row){
    if($row['username'] == $username and  $row['password'] == $logInPsw){
        $_SESSION["userId"] = $row['id'];
        $_SESSION["username"] = $row['username'];
        $_SESSION["displayname"] = $row['display_name'];
        $displayName = $row['display_name'];
    }
}

if($displayName == 'empty'){
    echo 'Your not a valid user';
}
else{
    echo "<div class=\"container\" id='displayContainer'>";
    echo "<form action=\"QuestionSelect.php\" method='post'>";
    echo "<div class=\"row\">";
    echo "<div class=\"col-sm-12\" id='inputRow'><input type='text' id='inputText'><button onclick='submitItem()'>Add Subject</button></div>";
    $postInputStringLength =  trim($_POST['input']);
    if(strlen($postInputStringLength) > 0){
        $insertSqlSubject = "INSERT INTO public.subject VALUES(".$_POST['input'].")";
        echo "<h1>".$_POST['input']."</h1>";
    }
    echo "</div>";
    $sqlSubjectId = "SELECT subject_id FROM public.user_subjects WHERE user_id =".$_SESSION["userId"];
    foreach ($db->query($sqlSubjectId) as $row) {
       $sqlSubject = "SELECT subject_name FROM public.subject WHERE id =".$row["subject_id"];
       foreach ($db->query($sqlSubject) as $column){
           echo "<div class=\"row\">";
           echo "<div class=\"col-sm-12\" id='displayRow'><input type='submit' value=".$column["subject_name"]." name='subject[]'></div>";
           #echo "<div class=\"col - sm - 12\" id='displayRow'><button onclick='selectName()'>".$column["subject_name"]."</button></div>";
           echo "</div>";
        }
    }
    echo "<input type='submit' style='display: none' name='input' id='submit'>";
    echo "</from>";
    echo "</div>";
}
?>

</body>
</html>