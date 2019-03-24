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
        //echo //print_r(unserialize(base64_decode($_GET["info"])));
        echo "</pre>";?>
        <tr>
            <th colspan="7">Moderator List</th>
        </tr>
        <tr>
            <td>Name</td>
            <td>Phone</td>
            <td>Email</td>
        </tr>
        <?php
        foreach(unserialize(base64_decode($_GET["info"])) as $value){
            echo "<tr>";
            echo "<td>" . $value["name"] . "</td>";
            echo "<td>" . $value["phone"] . "</td>";
            echo "<td>" . $value["email"] . "</td>";
            echo "</tr>";
        }
        ?>

    </table>
    <!--    <input type="submit" value="Participate">-->
</form>
</body>
</html>