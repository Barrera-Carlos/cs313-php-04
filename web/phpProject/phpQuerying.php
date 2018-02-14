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
        .container{
            margin-top: 20%;
        }
        input[type=submit]{
            padding-bottom: 10px;
            border-radius: 12%;
            width: 100%;
        }
    </style>
</head>

<body>
<?php
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
    echo "<div class=\"container\">";
    echo "<form action=\"QuestionSelect.php\" method='post'>";
    $sqlSubjectId = "SELECT subject_id FROM public.user_subjects WHERE user_id =".$_SESSION["userId"];
    foreach ($db->query($sqlSubjectId) as $row) {
       $sqlSubject = "SELECT subject_name FROM public.subject WHERE id =".$row["subject_id"];
       foreach ($db->query($sqlSubject) as $column){
           echo "<div class=\"row\">";
           echo "<div class=\"col-sm-4\"></div>";
           echo "<div class=\"col-sm-4\"><input type='submit' value=".$column["subject_name"]." name='subject[]'></div>";
           echo "<div class=\"col-md-4\"></div>";
           echo "</div>";
        }
    }
    echo "</from>";
    echo "</div>";
}
?>

</body>
</html>