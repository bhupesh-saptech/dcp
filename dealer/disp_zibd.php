<?php
    require '../incld/verify.php';
    require '../suppl/check_auth.php';
    require '../incld/autoload.php';
    $conn = new Model\Conn();
    $util = new Model\Util();
    if(isset($_GET['getData'])) {
      
      $rqst = json_decode(json_encode($_GET));
      $query = "select * from data_poi where ebeln = ? and ebelp = ?";
      $param = array($rqst->ebeln,$rqst->ebelp);
      $item  = $conn->execQuery($query,$param,1);
      echo json_encode($item);
      die();
    }
    require '../incld/header.php';
    require '../incld/top_menu.php';
    require '../suppl/side_menu.php';
    
    $cntr = new Contr\VehPassContr();
    $poit = new Contr\POItemContr();
    $conn = new Model\Conn();

    if(isset($_POST['action'])) {
      $rqst = json_decode(json_encode($_POST));
      $actn = $_POST['action'];
      switch($actn) {
        case  'newChln' :
          $util->writeLog(json_encode($rqst));
          $cntr = new Contr\VehChlnContr();
          $cntr->createVehChln($rqst);
          $cntr = new Contr\VehItemContr();
          $cntr->createVehItem($rqst);
          break;
        case  'newItem' :
          $cntr = new Contr\VehItemContr();
          $cntr->createVehItem($rqst);
          break;
        case  'modChln' :
          $util->writeLog(json_encode($rqst));
          $cntr = new Contr\VehChlnContr();
          $cntr->modifyVehChln($rqst);
          break;
        case  'modItem' :
          $cntr = new Contr\VehItemContr();
          $cntr->modifyVehItem($rqst);
          break;
        default :
          $actn = $_POST['action'];
          break;
      }
    } else {
      $actn = "noAction";
    }
    
    
    if(isset($_GET['pass_id'])) {
      $pass_id = $_GET['pass_id'];
    } else {
        $pass_id = "";
    }
    $query = "select * from veh_data where pass_id = ?";
    $param = array($pass_id);
    $pass = $conn->execQuery($query,$param,1);


    $query = "select * from veh_chln where pass_id = ?";
    $param = array($pass_id);
    $chlns = $conn->execQuery($query,$param);

    // $util->writeLog(json_encode($chlns));

    $query = "select * from veh_item where pass_id = ?";
    $param = array($pass_id);
    $items = $conn->execQuery($query,$param);
   
    $query = "select * from data_poi where lifnr =?";
    $param = array($_SESSION['supp_id']);
    $pitem = $conn->execQuery($query,$param);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">VP - Challan Details</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">VP-Challans</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div><!-- /.content-header -->

<?php
    include '../suppl/form_zibd.php';
?>

<?php
    include '../incld/jslib.php'; 
?>

<script>
    $( document ).ready(function() {
        text = $('#action').val();
        switch(text) {
            case 'add':
                $("input[name='vehno']").attr('readonly',false);
                $("button[name='setVeh']").attr('disabled', false);
                break;
            case 'mod' :
                $("input").attr('readonly', false);
                $("select").prop('disabled', false);
                break;
            case 'view':
                $("input").attr('readonly', true);
                $("select").prop('disabled', true);
                break;
        }
    });

    function getPOItem(obj) {
      let ebeln = $(obj).val().substr(0,10);
      let ebelp = $(obj).val().substr(11,2);
      let rowno = $(obj).closest('tr');
      $.get(window.location.href, { ebeln: ebeln, ebelp: ebelp, getData : true }, function(data) {
        var oData = JSON.parse(data);
        rowno.find('input[name="matnr"]').val(oData.matnr);
        rowno.find('input[name="txz01"]').val(oData.txz01);
        rowno.find('input[name="netpr"]').val(oData.netpr);
        rowno.find('input[name="meins"]').val(oData.meins);
      });
    }
    
    function updtInvoice(obj) {
      debugger;
      let row   = $(obj).closest('tr');
      row.find("input[name='cinv_no']").val($(obj).val());
    }
    function updtInvdate(obj) {
      let row   = $(obj).closest('tr');
      row.find("input[name='cinv_dt']").val($(obj).val());
    }


</script>

<?php
    include('../incld/footer.php');
?>