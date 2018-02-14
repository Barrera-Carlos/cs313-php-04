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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <title>Title</title>
    <style>
        .container{
            margin-top: 20%;
        }
    </style>
</head>
<body>
<?php
    echo "<div class=\"container\">";
    echo "<form action=\"quiz.php\" method='post'>";
    foreach ($_POST['subject'] as $subject){
        foreach ($db->query('SELECT * FROM public.subject') as $column){
            if($subject == $column['subject_name']){
                $select = "SELECT bundle_id FROM public.subject_bundles WHERE subject_id =".$column['id'] ;
                foreach ($db->query($select) as $row){
                    $bundle = "SELECT bundle_name FROM public.bundle_name WHERE id =".$row[0];
                    foreach ($db->query($bundle) as $name){
                        echo "<div class=\"row\">";
                        echo "<div class=\"col-sm-4\"></div>";
                        echo "<div class=\"col-sm-4\"><input type='submit' value='".$name[0]."'><br/></div>";
                        echo "<div class=\"col-md-4\"></div>";
                        echo "</div>";
                    }
                }
            }
        }
    }
    echo "</form>";
echo "</div>";
?>
</form>
</body>
</html>


