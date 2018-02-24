<?php
/**
 * Created by PhpStorm.
 * User: Carlos
 * Date: 2/24/2018
 * Time: 11:37 AM
 */

    $psw = password_hash($_POST['psw'], PASSWORD_DEFAULT);
    $subString = "INSERT INTO public.user (username,password,display_name) VALUES('".$_POST['username']."','".$psw."','".$_POST['dName']."')";
    echo $subString;