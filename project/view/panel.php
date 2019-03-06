<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php $_GET["info"] ?></title>
</head>
<body>
    <h2><?php echo strtoupper(unserialize(base64_decode($_GET["info"]))["name"]);?> Panel</h2>
    <ul>
        <li><a href="#" target="_self">Enrollment</a></li>
        <li><a href="editProfile.php?info=<?php echo $_GET["info"]?>" target="_self">Edit Profile</a></li>
        <li><a href="#" target="_self">My Schedule</a></li>
        <li><a href="#" target="_self">My class</a></li>

    </ul>
</body>
</html>