<?php
/**
 * Created by PhpStorm.
 * User: Allen
 * Date: 3/18/2019
 * Time: 10:59 AM
 */

session_start();
require("../model/database.php");
$db = new database("root", "");
$db->connection();

//echo "<pre>";
//print_r( $db->getAssignedSession($_GET["moid"]));
//echo "</pre>";


$assignedSession = $db->getAssignedSession($_GET["moid"]);
if (count($assignedSession) > 0){
   header("location:../view/viewAssignSession.php?info=" . base64_encode(serialize($assignedSession)));
}else{
    echo "<b>You do not have any assigned session</b>";
}

