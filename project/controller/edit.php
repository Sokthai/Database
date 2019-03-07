<?php
/**
 * Created by PhpStorm.
 * User: Allen
 * Date: 3/7/2019
 * Time: 9:29 AM
 */

require("../model/database.php");

$db = new database("root", "");
$db->connection();

$user = $db->getAllUserInfo($_GET["id"]);

if ($user["role"] == "parent"){
    header("location:../view/editParent.php?info=" . base64_encode(serialize($user)));
}else{
    if (isset($_GET["student"])){ //if student edit there own profile
        header("location:../view/editStudent.php?student=1&&info=" . base64_encode(serialize($user)));
    }else{
        header("location:../view/editStudent.php?info=" . base64_encode(serialize($user)));
    }
}
//echo "<pre>";
//print_r($db->getAllUserInfo($id));
//echo "</pre>";
