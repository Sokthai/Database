<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
</head>
<body>
    <form method="post" action="">
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
                <td>Child</td>
                <td>Allen</td>
            </tr>
        </table>
        <input type="submit" value="Update"/>
</form>
</body>
</html>