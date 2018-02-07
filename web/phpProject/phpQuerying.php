<?php
/**
 * Created by PhpStorm.
 * User: Carlos
 * Date: 2/7/2018
 * Time: 2:59 PM
 */
$dbURL = getenv('DATABASE_URL');
$dbopts = parse_url($dbURL);

$dbHost = $dbopts["host"];
$dbPort = $dbopts["port"];
$dbUser = $dbopts["user"];
$dbPassword = $dbopts["pass"];
$dbName = ltrim($dbopts["path"],'/');

$db = new PDO("pgsql:host=$dbHost;port=$dbPort;dbname=$dbName", $dbUser, $dbPassword);
?>

<!DOCTYPE html>
<html>
<head>
	<title>Scripture List</title>
</head>

<body>
</body>
</html>