<?php
  if(isset($_GET['table'])) {
    $myPDO = new PDO('sqlite:../TestDatabase/test.db');
    $options = $myPDO->query("select".$_GET['table']."from Jobs");

    echo $options;
  }

  // if (isset($_GET['table'])) {
  //     // Create a new PDO connection to the SQLite database
  //     $myPDO = new PDO('sqlite:../TestDatabase/test.db');

  //     // Build the query (add spaces around SQL keywords)
  //     $query = "SELECT " . $_GET['table'] . " FROM Jobs";

  //     // Run the query
  //     $options = $myPDO->query($query);

  //     // Fetch and display the results
  //     foreach ($options as $row) {
  //         echo $row[$_GET['table']] . "<br>";
  //     }
  // }
?>