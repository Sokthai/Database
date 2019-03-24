<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
</head>
<body>

<form method="post" action="../controller/moderate.php">
    <table border="2px solid ">
        <?php //echo print_r(unserialize(base64_decode($_GET["info"]))); ?>
        <tr>
            <th colspan="10">Moderating</th>
        </tr>
        <tr>
            <td>Course</td>
            <td>Section Name</td>
            <td>Start Date</td>
            <td>End Date</td>
            <td>Start Time</td>
            <td>End Time</td>
            <td>Day of Week</td>
            <td>Capacity</td>
            <td>Description</td>
            <?php
                if (isset($_GET["moid"])){
                    echo '<td>Select</td>';
                }
            ?>


        </tr>
        <?php
        foreach(unserialize(base64_decode($_GET["info"])) as $value){
            echo "<tr>";
            echo "<td>" . $value["title"] . "</td>";
            echo "<td>" . $value["secName"] . "</td>";
            echo "<td>" . $value["startDate"] . "</td>";
            echo "<td>" . $value["endDate"] . "</td>";
            echo "<td>" . $value["startTime"] . "</td>";
            echo "<td>" . $value["endTime"] . "</td>";
            echo "<td>" . $value["dayOfTheWeek"] . "</td>";
            echo "<td>" . $value["capacity"] . "</td>";
            echo "<td>" . $value["description"] . "</td>";
            //if (isset($_GET["moid"])) {
                echo "<td><input type='checkbox' name='secId[]' value=' " . $value["secId"] . "'</td>";
            //}
            echo "</tr>";
        }
        if (count(unserialize(base64_decode($_GET["info"]))) <= 0){
            echo "<b style='color: red'>YOU ARE MODERATING ALL SECTION. NO MORE SECTION FOR YOU TO MODERATE</b>";
        }


            //if (isset($_GET["moid"])) {
                echo "<input type='hidden' name='moderator' value='" . $_GET["moid"] . "'>";

            //}

        ?>

    </table>
    <?php
        //if (isset($_GET["moid"])){
            echo '<input type="submit" value="Moderate">';
        //}
    ?>
</form>
</body>
</html>