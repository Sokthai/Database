<?php
/**
 * Created by PhpStorm.
 * User: Allen
 * Date: 3/15/2019
 * Time: 12:59 AM
 */

require("../model/database.php");
$db = new database("root", "");
$db->connection();


    header("location:../view/parentSchedule.php?info=" . base64_encode(serialize($db->getUserSection($_GET["moid"]))));
