<?php
/**
 * Created by PhpStorm.
 * User: Allen
 * Date: 3/23/2019
 * Time: 6:38 PM
 */


session_start();
require("../model/database.php");
$db = new database("root", "");
$db->connection();


if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    if (isset($_POST["participate"])){
        if ($db->participate($_POST["participate"], $_POST["id"])){
            echo "participation is added";
        }else{
            echo "something went wrong, cannot add participation right now.";
        }

    }else{
        echo "Please select at lease one session to participate";
    }

}else {
    if (isset($_GET["moid"])) { //if parent want to see how many participation each session;

        header("location:../view/countParticipation.php?info=" . base64_encode(serialize($db->getParticipantAndMentor())));

    } else {
//        echo "he";
        header("location:../view/participate.php?id= " . $_GET["id"] . "&&info=" . base64_encode(serialize($db->getParticipation($_GET["id"]))));
    }
}

