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



//$postedMaterial = $db->getAllSession($_GET["moid"]);
$allSession = $db->getAllSession();

if (count($postedMaterial) > 0){
    header("location:../view/session.php?moid= " . $_GET["moid"] . "&&maid=" . $_GET["materialId"] . "&&info=" . base64_encode(serialize($allSession)));
}else{
    echo "You do not have any posted material";
}