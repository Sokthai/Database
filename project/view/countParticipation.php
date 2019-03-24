<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
</head>
<body>
    <table border="2px solid ">
        <?php echo "<pre>";
//        echo print_r(unserialize(base64_decode($_GET["info"])));
        echo "</pre>";?>
        <tr>
            <th colspan="7">Count Participation</th>
        </tr>
        <tr>
            <td>SesId</td>
            <td>Title</td>
            <td>Session Name</td>
            <td>Date</td>
            <td>Announcement</td>
            <td># of Mentor</td>
            <td># of Participate Mentee</td>


        </tr>
        <?php
        foreach(unserialize(base64_decode($_GET["info"])) as $value){
            echo "<tr>";
            echo "<td>" . $value["sesId"] . "</td>";
            echo "<td>" . $value["title"] . "</td>";
            echo "<td>" . $value["sesName"] . "</td>";
            echo "<td>" . $value["date"] . "</td>";
            echo "<td>" . $value["announcement"] . "</td>";
            echo "<td>" . $value["mentorCount"] . "/3</td>";
            echo "<td>" . $value["participantCount"] . "/6</td>";
            echo "</tr>";
        }
        ?>

    </table>
</body>
</html>