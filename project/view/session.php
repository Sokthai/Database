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
        <th colspan="7">Posted Material</th>
    </tr>
    <tr>
        <td>Section ID</td>
        <td>Session ID</td>
        <td>Session Name</td>
        <td>Date</td>
        <td>Announcement</td>
        <td>Moderator ID</td>
        <td>Material ID</td>

    </tr>
    <?php
    foreach(unserialize(base64_decode($_GET["info"])) as $value){
        echo "<tr>";
        echo "<td>" . $value["secId"] . "</td>";
        echo "<td>" . $value["sesId"] . "</td>";
        echo "<td>" . $value["sesName"] . "</td>";
        echo "<td>" . $value["date"] . "</td>";
        echo "<td>" . $value["announcement"] . "</td>";
        echo "<td>" . $_GET["moid"] . "</td>";
        echo "<td>" . $_GET["maid"] . "</td>";

        echo "</tr>";
    }
    ?>

</table>
</body>
</html>