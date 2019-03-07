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
if (isset($_POST["status"])){ //if student edit their own profile
    switch ($_POST["status"]){
        case 2:
            $mentee = $mentor = 1; break;
        case 1:
            $mentor = 1; break;
        default:
            $mentee = 1;
    };
}


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