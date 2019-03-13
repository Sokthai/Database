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
                if (unserialize(base64_decode($_GET["info"]))["moderator"] == 1){
                    echo '<li><a href="postMaterial.php?moid=' . unserialize(base64_decode($_GET["info"]))["id"] . '" target="_self">Post Material</a></li>';
                    echo '<li><a href="../controller/posted.php?moid=' . unserialize(base64_decode($_GET["info"]))["id"] . '" target="_self">View Posted Material</a></li>';

                }

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