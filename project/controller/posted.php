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



$postedMaterial = $db->getUserPostedMaterial($_GET["moid"]);
if (count($postedMaterial) > 0){
    header("location:../view/postedMaterial.php?info=" . base64_encode(serialize($postedMaterial)));
}else{
    echo "You do not have any posted material";
}