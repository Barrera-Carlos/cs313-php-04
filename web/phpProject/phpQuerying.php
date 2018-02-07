<?php
/**
 * Created by PhpStorm.
 * User: Carlos
 * Date: 2/7/2018
 * Time: 2:59 PM
 */

?>

<!DOCTYPE html>
<html>
<head>
	<title>Scripture List</title>
</head>

<body>
<?php

    $dbURL = getenv('DATABASE_URL');
    $dbopts = parse_url($dbURL);


    $dbHost = $dbopts["host"];
    $dbPort = $dbopts["port"];
    $dbUser = $dbopts["user"];
    $dbPassword = $dbopts["pass"];
    $dbName = ltrim($dbopts["path"],'/');

    $db = new PDO("pgsql:host=$dbHost;port=$dbPort;dbname=$dbName", $dbUser, $dbPassword);

    echo 'im in pain, because my existence is meaningless. I also have a hard time spelling';
    /*foreach ($db->query('SELECT * FROM question_answers') as $row){
        echo 'question id:'. $row['question_id'];
        echo 'answer id;'. $row['answer_id'];
        echo '<br/>';
    }*/
?>

</body>
</html>