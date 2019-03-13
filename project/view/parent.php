<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Parent</title>
</head>
<body>
    <table border="2px solid ">
<!--        <?php //echo print_r(unserialize(base64_decode($_GET["info"]))["child"]);?> -->
        <tr><td colspan="2"><?php echo strtoupper(unserialize(base64_decode($_GET["info"]))["role"]);?></td> </tr>
        <tr>
            <td>Name:</td>
            <td><?php echo strtoupper(unserialize(base64_decode($_GET["info"]))["name"]);?> </td>
        </tr>
        <tr>
            <td>Email:</td>
            <td><?php echo strtoupper(unserialize(base64_decode($_GET["info"]))["email"]);?></td>
        </tr>
        <tr>
            <td>Phone:</td>
            <td><?php echo strtoupper(unserialize(base64_decode($_GET["info"]))["phone"]);?> </td>
        </tr>
        <tr>
            <td>City</td>
            <td><?php echo strtoupper(unserialize(base64_decode($_GET["info"]))["city"]);?> </td>
        </tr>
        <tr>
            <td>State</td>
            <td><?php echo strtoupper(unserialize(base64_decode($_GET["info"]))["state"]);?> </td>
        </tr>
        <tr>
            <td>Moderator</td>
            <td>
                <?php
                if (unserialize(base64_decode($_GET["info"]))["moderator"] != ""){
                    echo '<input name="moderator" type="checkbox" value="1" checked readonly/>';
                }else{
                    echo '<input name="moderator" type="checkbox" value="1" readonly/>';
                }
                ?>
            </td>
        </tr>
        <tr>
            <td>Child</td>
            <td>
                <?php
                    echo "<table border='1px solid red'>";
                            array_map(function($value){
                               echo "<tr>";
                               echo "<td width='100px'>" . $value["name"];
                               echo "<td>" . "<a href='../controller/edit.php?id=" . $value["id"] . "'>Edit</a></td>";
                               echo "<tr/>";
                           }, unserialize(base64_decode($_GET["info"]))["child"]);
                    echo "</table>";
                ?>
            </td>
        </tr>
    </table>
    <a href="../controller/edit.php?id=<?php echo strtoupper(unserialize(base64_decode($_GET["info"]))["id"]);?>">Edit</a>

</body>
</html>