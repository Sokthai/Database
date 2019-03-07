<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
</head>
<body>
<form method="post" action="../controller/update.php">
    <table border="2px solid ">

        <?php echo "<pre>"; echo print_r(unserialize(base64_decode($_GET["info"]))); echo "</pre>";?>
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
        <input type="hidden" name="state" id="state" value="<?php echo strtoupper(unserialize(base64_decode($_GET["info"]))["id"]);?>">


        <?php
                $info = unserialize(base64_decode($_GET["info"]));
                if (isset($_GET["student"])){ //if student themselves want to edit their own profile
                    echo '<label for="role"><b>Role</b></label>';
                    echo '<select name="status" required>';

                    if ($info["mentor"] == 1 && $info["mentee"] == 1){
                        echo '<option value="0">Mentee</option>';
                        echo '<option value="1">Mentor</option>';
                        echo '<option value="2" selected>Both</option> ';
                    }elseif ($info["mentor"] == 1){
                        echo '<option value="0">Mentee</option>';
                        echo '<option value="1" selected>Mentor</option>';
                        echo '<option value="2">Both</option> ';
                    }else{
                        echo '<option value="0" selected>Mentee</option>';
                        echo '<option value="1">Mentor</option>';
                        echo '<option value="2" >Both</option> ';
                    }
                    echo '</select>';
                }
            ?>


    </table>
    <input type="submit" value="Update"/>
</form>
</body>
</html>