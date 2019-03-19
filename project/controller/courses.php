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



if (isset($_GET["mentee"])){
    if ($db->isStudent($_GET["mentee"]) && $db->isMentee($_GET["mentee"])){
        $course = $db->getAddableMenteeSection($_GET["mentee"]);
        if (count($course) > 0){
            $mentee = $db->getStudentGrade($_GET["mentee"]);
            header("location:../view/courses.php?mentee=" . base64_encode(serialize($mentee)) ."&&info=" . base64_encode(serialize($course)));
        }else{
            echo "<b>There is no course available at this time mentee</b>";
        }
    }else{
        echo "Sorry, only mentee can enroll this section";
    }
}elseif(isset($_GET["mentor"])){
    if (($db->isStudent($_GET["mentor"]) && $db->isMentor($_GET["mentor"]))) {
        $course = $db->getAddableMentorSection($_GET["mentor"]);
        if (count($course) > 0) {
            $mentor = $db->getStudentGrade($_GET["mentor"]);
            echo "in";
            header("location:../view/courses.php?mentor=" . base64_encode(serialize($mentor)) ."&&info=" . base64_encode(serialize($course)));
        } else {
            echo "<b>There is no course available at this time mentor</b>";
        }
    }else{
        echo "Sorry, only mentor can enroll this section";
    }
}else{
        $course = $db->getAllSection();
        if (count($course) > 0){
            header("location:../view/courses.php?info=" . base64_encode(serialize($course)));
        }else{
            echo "<b>There is no course available at this time all</b>";
        }
}



//if ($db->isStudent($_GET["mentee"]) && $db->isMentee($_GET["mentee"])){
//    if (count($course) > 0){
//        $mentee = $db->getStudentGrade($_GET["sid"]);
//        header("location:../view/courses.php?mentee=" . base64_encode(serialize($mentee)) ."&&info=" . base64_encode(serialize($course)));
//    }else{
//        echo "<b>There is no course available at this time</b>";
//    }
//}elseif (($db->isStudent($_GET["sid"]) && $db->isMentor($_GET["sid"]))) {
//    if (count($course) > 0){
//        $mentor = $db->getStudentGrade($_GET["sid"]);
//        header("location:../view/courses.php?mentor=" . base64_encode(serialize($mentor)) ."&&info=" . base64_encode(serialize($course)));
//    }else{
//        echo "<b>There is no course available at this time</b>";
//    }
//}else{
//    if (count($course) > 0){
//        header("location:../view/courses.php?info=" . base64_encode(serialize($course)));
//    }else{
//        echo "<b>There is no course available at this time</b>";
//    }
//}