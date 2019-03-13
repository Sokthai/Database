<?php
/**
 * Created by PhpStorm.
 * User: Allen
 * Date: 3/12/2019
 * Time: 12:28 PM
 */
session_start();
require("../model/database.php");
$db = new database("root", "");
$db->connection();

$info = [
    "title" => $_POST["title"],
    "author" => $_POST["author"],
    "type" => $_POST["type"],
    "url" => $_POST["url"],
    "assignDate" => $_POST["assignDate"],
    "note" => $_POST["note"],
    "moderatorId" => $_POST["moderatorId"]
];


if ($db->postMaterial($info)){
    echo "Material is posted";
}else{
    echo "sorry, something wrong, cannot post the material";
}