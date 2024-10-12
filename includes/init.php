<?php include 'session.php';
      include 'functions.php';
if (!isset($_SESSION['userId']) &&  $_SESSION['isLogin'] !== true) {
    header("location: login.php");
}
include 'header.php';
include 'sidebar.php';

