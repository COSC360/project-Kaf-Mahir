<?php

  require 'constants.php';

$conn = new mysqli(dbhost, dbuser, dbpass, dbname);

if ($conn -> connect_error) {
  die('Could not reach database: ' . $conn -> connect_error);
}
?>


