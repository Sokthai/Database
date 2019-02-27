<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
</head>
<body>
    <form method="post" action="">
        <table border="2px solid ">

            <tr><td colspan="2">Name (Parent)</td> </tr>
            <tr>
                <td>Name:</td>
                <td>Allen</td>
            </tr>
            <tr>
                <td>Email:</td>
                <td>Allen</td>
            </tr>
            <tr>
                <td>Phone:</td>
                <td>Allen</td>
            </tr>
            <tr>
                <td>City</td>
                <td>Allen</td>
            </tr>
            <tr>
                <td>State</td>
                <td>Allen</td>
            </tr>
            <tr>
                <td>Child</td>
                <td>Allen</td>
            </tr>
            <tr>
                <td>Add Child:</td>
                <td>
                    <select>
                        <?php
                            echo '<option value="test">' . 'testOption' . '</option>';
                        ?>
                    </select>
                </td>
            </tr>
        </table>
        <input type="submit" value="Update"/>
</form>
</body>
</html>