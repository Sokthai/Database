<?php
 
  $myconnection = mysqli_connect('localhost', 'root', '') 
    or die ('Could not connect: ' . mysql_error());

  $mydb = mysqli_select_db ($myconnection, 'movie') or die ('Could not select database');

  $query = 'SELECT DISTINCT title, year FROM movies order by year';
  $result = mysqli_query($myconnection, $query) or die ('Query failed: ' . mysql_error());

  echo 'Title &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Year<br>';

  while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC)) {    
    echo $row["title"];
    echo "&nbsp;&nbsp;&nbsp;";
    echo $row["year"];
    echo '<br>';
  }

  mysqli_free_result($result);

  mysqli_close($myconnection);

?>
