<?php
/**
 * Created by PhpStorm.
 * User: Allen
 * Date: 3/24/2019
 * Time: 1:42 AM
 */

function check(){
    require("../model/database.php");
    $db = new database("root", "");
    $db->connection();

    $participationCount = $db->getParticipantAndMentor();
//    echo "<pre>";
//        print_r($participationCount);
//    echo "</pre>";



    $menteeNotify = [];
    $moderatorNotify = [];
    foreach ($participationCount as $value) {
        if (date("l") == "Thursday" && $value["participantCount"] < 3){
//            echo "cp count " . $value["participantCount"];
//            echo "session id = " . $value["sesId"] . " ,Session= " . $value["sesName"] . " will be cancelled<br/>";
            $notify = $db->getNotification($value["sesId"]);
            if (count($notify) > 0) {
                array_push($menteeNotify, $db->getNotification($value["sesId"]));
            }
        }

        if (date("l") == "Thursday" && $value["mentorCount"] < 2){
//            $moderatorNotify = $db->getNotification($value["sesId"]);
//            echo "session id = " . $value["sesId"] . " ,Session= " . $value["sesName"] . " will be cancelled to moderato----------r<br/>";
            $notify = $db->getNotification($value["sesId"]);
            if (count($notify) > 0){
                array_push($moderatorNotify, $db->getNotification($value["sesId"]));
            }
        }
    }


    if (count($menteeNotify) > 0){
        writeToFile("StudentNotification.txt", $menteeNotify, "participant is less than 3");
    }

    if (count($moderatorNotify) > 0){
        writeToFile("ModeratorNotification.txt", $moderatorNotify, "mentor is less then 2");
    }




}

function writeToFile($fileName, $array, $message){
    $myfile = fopen($fileName, "w") or die("Unable to open file!");
    foreach($array as $value){
        foreach($value as $v){
            $content = "Name: " . $v["name"] . "    email: " . $v["email"] . "      SessionId: " . $v["sesId"] . "      Session Name: " . $v["sesName"] . "     is cancelled due to " . $message . "\n";
            fwrite($myfile, $content);
        }
    }



    fclose($myfile);
}


check();