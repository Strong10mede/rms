<?php
session_start();

if (!isset($_SESSION['login_user_email'])) {
  header("Location: login.php");
  die();
}
