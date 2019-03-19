<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
</head>
<body>
<form action="../controller/assignment.php" method="POST">
    <table border="2px solid ">
        <?php
//            echo "<pre>";
//            print_r(unserialize(base64_decode($_GET["info"])));
//            echo "</pre>"
        ?>
        <tr>
            <th colspan="7">Select Session </th>
        </tr>
        <tr>
            <td>Section ID</td>
            <td>Session ID</td>
            <td>Session Name</td>
            <td>Date</td>
            <td>Announcement</td>
            <td>Moderator ID</td>
            <td>Material ID</td>
            <td>Assign</td>

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
            echo "<td><input type='checkbox' name='assignToSession[]' value='" .
                serialize([
                        'secId' => $value["secId"],
                        'sesId' => $value["sesId"],
                        'moid' => $_GET["moid"],
                        'maid' => $_GET["maid"]
                ])
                . "'</td>";
            echo "</tr>";
        }

        ?>

    </table>
    <input type="submit" name="submit" value="Assign">
</form>
</body>
</html>