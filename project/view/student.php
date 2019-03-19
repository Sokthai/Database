<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
</head>
<body>

        <table border="2px solid ">
            <?php echo print_r(unserialize(base64_decode($_GET["info"]))); ?>
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
                <td>Parent</td>
                <td><?php echo strtoupper(unserialize(base64_decode($_GET["info"]))["parent"][0]["name"]);?></td>
            </tr>
            <tr>
                <td>Role</td>
                <td>
                    <?php
                        $role = unserialize(base64_decode($_GET["info"]));
                        if ($role["mentee"] && $role["mentor"]){
                            echo "Mentee, Mentor";
                        }elseif ($role["mentee"]){
                            echo "Mentee";
                        }elseif ($role["mentor"]){
                            echo "Mentor";
                        }

                    ?>
                </td>
            </tr>
        </table>
        <a href="../controller/edit.php?id=<?php echo strtoupper(unserialize(base64_decode($_GET["info"]))["id"]);?>">Edit</a>

</body>
</html>