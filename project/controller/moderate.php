<?php
/**
 * Created by PhpStorm.
 * User: Allen
 * Date: 3/14/2019
 * Time: 9:26 AM
 */
require("../model/database.php");
$db = new database("root", "");
$db->connection();


if (isset($_GET["moid"])){
    header("location:../view/moderating.php?moid=" .$_GET["moid"] . "&&info=" . base64_encode(serialize($db->getAllSection())));
}else{
    if (isset($_POST["secId"])){
        $db->setSection($_POST["moderator"], $_POST["secId"]);
        //header("location:../view/moderating.php?info=" . base64_encode(serialize($db->setSection())));

    }else{
        echo "Please select section you want to moderate";
        header("location:../view/moderating.php?moid=" .$_GET["moid"] . "&&info=" . base64_encode(serialize($db->getAllSection())));

    }

}
