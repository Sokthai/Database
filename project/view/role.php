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
                    echo "My mentor Course";
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
                listAll($value, "<a href='#'>Remove</a>");
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

        function listAll($value, $role){
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
            echo "<td>$role</td>";

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