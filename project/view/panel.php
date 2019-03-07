<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php $_GET["info"] ?></title>
</head>
<body>
    <h2><?php echo strtoupper(unserialize(base64_decode($_GET["info"]))["role"]);?> Panel</h2>
    <ul>
    <?php
        if (unserialize(base64_decode($_GET["info"]))["role"] == "parent"){
               //echo  '<li><a href="#" target="_self">Enrollment</a></li>';
               echo '<li><a href="parent.php?info=' . $_GET["info"] . '" target="_self">Edit Profile</a></li>';
               echo '<li><a href="#" target="_self">My Schedule</a></li>';
               //echo '<li><a href="#" target="_self">My class</a></li>';
        }else{
            echo '<li><a href="#" target="_self">Enrollment</a></li>';
            echo '<li><a href="student.php?info=' . $_GET["info"] . '" target="_self">Edit Profile</a></li>';
            echo '<li><a href="#" target="_self">My Schedule</a></li>';
            echo '<li><a href="#" target="_self">My class</a></li>  ';
        }
    ?>



    </ul>
</body>
</html>