<?php
/**
 * Created by PhpStorm.
 * User: Allen
 * Date: 2/24/2019
 * Time: 5:25 PM
 */
session_start();
require("../model/database.php");





if ($_SERVER['REQUEST_METHOD'] == "POST"){
    $email = $_POST["email"];
    $password = $_POST["password"];
    $pwRepeat = $_POST["pw-repeat"];
    $name = $_POST["name"];
    $phone = $_POST["phone"];
    $city = $_POST["city"];
    $state = $_POST["state"];
    $role = $_POST["role"];


    if (!$password == $pwRepeat){
        $_SESSION["error"] = "you password doesn't match";
        echo "are you here";
        //header("location:../view/registration.html");
    }

    $info = [
        "email" => $email, "password" => $password,
        "name" => $name, "phone" => $phone,
        "city" => $city, "state" => $state,
        "role" => $role
    ];



    $db = new database("root", "");
    $db->connection();
    if ($db->registration($info)){
        echo "new record is added successfully";
    }else{
        echo "cannot add new record now";
    }
}else{
    echo "sorry , wrong page";
}

