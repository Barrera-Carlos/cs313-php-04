<?php
/**
 * Created by PhpStorm.
 * User: Carlos
 * Date: 2/24/2018
 * Time: 11:37 AM
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

    $psw = password_hash($_POST['psw'], PASSWORD_DEFAULT);
    $subString = "INSERT INTO public.user (username,password,display_name) VALUES('".$_POST['username']."','".$psw."','".$_POST['dName']."')";
    if(!empty($psw) or $psw != false)
        $db->query($subString);