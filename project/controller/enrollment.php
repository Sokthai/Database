<?php
/**
 * Created by PhpStorm.
 * User: Allen
 * Date: 3/19/2019
 * Time: 6:14 AM
 */

require("../model/database.php");

$db = new database("root", "");
$db->connection();

//$course = $db->getAllSection();
//if (count($course) > 0){
//    header("location:../view/courses.php?info=" . base64_encode(serialize($course)));
//}else{
//    echo "<b>There is no course available at this time</b>";
//}

//if ($db->isStudent($_GET["sid"]) && $db->isMentee($_GET["sid"])){
//    $course = $db->getAllSection();
//    if (count($course) > 0){
//        $mentee = $db->getStudentGrade($_GET["sid"]);
//        header("location:../view/courses.php?mentee=" . base64_encode(serialize($mentee)) ."&&info=" . base64_encode(serialize($course)));
//    }else{
//        echo "<b>There is no course available at this time</b>";
//    }
//}


if (isset($_POST["mentorEnrollment"])){
    if ($db->sectionEnrollment("teach", $_POST["mentor"], $_POST["mentorEnrollment"])){
        echo "added";
    }else{
        echo "cannot add";
    }
}elseif (isset($_POST["menteeEnrollment"])){
    if ($db->sectionEnrollment("enroll", $_POST["mentee"], $_POST["menteeEnrollment"])){
        echo "added";
    }else{
        echo "cannot add";
    }
}else{
    echo "Please select section you want to enroll";
}