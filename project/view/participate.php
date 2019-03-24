<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
</head>
<body>
<form method="post" action="../controller/participation.php">
    <table border="2px solid ">
        <?php echo "<pre>";
            //echo print_r(unserialize(base64_decode($_GET["info"])));
        echo "</pre>";?>
        <tr>
            <th colspan="7">Participation</th>
        </tr>
        <tr>
            <td>SesId</td>
            <td>SecId</td>
            <td>Title</td>
            <td>session Name</td>
            <td>Date</td>
            <td>Announcement</td>
            <td>Participate</td>

        </tr>
        <?php
        foreach(unserialize(base64_decode($_GET["info"])) as $value){
            echo "<tr>";
            echo "<td>" . $value["sesId"] . "</td>";
            echo "<td>" . $value["secId"] . "</td>";
            echo "<td>" . $value["title"] . "</td>";
            echo "<td>" . $value["sesName"] . "</td>";
            echo "<td>" . $value["date"] . "</td>";
            echo "<td>" . $value["announcement"] . "</td>";
            echo "<td><input type='checkbox' name='participate[]' value=". base64_encode(serialize(["secId" => $value["secId"], "sesId" => $value["sesId"]])) . ">Participate</td>";
            echo "</tr>";
        }
          echo "<input type='hidden' name='id' value='" . $_GET["id"] ."''>";
        ?>

    </table>
    <input type="submit" value="Participate">
</form>
</body>
</html>