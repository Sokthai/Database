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
        <td>Material ID</td>
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
        echo "<td>" . $value["materialId"] . "</td>";
        echo "<td>" . $value["title"] . "</td>";
        echo "<td>" . $value["author"] . "</td>";
        echo "<td>" . $value["type"] . "</td>";
        echo "<td>" . $value["url"] . "</td>";
        echo "<td>" . $value["assignDate"] . "</td>";
        echo "<td>" . $value["notes"] . "</td>";
        echo "<td><a href='../controller/assignment.php?moid=" . $_GET["moid"] . "&&info=" . $value["materialId"] . "</a></td>"  ;
//        header("location:../view/moderating.php?moid=" .$_GET["moid"] . "&&info=" . base64_encode(serialize($db->getAddableSection($_GET["moid"]))));

        echo "</tr>";
    }
    ?>

</table>
</body>
</html>