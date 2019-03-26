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

if (isset($_GET["viewMentor"])){
    echo "<pre>";
    echo $_GET["viewMentor"];
    print_r($db->getMentor($_GET["viewMentor"]));
    echo "</pre>";
}else{
    if (isset($_GET["secId"])){ //if student want to view all assign material
        $mentoring = $db->getMentor($_GET["secId"]);
        header("location:../view/mentoring.php?info=" . base64_encode(serialize($mentoring)));
    }

}


