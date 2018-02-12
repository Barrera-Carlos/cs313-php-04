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
	<title>Scripture List</title>
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
    echo "<form action=\"QuestionSelect.php\" method='post'>";
    $sqlSubjectId = "SELECT subject_id FROM public.user_subjects WHERE user_id =".$_SESSION["userId"];
    foreach ($db->query($sqlSubjectId) as $row) {
       $sqlSubject = "SELECT subject_name FROM public.subject WHERE id =".$row["subject_id"];
       foreach ($db->query($sqlSubject) as $column){
            echo "<input type='submit' value=".$column["subject_name"]." name='subject[]'><br/>";
        }
    }
    echo "</from>";
}
?>

</body>
</html>