<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post Material</title>
</head>
<body>
<form action="../controller/post.php" method="post" autocomplete="on">

    <div class="container">
        <h1>Post Material</h1>
        <label for="title"><b>Title</b></label>
        <input type="text" placeholder="Enter title" name="title" required>
        <br/>
        <label for="author"><b>Author</b></label>
        <input type="text" placeholder="Enter Author" name="author">
        <br/>
        <label for="type"><b>Type</b></label>
        <input type="text" placeholder="Enter Type" name="type" required>
        <br/>
        <label for="url"><b>URL</b></label>
        <input type="text" placeholder="Enter URL" name="url">
        <br/>
        <label for="assignDate"><b>Assign Date</b></label>
        <input type="date" name="assignDate" required>
        <br/>
        <label for="note"><b>Note</b></label>
        <textarea placeholder="Enter Note" name="note" required></textarea>
        <br/>
        <input type="hidden" name="moderatorId" value="<?php echo $_GET["moid"];?>">
        <button type="submit" class="registerbtn">Post</button>
    </div>


</form>
</body>
</html>