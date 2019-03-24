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


if (isset($_GET["id"])){ //if student want to view all assign material
    $assignedMaterial = $db->getAllAssignMaterial($_GET["id"]);
    header("location:../view/assignMaterial.php?info=" . base64_encode(serialize($assignedMaterial)));
}else { //if parent want to view all the material session they assigned
    $assignedSession = $db->getAssignedSession($_GET["moid"]);
    if (count($assignedSession) > 0) {
        header("location:../view/viewAssignSession.php?info=" . base64_encode(serialize($assignedSession)));
    } else {
        echo "<b>You do not have any assigned session</b>";
    }
}

