<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
</head>
<body>
<form method="post" action="../controller/update.php">
    <table border="2px solid ">
        <?php echo print_r(unserialize(base64_decode($_GET["info"]))); ?>
        <tr><td colspan="2"><?php echo strtoupper(unserialize(base64_decode($_GET["info"]))["role"]);?></td> </tr>
        <tr>
            <td><label for="name">Name:</label></td>
            <td>
                <input type="text" name="name" id="name" value="<?php echo strtoupper(unserialize(base64_decode($_GET["info"]))["name"]);?>">
            </td>
        </tr>
        <tr>
            <td><label for="email">Email:</label></td>
            <td>
                <input type="email" name="email" id="email" value="<?php echo strtoupper(unserialize(base64_decode($_GET["info"]))["email"]);?>">
            </td>
        </tr>
        <tr>
            <td><label for="phone">Phone:</label></td>
            <td>
                <input type="number" name="phone" id="phone" value="<?php echo strtoupper(unserialize(base64_decode($_GET["info"]))["phone"]);?>">
            </td>
        </tr>
        <tr>
            <td><label for="city">City:</label></td>
            <td>
                <input type="text" name="city" id="city" value="<?php echo strtoupper(unserialize(base64_decode($_GET["info"]))["city"]);?>">
            </td>
        </tr>
        <tr>
            <td><label for="state">State:</label></td>
            <td>
                <input type="text" name="state" id="state" value="<?php echo strtoupper(unserialize(base64_decode($_GET["info"]))["state"]);?>">
            </td>
        </tr>
        <input type="hidden" name="id" value="<?php echo strtoupper(unserialize(base64_decode($_GET["info"]))["id"]);?>">

        <tr>
            <td>Moderator</td>
            <td>
                <?php
                    if (unserialize(base64_decode($_GET["info"]))["moderator"] != ""){
                        echo '<input name="moderator" type="checkbox" value="1" checked/>';
                    }else{
                        echo '<input name="moderator" type="checkbox" value="1"/>';
                    }

                ?>
            </td>
        </tr>

    </table>
    <input type="submit" value="Update"/>
</form>
</body>
</html>