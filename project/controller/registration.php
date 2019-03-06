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
    $db = new database("root", "");
    $db->connection();

    $email = $_POST["email"];
    $password = $_POST["password"];
    $pwRepeat = $_POST["pw-repeat"];
    $name = $_POST["name"];
    $phone = $_POST["phone"];
    $city = $_POST["city"];
    $state = $_POST["state"];
    $role = $_POST["role"];

    //echo "this is moderator "  . $moderator;


    if ($db->notExistEmail($email)){

        if ($password != $pwRepeat) {
            $_SESSION["error"] = "you password doesn't match";
            header("location:" . $_SERVER['HTTP_REFERER']);
        }else {
            if ($role == "parent"){
                $moderator = false;
                if (isset($_POST["moderator"])){
                    $moderator = $_POST["moderator"];
                }
                $info = [
                    "email" => $email, "password" => $password,
                    "name" => $name, "phone" => $phone,
                    "city" => $city, "state" => $state,
                    "role" => $role, "moderator" => $moderator
                ];
            }else{
                $status = $_POST["status"];
                $info = [
                    "email" => $email, "password" => $password,
                    "name" => $name, "phone" => $phone,
                    "city" => $city, "state" => $state,
                    "role" => $role, "status" => $status
                ];
            }


            if ($db->registration($info)) {
                echo "new record is added successfully";
                echo $_SERVER['HTTP_REFERER'];
            } else {
                echo "cannot add new record now";
                echo $_SERVER['HTTP_REFERER'];
            }
        }
    }else{
        echo "        ---->the email is already exist";
    }

}else{
    echo "sorry , wrong page";
}

