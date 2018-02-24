<?php
/**
 * Created by PhpStorm.
 * User: Carlos
 * Date: 2/24/2018
 * Time: 11:06 AM
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
    <script type="text/javascript">
        function checkData() {
           var usernameValue = document.getElementsByName('username')[0].value;
           var pswValue = document.getElementsByName('psw')[0].value;
           var dNameValue = document.getElementsByName('dName')[0].value;

           <?php
                $userName = array();
                $userNameSearch= "SELECT username FROM public.user";
                foreach ($db->query($userNameSearch) as $item){
                    array_push($userName, $item['username']);
                }

                $usernameJSON = json_encode($userName);
            ?>

            var username = JSON.parse('<?php echo $usernameJSON;?>')
            var nameExist = false;
            if(Array.isArray(username)){
                var x = username.length;
                for (var i = 0; i < x; i++){
                    if(username[i] === usernameValue)
                        nameExist = true;
                }
            }

            if (!nameExist ){
                //document.getElementById("myForm").submit();
            }
            else{
                if(nameExist)
                    alert('Username selected is take');
                else
                    alert('The form was not filled in')
            }

        }
    </script>
</head>
<body>
<div class="container">
    <form action="" method="post" id="myForm">
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">Username <br><input type="text" name="username"><br></div>
            <div class="col-sm-4"></div>

        </div>
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">Password <br><input type="password" name="psw"><br></div>
            <div class="col-sm-4"></div>
        </div>
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">DisplayName<br><input type="text" name="dName"><br></div>
            <div class="col-sm-4"></div>
        </div>
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4"><button onclick="checkData()">Create Profile</button></div>
            <div class="col-sm-4"></div>
        </div>
    </form>
</div>
</body>
</html>

