<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
</head>
<body>

<table border="2px solid ">
    <?php
//    echo "<pre>";
//    print_r(unserialize(base64_decode($_GET["info"])));
//    echo "</pre>";
    ?>
    <tr>
        <th colspan="10">Assigned Session</th>
    </tr>
    <tr>
        <td>Material ID</td>
        <td>Section ID</td>
        <td>Session ID</td>
        <td>Type</td>
        <td>URL</td>
        <td>Assign Date</td>
        <td>Note</td>
        <td>Session Name</td>
        <td>Date</td>
        <td>Announcement</td>
    </tr>
    <?php
    $assignSession = unserialize(base64_decode($_GET["info"]));
    foreach($assignSession as $value){
        foreach($value as $k => $v) {

            echo "<tr>";
            echo "<td>" . $v["maid"] . "</td>";
            echo "<td>" . $v["secId"] . "</td>";
            echo "<td>" . $v["sesId"] . "</td>";
            echo "<td>" . $v["type"] . "</td>";
            echo "<td>" . $v["url"] . "</td>";
            echo "<td>" . $v["assignedDate"] . "</td>";
            echo "<td>" . $v["notes"] . "</td>";
            echo "<td>" . $v["sesName"] . "</td>";
            echo "<td>" . $v["date"] . "</td>";
            echo "<td>" . $v["announcement"] . "</td>";
            echo "</tr>";
        }
    }
    ?>

</table>
</body>
</html>