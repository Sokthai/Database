<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
</head>
<body>

    <table border="2px solid ">
        <?php echo print_r(unserialize(base64_decode($_GET["info"]))); ?>
        <tr>
            <th colspan="10">Your Schedule</th>
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
            echo "</tr>";
        }
        ?>

    </table>

</body>
</html>