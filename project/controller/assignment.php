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



//$allSession = $db->getAllSession();
//
//if (count($allSession) > 0){
//    header("location:../view/session.php?moid= " . $_GET["moid"] . "&&maid=" . $_GET["materialId"] . "&&info=" . base64_encode(serialize($allSession)));
//}else{
//    echo "You do not have any posted material";
//}



$postedMaterial = $db->getUserPostedMaterial($_GET["moid"]);
if (count($postedMaterial) > 0){
//    header("location:../view/assign.php?info=" . base64_encode(serialize($postedMaterial)));
    header("location:../view/assign.php?moid= " . $_GET["moid"] . "&&maid=" . $_GET["materialId"] . "&&info=" . base64_encode(serialize($postedMaterial)));
}else{
    echo "<b>You do not have any posted material</b>";
}


// https://portal.adtpulse.com/myhome/13.0.0-153/summary/summary.jsp

