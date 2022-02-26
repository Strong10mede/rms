<?php

//connect to database
$conn = mysqli_connect("127.0.0.1:3307", "amish", "test1234", "rms");
//check connection
if (!$conn) {
  //error in connection
  echo "Connection error: " . mysqli_connect_error();
}
