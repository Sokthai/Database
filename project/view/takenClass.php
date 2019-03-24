<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
</head>
<body>

<table border="2px solid ">
    <?php
//        echo "<pre>ok";
//        print_r(unserialize(base64_decode($_GET["info"])));
//        echo "</pre>";
    ?>
    <tr>
        <th colspan="10">Record</th>
    </tr>
    <tr>
        <td>Title</td>
        <td>Description</td>
        <td>Grade</td>
        <td>Semester</td>

    </tr>
    <?php
    $takenClass = unserialize(base64_decode($_GET["info"]));
    foreach($takenClass as $v) {
        echo "<tr>";
        echo "<td>" . $v["title"] . "</td>";
        echo "<td>" . $v["description"] . "</td>";
        echo "<td>" . $v["grade"] . "</td>";
        echo "<td>" . $v["startDate"] . "</td>";
        echo "</tr>";
    }
    ?>

</table>
</body>
</html>