<?php
/**
 * Created by PhpStorm.
 * User: Allen
 * Date: 3/19/2019
 * Time: 8:24 AM
 */

require("../model/database.php");
$db = new database("root", "");
$db->connection();




if (isset($_GET["mentee"])){
    if ($db->isMentee($_GET["mentee"])) {
        $course = $db->getMenteeSection($_GET["mentee"]);
        $mentee = $db->getStudentGrade($_GET["mentee"]);
        header("location:../view/role.php?mentee=" . base64_encode(serialize($mentee)) . "&&info=" . base64_encode(serialize($course)));
    }else{
        echo "Sorry, you cannot view this page because you are not a mentee";
    }
}elseif (isset($_GET["mentor"])){
    if ($db->isMentor($_GET["mentor"])) {
        $course = $db->getMentorSection($_GET["mentor"]);
        $mentor = $db->getStudentGrade($_GET["mentor"]);
        header("location:../view/role.php?mentor=" . base64_encode(serialize($mentor)) . "&&info=" . base64_encode(serialize($course)));
    }else{
        echo "Sorry, you cannot view this page because you are not a mentor";

    }

}elseif (isset($_GET["enrollment"])){
    $menteeCourse = ["mentee" => $db->getMenteeSection($_GET["enrollment"])];
    $mentorCourse = ["mentor" => $db->getMentorSection($_GET["enrollment"])];

    if (!($db->isMentor($_GET["enrollment"])) && !($db->isMentee($_GET["enrollment"]))) {
        echo "Sorry, you cannot view this page since you are neither mentor nor mentee";
    }else{
        $enrollment = $db->getStudentGrade($_GET["enrollment"]);
        header("location:../view/role.php?enrollment=" . base64_encode(serialize($enrollment)) ."&&info=" . base64_encode(serialize(array_merge($menteeCourse, $mentorCourse))));
    }

//    echo "<pre>";
//    print_r(array_merge($menteeCourse, $mentorCourse));
//    echo "</pre>";
}



//echo "<pre>";
//    print_r($db->getMenteeSection($_GET["mentee"]));
//echo "</pre>";