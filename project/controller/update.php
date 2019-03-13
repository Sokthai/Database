<?php
/**
 * Created by PhpStorm.
 * User: Allen
 * Date: 3/7/2019
 * Time: 11:48 AM
 */

session_start();
require("../model/database.php");

$db = new database("root", "");

$db->connection();
$mentee = $mentor = null;
$user = "parent";

if (isset($_POST["status"])){ //if student edit their own profile
    $user = "student";
}

if ($user == "student"){
    switch ($_POST["status"]){
        case 2:
            $mentee = $mentor = 1; break;
        case 1:
            $mentor = 1; break;
        default:
            $mentee = 1;
    };
    $info = [
        "name" => $_POST["name"],
        "email" => $_POST["email"],
        "phone" => $_POST["phone"],
        "city" => $_POST["city"],
        "state" => $_POST["state"],
        "id" => $_POST["id"],
        "mentee" => $mentee,
        "mentor" => $mentor
    ];
}else{
    $info = [
        "name" => $_POST["name"],
        "email" => $_POST["email"],
        "phone" => $_POST["phone"],
        "city" => $_POST["city"],
        "state" => $_POST["state"],
        "id" => $_POST["id"],
        "moderator" => isset($_POST["moderator"])? $_POST["moderator"] : null
    ];
}




if ($user == "student"){
    if (!$db->updateStudent($info)){
        echo "sorry, something when wrong in the database";
    }else{
        echo "updated student";
        echo "<pre>";
        print_r($info);
        echo "</pre>";
    }
}else{
    if (!$db->updateParent($info)){
        echo "sorry, something when wrong in the database";
    }else{
        echo "updated parent";
        echo "<pre>";
        print_r($info);
        echo "</pre>";
    }
}

