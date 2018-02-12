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
    <title>Title</title>
</head>
<body>
<?php
    #echo "<form action=\"quiz.php\" method='post'>";
    foreach ($_POST['subject'] as $subject){
        foreach ($db->query('SELECT * FROM public.subject') as $column){
            if($subject == $column['subject_name']){
                # a merege should be added to recive all of the id
                $select = "SELECT bundle_id FROM public.subject_bundles WHERE subject_id =".$column['id'] ;
                foreach ($db->query($select) as $row){
                    $bundle = "SELECT bundle_name FROM public.bundle_name WHERE id =".$row[0];
                    $bundleName = $db->query($bundle);
                    echo $bundleName;
                }
            }
        }
    }
    #echo "</form>"
?>
</form>
</body>
</html>


