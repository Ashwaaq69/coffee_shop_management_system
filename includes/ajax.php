<?php  
include 'includes/connection.php';
include 'functions.php';
if(isset($_POST['action']) and $_POST['action']=='forUpdate'){
    $result = read_where($_POST['table']," id=".$_POST['id']);
    echo json_encode($result[0]);
}



