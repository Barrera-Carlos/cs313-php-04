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
        #displayContainer{
            margin-top: 20%;
        }
        /*#displayRow{
            padding-bottom: 20px;
        }*/

        input[type=submit]{
            width: 100%;
            background-color: #737d8c;
            color: snow;
        }
        #inputRow{
           padding-top: 100px;
        }
        input[type=text]{
            width: 100%;
        }
        button{
            width: 50%;
            color: snow;
            background-color: deepskyblue;
        }




    </style>
</head>

<body>
<?php
    echo "<div class=\"container\">";
    echo "<form action=\"QuestionSelect.php\" method='post'>";
    echo "<div class=\"row\" id='inputRow'>";
    echo "<div class=\"col-sm-12\" ><input type='text'></div>";
    echo "</div>";
    echo "<div class=\"row\">";
    echo "<div class=\"col-sm-12\" ><button style='text-align: center'>Add Subject</button></div>";
    echo "</div>";
    echo "</form></div>";
    /*foreach ($db->query('SELECT * FROM public.user') as $row){
        echo 'id:'. $row['id'];
        echo 'username:'. $row['username'];
        echo 'display name;'. $row['display_name'];
        echo '<br/>';
    }*/

    $username = $_POST["username"];
    $logInPsw = $_POST["psw"];
    $displayName = 'empy';

foreach ($db->query('SELECT * FROM public.user') as $row){
    if($row['username'] == $username and  $row['password'] == $logInPsw){
        $_SESSION["userId"] = $row['id'];
        $_SESSION["username"] = $row['username'];
        $_SESSION["displayname"] = $row['display_name'];
        $displayName = $row['display_name'];
    }
}

if($displayName == 'empy'){
    echo 'Your not a valid user';
}
else{
    echo "<div class=\"container\" id='displayContainer'>";
    echo "<form action=\"QuestionSelect.php\" method='post'>";
    /*echo "<div class=\"row\">";
    echo "<div class=\"col-sm-12\"><input type='text'></div>";
    echo "</div>";*/
    $sqlSubjectId = "SELECT subject_id FROM public.user_subjects WHERE user_id =".$_SESSION["userId"];
    foreach ($db->query($sqlSubjectId) as $row) {
       $sqlSubject = "SELECT subject_name FROM public.subject WHERE id =".$row["subject_id"];
       foreach ($db->query($sqlSubject) as $column){
           echo "<div class=\"row\">";
           echo "<div class=\"col-sm-12\" id='displayRow'><input type='submit' value=".$column["subject_name"]." name='subject[]'></div>";
           echo "</div>";
        }
    }
    echo "</from>";
    echo "</div>";
}
?>

</body>
</html>