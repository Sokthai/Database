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
//        echo print_r(unserialize(base64_decode($_GET["info"])));
        echo "</pre>";?>
        <tr>
            <th colspan="7">Assign Material</th>
        </tr>
        <tr>
            <td>SesId</td>
            <td>Title</td>
            <td>Author</td>
            <td>Type</td>
            <td>URL</td>
            <td>Assign Date</td>
            <td>Note</td>

        </tr>
        <?php
        foreach(unserialize(base64_decode($_GET["info"])) as $value){
            echo "<tr>";
            echo "<td>" . $value["sesId"] . "</td>";
            echo "<td>" . $value["title"] . "</td>";
            echo "<td>" . $value["author"] . "</td>";
            echo "<td>" . $value["type"] . "</td>";
            echo "<td>" . $value["url"] . "</td>";
            echo "<td>" . $value["assignedDate"] . "</td>";
            echo "<td>" . $value["notes"] . "</td>";
            echo "</tr>";
        }
        ?>

    </table>
<!--    <input type="submit" value="Participate">-->
</form>
</body>
</html>