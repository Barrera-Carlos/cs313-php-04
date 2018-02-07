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

    echo "I also have a hard time spelling";
    foreach ($db->query('SELECT * FROM public.user') as $row){
        echo 'id:'. $row['id'];
        echo 'username:'. $row['username'];
        echo 'display name;'. $row['display_name'];
        echo '<br/>';
    }
?>

</body>
</html>