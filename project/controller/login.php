<?php
/**
 * Created by PhpStorm.
 * User: Allen
 * Date: 2/25/2019
 * Time: 8:58 AM
 */
session_start();
require("../model/database.php");

$db = new database("root", "");
$email = $_POST["email"];
$password = $_POST["password"];
$db->connection();//connect to database
$info = ["email" => strtolower($_POST["email"]), "password" => $_POST["password"]];

if (($name = $db->login($info)) != false){
    header("location:../view/panel.php?name=" . $name);
}else{
    echo "Sorry, your email or password is not correct";
}
