<?php

     
    require '../incld/verify.php';
    require '../buyer/check_auth.php';
    require '../incld/header.php';
    require '../buyer/top_menu.php';
    require '../buyer/side_menu.php';
    require '../buyer/dashboard.php';
    require '../incld/autoload.php';
    
    $conn = new Model\Conn();
    if(isset($_GET['cstat'])) {
      $cstat = $_GET['cstat'];
    } else {
      $cstat = "";
    }
    $query = "select * from veh_data where cstat = ? order by pass_id desc"; 
    $param = array($cstat);
    $items = $conn->execQuery($query,$param);
    $_SESSION['pref_id'] = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; 
    include('../suppl/tabl_pass.php');
?>