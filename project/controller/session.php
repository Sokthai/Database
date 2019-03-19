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



$allSession = $db->getAssignableSession(["moid" => $_GET["moid"], "maid" => $_GET["maid"]]);

if (count($allSession) > 0){
    header("location:../view/session.php?moid= " . $_GET["moid"] . "&&maid=" . $_GET["maid"] . "&&info=" . base64_encode(serialize($allSession)));
}else{
    echo "You do not have any posted material or this material has been assigned to all of your sessions";
}