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
    require '../suppl/top_menu.php';
    require '../suppl/side_menu.php';
    
    $cntr = new Contr\VehPassContr();
    $poit = new Contr\POItemContr();
    $conn = new Model\Conn();

    if(isset($_POST['action'])) {
      $rqst = json_decode(json_encode($_POST));
      $actn = $rqst->action;
      switch($rqst->action) {
        case  'newChln' :
          $util->writeLog(json_encode($rqst));
          $cntr = new Contr\VehChlnContr();
          $cntr->createVehChln($rqst);
          $cntr = new Contr\VehItemContr();
          $cntr->createVehItem($rqst);
          break;
        case  'newItem' :
          $util->writeLog(json_encode($rqst));
          $cntr = new Contr\VehItemContr();
          $cntr->createVehItem($rqst);
          break;
        case  'modChln' :
          $util->writeLog(json_encode($rqst));
          $cntr = new Contr\VehChlnContr();
          $cntr->modifyVehChln($rqst);
          break;
        case  'modItem' :
          $util->writeLog(json_encode($rqst));
          $cntr = new Contr\VehItemContr();
          $cntr->modifyVehItem($rqst);
          break;
        case  'delChln' :
          $util->writeLog(json_encode($rqst));
          $cntr = new Contr\VehChlnContr();
          $cntr->deleteVehChln($rqst);
          break;
        case  'delItem' :
          $cntr = new Contr\VehItemContr();
          $cntr->deleteVehItem($rqst);
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


    $query = "select * from veh_chln where pass_id = ? 
                                       and supp_id = ?";
    $param = array($pass_id,$_SESSION['supp_id']);
    $chlns = $conn->execQuery($query,$param);

    // $util->writeLog(json_encode($chlns));

   
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
    include '../suppl/form_chln.php';
?>

<?php
    include '../incld/jslib.php'; 
?>
<script>
  $(document).ready(function () {
    $('.supp_id, .chln_no, .chln_yr').on('blur change keyup', function () {
      debugger;
      var $form = $(this).closest('.invForm');
      var supp_id = $form.find('.supp_id').val();
      var chln_no = $form.find('.chln_no').val();
      var chln_yr = $form.find('.chln_yr').val();
      var $error = $form.find('.error');

      if (supp_id && chln_no && chln_yr ) {
        $.get(window.location.href,{supp_id : supp_id,chln_no :chln_no, chln_yr : chln_yr},function(data) {
          if (data === 'true') {
              $error.text('Duplicate Challan.');
              $form.find('button[type="submit"]').prop('disabled', true);
            } else {
              $error.text('');
              $form.find('button[type="submit"]').prop('disabled', false);
            }
        });
      }
    });
  });

    function modChlnForm(obj) {

      if (obj.type === "button") {
        row = $(obj).closest('tr'); 
        row.find("select").prop('disabled', false);
        row.find("input").attr('readonly', false);
        obj.innerHTML = '<i class="fa fa-save"></i>';
        obj.type = "submit"; // ✅ Change to submit
        event.preventDefault();
      } 

    }
    function modItemForm(obj) {

      if (obj.type === "button") {
        row = $(obj).closest('tr'); 
        row.find('select[name="pitem"').prop('disabled', false);
        row.find('select[name="zpmat"').prop('disabled', false);
        row.find('input[name="lfimg"').attr('readonly', false);
        row.find('input[name="zpkqty"').attr('readonly', false);
        obj.innerHTML = '<i class="fa fa-save"></i>';
        obj.type = "submit"; // ✅ Change to submit
        event.preventDefault();
      } 

    }
	  function calcAmt(obj)  {

      let crow = $(obj).closest('tr');
      let rate = crow.find('input[name="netpr"]').val();
      let cqty = crow.find('input[name="lfimg"]').val();
      crow.find('input[name="netwr"]').val(rate * cqty);
    }
  
    function getPOItem(obj) {
      debugger;
      let ebeln = $(obj).val().substr(0,10);
      let ebelp = $(obj).val().substr(11,2);
      let rowno = $(obj).closest('tr');
      $.get(window.location.href, { ebeln: ebeln, ebelp: ebelp, getData: true }, function(data) {
        try {
              var oData = JSON.parse(data);
              console.log(data);
              rowno.find('input[name="ebeln"]').val(oData.ebeln);
              rowno.find('input[name="ebelp"]').val(oData.ebelp);
              rowno.find('input[name="matnr"]').val(oData.matnr);
              rowno.find('input[name="txz01"]').val(oData.txz01);
              rowno.find('input[name="mdesc"]').val(oData.matnr.replace(/^0+/, "") + '_' +oData.txz01);
              rowno.find('input[name="werks"]').val(oData.werks);
              rowno.find('input[name="lgort"]').val(oData.lgort);
              rowno.find('input[name="netpr"]').val(oData.netpr);
              rowno.find('input[name="vrkme"]').val(oData.meins);
            } catch (e) {
              console.error("Invalid JSON response", e);
            }
      });
    }

    function updtInvoice(obj) {
      debugger;
      let row = $(obj).closest('tr');
      row.find("input[name='cinv_no']").val($(obj).val());
    }
    function updtInvdate(obj) {
      debugger;
      let row = $(obj).closest('tr');
      row.find("input[name='cinv_dt']").val($(obj).val());
    }
    function setLakhFormat(obj) {
      let value = $(obj).val().replace(/,/g, '');
      if ($.isNumeric(value)) {
          $(obj).val(formatIndianNumber(value));
      }
    }
    function getFY(obj) {
			let date = new Date($(obj).val());
            let year = date.getFullYear();
            let month = date.getMonth() + 1; // Months are 0-based (0 = January)

            if (month < 4) {
                FY = (year - 1);
            } else {
                FY = year;
            }
			row = $(obj).closest('tr');
			row.find('input[name="chln_yr"]').val(FY);
			row.find('input[name="cinv_yr"]').val(FY);
    }
 </script>
<?php
    include('../incld/footer.php');
?>