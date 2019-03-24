<?php
/**
 * Created by PhpStorm.
 * User: Allen
 * Date: 3/23/2019
 * Time: 2:02 PM
 */

session_start();
require("../model/database.php");
$db = new database("root", "");
$db->connection();


$takenClass = $db->getAllTakenClass($_GET["id"]);

//echo "<pre>";
//print_r($takenClass);
//echo "</pre>";
header("location:../view/takenClass.php?&&info=" . base64_encode(serialize($takenClass)));

