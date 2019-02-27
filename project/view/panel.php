<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php $_GET["name"] ?></title>
</head>
<body>
    <h2><?php echo strtoupper($_GET["name"]); echo serialize(["test1", "test2"]);?> Panel</h2>
    <ul>
        <li><a href="#" target="_self">Enrollment</a></li>
        <li><a href="editProfile.php" target="_self">Edit Profile</a></li>
        <li><a href="#" target="_self">My Schedule</a></li>
        <li><a href="#" target="_self">My class</a></li>

    </ul>
</body>
</html>