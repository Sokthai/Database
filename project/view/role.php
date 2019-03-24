<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
</head>
<body>
<form method="POST" action="#">
    <table border="2px solid ">
        <?php
        echo "<pre>";
        //print_r(unserialize(base64_decode($_GET["info"])));
        echo "</pre>";
        ?>
        <tr>
            <th colspan="14">
                <?php
                if (isset($_GET["mentee"])){
                    echo "My Mentee Course";
                }elseif(isset($_GET["mentor"])) {
                    echo "My Mentor Course";
                }else{
                    echo "My Enrollment Course";
                }
                ?>
            </th>
        </tr>
        <tr>
            <td>Section ID</td>
            <td>Section Name</td>
            <td>title</td>
            <td>dayOfTheWeek</td>
            <td>startDate</td>
            <td>endDate</td>
            <td>capacity</td>
            <td>Course ID</td>
            <td>startTime</td>
            <td>endTime</td>
            <td>description</td>
            <td>menteeGradeReq</td>
            <td>mentorGradeReq</td>
            <?php
                if (isset($_GET["enrollment"])){
                    echo "<td>Role</td>";
                }else{

                    echo "<td>Remove</td>";
                    if (isset($_GET["mentor"])){
                        echo "<td>View Mentor</td>";
                    }
                }
            ?>

        </tr>
        <?php

        foreach(unserialize(base64_decode($_GET["info"])) as $key => $value){
            if (isset($_GET["enrollment"])){
                foreach($value as $v){
                    listAll($v, $key);
                }
            }else{
                if (isset($_GET["mentee"])){
                    listAll($value, "menteeDeletion", unserialize(base64_decode($_GET["mentee"]))["studentId"]);

//                    listAll($value, "<a href='../controller/enrollment.php?menteeDeletion='" . unserialize(base64_decode($_GET["mentee"]))["studentId"] . "'>Remove</a>");
                }elseif(isset($_GET["mentor"])) {
                    listAll($value, "mentorDeletion", unserialize(base64_decode($_GET["mentor"]))["studentId"]);

//                    listAll($value, "<a href='../controller/enrollment.php?mentorDeletion='" . unserialize(base64_decode($_GET["mentor"]))["studentId"] . "'>Remove</a>");
                }
            }
        }




//        if (isset($_GET["mentee"])) {
//            echo "<input type='hidden' name='mentee' value='" . unserialize(base64_decode($_GET["mentee"]))["studentId"] . "'>";
//
//        }
//        if (isset($_GET["mentor"])) {
//            echo "<input type='hidden' name='mentor' value='" . unserialize(base64_decode($_GET["mentor"]))["studentId"] . "'>";
//
//        }

        function listAll($value, $role, $id = null){
            echo "<tr>";
            echo "<td>" . $value["secId"] . "</td>";
            echo "<td>" . $value["secName"] . "</td>";
            echo "<td>" . $value["title"] . "</td>";
            echo "<td>" . $value["dayOfTheWeek"] . "</td>";
            echo "<td>" . $value["startDate"] . "</td>";
            echo "<td>" . $value["endDate"] . "</td>";
            echo "<td>" . $value["capacity"] . "</td>";
            echo "<td>" . $value["cId"] . "</td>";
            echo "<td>" . $value["startTime"] . "</td>";
            echo "<td>" . $value["endTime"] . "</td>";
            echo "<td>" . $value["description"] . "</td>";
            echo "<td>" . $value["menteeGradeReq"] . "</td>";
            echo "<td>" . $value["mentorGradeReq"] . "</td>";
            if ($id == null){
                echo "<td>" . $role . "</td>";
            }else {
                echo "<td><a href='../controller/enrollment.php?secId=" . $value["secId"] . "&&" . $role . "=" . $id . "'>Remove</a></td>";
                if (isset($_GET["mentor"])){
                    echo "<td><a href='../controller/mentor.php?secId=" . $value["secId"] ."'>View</a></td>";
                }
            }
        }
        ?>
    </table>
    <?php
    if (isset($_GET["mentee"]) || isset($_GET["mentor"])){
        echo "<input type='submit' value='Update'>";

    }
    ?>
</form>
</body>
</html>