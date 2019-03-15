<?php
/**
 * Created by PhpStorm.
 * User: Allen
 * Date: 3/15/2019
 * Time: 1:11 PM
 */

session_start();
require("../model/database.php");
$db = new database("root", "");
$db->connection();



$allSession = $db->getAllSession();

if (count($allSession) > 0){
    header("location:../view/session.php?moid= " . $_GET["moid"] . "&&maid=" . $_GET["materialId"] . "&&info=" . base64_encode(serialize($allSession)));
}else{
    echo "You do not have any posted material";
}