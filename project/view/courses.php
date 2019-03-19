<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
</head>
<body>
<form method="POST" action="../controller/enrollment.php">
    <table border="2px solid ">
        <?php
        echo "<pre>";
        //print_r(unserialize(base64_decode($_GET["info"])));
        echo "</pre>";
        ?>
        <tr>
            <th colspan="14">All Courses</th>
        </tr>
        <tr>
            <td>Section ID</td>
            <td>Section Name</td>
            <td>dayOfTheWeek</td>
            <td>startDate</td>
            <td>endDate</td>
            <td>capacity</td>
            <td>Course ID</td>
            <td>title</td>
            <td>startTime</td>
            <td>endTime</td>
            <td>description</td>
            <td>menteeGradeReq</td>
            <td>mentorGradeReq</td>
            <?php
                if (isset($_GET["mentee"]) || isset($_GET["mentor"])){
                    echo "<td>Enroll</td>";

                }
            ?>

        </tr>
        <?php

        foreach(unserialize(base64_decode($_GET["info"])) as $value){
            echo "<tr>";
            echo "<td>" . $value["secId"] . "</td>";
            echo "<td>" . $value["secName"] . "</td>";
            echo "<td>" . $value["dayOfTheWeek"] . "</td>";
            echo "<td>" . $value["startDate"] . "</td>";
            echo "<td>" . $value["endDate"] . "</td>";
            echo "<td>" . $value["capacity"] . "</td>";
            echo "<td>" . $value["cId"] . "</td>";
            echo "<td>" . $value["title"] . "</td>";
            echo "<td>" . $value["startTime"] . "</td>";
            echo "<td>" . $value["endTime"] . "</td>";
            echo "<td>" . $value["description"] . "</td>";
            echo "<td>" . $value["menteeGradeReq"] . "</td>";
            echo "<td>" . $value["mentorGradeReq"] . "</td>";

            if (isset($_GET["mentee"])){
                $mentee = unserialize(base64_decode($_GET["mentee"]));
                if ($mentee["grade"] >= $value["menteeGradeReq"]){
                    echo "<td><input type='checkbox' name='menteeEnrollment[]' value='" . $value["secId"] . "'>Mentee</td>";
                }
            }

            if (isset($_GET["mentor"])){
                $mentor = unserialize(base64_decode($_GET["mentor"]));
                if ($mentor["grade"] >= $value["mentorGradeReq"]){
                    echo "<td><input type='checkbox' name='mentorEnrollment[]' value='" . $value["secId"] . "'>Mentor</td>";
                }
            }
         echo "</tr>";
        }
        if (isset($_GET["mentee"])) {
            echo "<input type='hidden' name='mentee' value='" . unserialize(base64_decode($_GET["mentee"]))["studentId"] . "'>";

        }
        if (isset($_GET["mentor"])) {
            echo "<input type='hidden' name='mentor' value='" . unserialize(base64_decode($_GET["mentor"]))["studentId"] . "'>";

        }        ?>
    </table>
    <?php
        if (isset($_GET["mentee"]) || isset($_GET["mentor"])){
            echo "<input type='submit' value='Enroll'>";

        }
    ?>
</form>
</body>
</html>