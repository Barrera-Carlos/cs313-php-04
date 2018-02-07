<?php
/**
 * Created by PhpStorm.
 * User: Carlos
 * Date: 2/7/2018
 * Time: 2:59 PM
 */
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
    foreach ($db->query('SELECT * FROM question_answers') as $row){
        echo 'question id:'. $row['question_id'];
        echo 'answer id;'. $row['answer_id'];
        echo '<br/>';
    }
?>

</body>
</html>