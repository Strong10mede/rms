<?php
require("session.php");
require "connect.php";

if (isset($_GET['house_id']) && !empty($_GET["house_id"])) {

  $house_id = mysqli_real_escape_string($conn, $_GET["house_id"]);

  $sql = "DELETE FROM assigned WHERE house_id='$house_id';";

  if (mysqli_query($conn, $sql)) {
    header("Location: houseDetails.php?id=" . $house_id);
  } else {
    echo "query error: " . mysqli_error($conn);
  }
} else {
  header("Location: index.php");
}
