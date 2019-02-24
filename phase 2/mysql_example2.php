<?php
   
  $year = $_POST['year']; 
 
  $myconnection = mysqli_connect('localhost', 'root', '') 
    or die ('Could not connect: ' . mysqli_error($myconnection));

  $mydb = mysqli_select_db ($myconnection, 'movie') or die ('Could not select database');

  $query = "SELECT DISTINCT title, length FROM movies WHERE genre = '" . $year . "'";

  $result = mysqli_query($myconnection, $query) or die ('Query failed: ' . mysqli_error($myconnection));

  echo 'Title &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Length &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Year<br>';

  while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC)) {    
    echo $row["title"];
    echo "&nbsp;&nbsp;&nbsp;";
    echo $row["length"];
    echo "&nbsp;&nbsp;&nbsp;";
    echo $year;
    echo "&nbsp;&nbsp;&nbsp;";
    echo '<br>';
  }

  mysqli_free_result($result);

  mysqli_close($myconnection);







?>
