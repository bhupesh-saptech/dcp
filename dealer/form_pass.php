<?php
    require '../incld/verify.php';
    require '../suppl/check_auth.php';
    require '../incld/autoload.php';
    $cntr = new Contr\VehPassContr();
    $util = new Model\Util();  
    $conn = new Model\Conn();
    
    if(isset($_POST['action'])) {
        $rqst = json_decode(json_encode($_POST));
        $rqst->user_id = $_SESSION['user_id'];
        $actn = $rqst->action;
        switch($rqst->action) {
            case 'getVehicle'  :
                $query = "select * from veh_data where pass_id in (select max(pass_id) where zvehn = ?)";
                $param = array($rqst->vehno);
                $pass = $conn->execQuery($query,$param,1);
                break;
            case 'addPass' :
                $pass = $cntr->createPass($rqst);
                $rqst->pass_id = $pass->pass_id;
                $rqst->pstatus = 'VP';
                $cntr->updtVPStatus($rqst);
                $_SESSION['status'] = 'Vehicle Pass : '.$pass->pass_id. ' Created';
                header("Location:disp_chln.php?pass_id=".$pass->pass_id);
                exit(0);
                break;
            case 'modPass' :
                $pass = $cntr->modifyPass($rqst);
                $_SESSION['status'] = 'Vehicle Pass : '.$pass->pass_id. ' Modified';
                $actn = 'viewPass';
                break;
            case 'viewPass' :
                $query = "select * from veh_data where pass_id = ?";
                $param = array($rqst->pass_id);
                $pass = $conn->execQuery($query,$param,1);
                $actn = 'modPass';
                break;
            case 'invPass' : 
                $query = "select * from veh_data where pass_id = ?";
                $param = array($rqst->pass_id);
                $pass = $conn->execQuery($query,$param,1);
                if ($pass->lifnr != $rqst->lifnr) {
                    $cntr->createVehSupp($rqst->pass_id,$rqst->lifnr);
                }
                header("Location:disp_chln.php?pass_id=".$pass->pass_id);
                exit(0);
                break;
        }
    }
    include('../incld/header.php');
    include('../suppl/top_menu.php');
    include('../suppl/side_menu.php');
    if(isset($_GET['pass_id'])) {
        $actn = "viewPass";
        $rqst = json_decode(json_encode($_GET));
        $query = "select * from veh_data where pass_id = :pass_id";
        $param = array($rqst->pass_id);
        $pass  = $conn->execQuery($query,$param,1);
    }  else {
        if(isset($rqst->vehno)) { 
            if(isset($pass)) {
                switch($pass->cstat) {
                    case 'VP' :
                        $actn = 'invPass';
                        break;
                    case 'CO' :
                        $pass->pass_id = '';
                        $pass->zlrno   = '';
                        $pass->cstat   = 'VP';
                        $actn = 'addPass';
                        break;
                    default   :
                        $actn = 'viewPass';
                        break;
                } 
            } else {
                $pass = new Contr\VehPassContr();
                $pass->zvehn = $rqst->vehno;
                $actn = "addPass";
            }
        } else {
            $pass = new Contr\VehPassContr();
            $actn = 'vehPass';
        }
    }
    $states = $util->getStates();
    $cities = $util->getCities($pass->state);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Vehicle Pass </h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Vehicle Pass</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div><!-- /.content-header -->
    <div class="card">
        <div class="row">
            <div class="col-md-2">
            </div>
            <div class="col-md-8">
                <div class="card-header">
                    <?php include('../incld/messages.php'); ?>
                </div>
            </div>
            <div class="col-md-2">
            </div>
        </div>
    </div>
        <div class="card-body">
           
            <div class="row">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-8">
                    <table class="table table-bordered table-stripped">
                        <tr class="bg-dark">
                            <td colspan="5"><h1>Vehicle Pass Details</h1></td>
                        <tr>
                    
                        <tr class="bg-info">
                            <form method="POST" >
                            <td>
                                <label for="">Search Vehicle</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="vehno" pattern="^[A-Z]{2}[0-9]{2}[A-Z]{2}[0-9]{4}$"   value="" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit" name="action" value="getVehicle">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                            </form>
                            <form method="POST" >
                            <td class="col-sm-3">
                                <label for="">Vehicle No</label>
                                <input type="text" name="zvehn" class="form-control" value="<?php echo $pass->zvehn;?>" required  >
                            </td>
                            <td class="col-sm-3">
                                <label for="">Pass Status</label>
                                <input type="text"   name="cstat" class="form-control"  value="<?php echo "{$pass->cstat} : {$pass->sdesc}"; ?>" required  >   
                            </td>
                            <td class="col-sm-3">
                                <label for="">Pass Number</label>
                                <input type="text"   name="pass_id" class="form-control"  value="<?php echo $pass->pass_id; ?>" required  >      
                            </td>
                        </tr>
                    </table>
                    <table class="table table-bordered table-stripped">
                        <tr>
                            <th class="col-md-6">
                                <input type="hidden" name="pass_id" class="form-control"  value="<?php echo $pass->pass_id; ?>"   >
                                <input type="hidden" name="action"  class="form-control"  value="<?php echo $actn; ?>" id="action">
                                <input type="hidden" name="user_id" class="form-control"  value="<?php echo $_SESSION['user_id']; ?>">
                                <input type="hidden" name="lifnr"   class="form-control"  value="<?php echo $_SESSION['supp_id']; ?>">
                                <input type="hidden" name="zvehn"   class="form-control"  value="<?php echo $pass->zvehn  ?>">
                                <input type="hidden" name="shtyp"   class="form-control"  value="<?php echo $pass->shtyp; ?>" >
                                <input type="hidden" name="tplst"   class="form-control"  value="<?php echo $pass->tplst; ?>" >
                                <input type="hidden" name="zbunt"   class="form-control"  value="<?php echo $pass->zbunt; ?>" >
                                <input type="hidden" name="zdref"   class="form-control"  value="<?php echo $pass->zdref; ?>"   >
                                <input type="hidden" name="zzins"   class="form-control"  value="<?php echo $pass->zzins; ?>"    >
                                <input type="hidden" name="cstat"   class="form-control"  value="<?php echo $pass->cstat; ?>" >
                                <label for="">Loading State</label>
                                <select  name="state" class="form-control" value="" onchange="getCities(this);" required>
                                    <option value=''>Select State </option>
                                    <?php foreach( $states as $state) { ?>
                                        <option value="<?php echo "{$state->iso2}"; ?>" <?php if($pass->state  == $state->iso2 ) { echo 'selected';}?>><?php echo "{$state->iso2} : {$state->name}"; ?></option>
                                    <?php } ?>
                                </select>
                            </th>
                            <th class="col-md-6">
                                <label for="">Loading Location</label>
                                <select  name="zzloc" class="form-control" id="cities" required >
                                    <option value="">Select City </option>
                                    <?php foreach( $cities as $city) { ?>
                                        <option value="<?php echo $city->name; ?>" <?php if($pass->zzloc == $city->name ) { echo 'selected';}?> ><?php echo "{$city->name}"; ?></option>
                                    <?php } ?>
                                </select>    
                            </th>
                        </tr>
                        <tr>
                            <td>
                                <label for="">Driver Name</label>
                                <input type="text" name="zdnam" class="form-control" value="<?php echo $pass->zdnam; ?>"  required>
                            </td>
                            <td>
                                <label for="">Transporter Name</label>
                                <input type="text" name="ztnam" class="form-control" value="<?php echo $pass->ztnam; ?>"  required>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="">Driver Phone</label>
                                <input type="tel" name="zmbno" class="form-control" pattern="[0-9]{10}" value="<?php echo $pass->zmbno; ?>" required>
                            </td>
                            <td>
                                <label for="">Driver License</label>
                                <input type="text" name="zlcno" class="form-control" oninput="this.value = this.value.toUpperCase();" value="<?php echo $pass->zlcno;?>"  required>    
                            </td>
                
                        </tr>
                        <tr>
                            <td>
                                <label for="">LR Number</label>
                                <input type="text" name="zlrno" class="form-control" value="<?php echo $pass->zlrno; ?>"  >
                            </td>
                            <td>
                                <label for="">LR Date</label>
                                <input type="date" name="zlrdt" class="form-control" value="<?php echo $pass->zlrdt; ?>" onfocus="this.max = new Date().toISOString().split('T')[0]" >
                            </td>                    
                        </tr>
                        <tr>
                            
                            <td colspan="2">
                                <button type="button" name="btnClose" class="btn btn-danger  float-right  ml-2" value="cls" onclick="window.close();">Close</button>
                                <button type="submit" name="action"   class="btn btn-primary float-right  ml-2" value="invPass" <?php switch($actn) {
                                                                                                                                    case 'vehPass'  : echo 'disabled'; break;
                                                                                                                                    case 'addPass'  : echo 'disabled'; break;
                                                                                                                                    case 'modPass'  : echo 'disabled'; break;
                                                                                                                                    case 'viewPass' : if($pass->cstat != 'VP') { echo 'disabled';} break; 
                                                                                                                                    }?>
                                                                                                                                        >Add Invoices</button>
                                <button type="submit" name="action"  class="btn btn-success  float-right  ml-2"  value="<?php    switch($actn) { 
                                                                                                                                    case 'addPass'  : echo 'addPass';  break; 
                                                                                                                                    case 'modPass'  : echo 'modPass';  break;
                                                                                                                                    default         : echo 'viewPass'; break;
                                                                                                                                } ?>" <?php    switch($actn) { 
                                                                                                                                    case 'veh'  : echo 'disabled';break;
                                                                                                                                    case 'view' : if($pass->cstat != 'VP') { echo 'disabled';} break; 
                                                                                                                                } ?>>
                                                                                                                        <?php   switch($actn) {
                                                                                                                                    case 'vehPass' : echo 'Create vehPass';break; 
                                                                                                                                    case 'addPass' : echo 'Create vehPass';break;
                                                                                                                                    case 'modPass' : echo 'Update Pass'; break;
                                                                                                                                    default    : echo 'Modify'; break;
                                                                                                                                } ?></button>
                            
                            </td>
                            </form>                    
                        </tr>
                    </table>
                </div>
                <div class="col-sm-2">
                </div>
            </Div>
        </div>
    </div>
</div>
<?php
    include('../incld/jslib.php');
?>
<script>

$( document ).ready(function() {
    actn = $('#action').val();
    switch(actn) {
        case 'vehPass':
            $("input").attr('readonly', true);
            $("select").prop('disabled', true);
            $("input[name='vehno']").attr('readonly',false);
            $("button[name='setVeh']").attr('disabled', false);
            break;
        case 'addPass':
            $("input[name='vehno']").attr('readonly',false);
            $("button[name='setVeh']").attr('disabled', false);
            $("input[name='pass_id']").attr('readonly',true);
            $("input[name='zvehn']").attr('readonly',true);
            $("input[name='sdesc']").attr('readonly',true);
            break;
        case 'invPass':
            $("input").attr('readonly', true);
            $("select").prop('disabled', true);
            $("button[name='setVeh']").attr('disabled', true); 
            break;
        case 'modPass' :
            $("input").attr('readonly', false);
            $("select").prop('disabled', false);
            $("input[name='vehno']").attr('readonly',true);
            $("button[name='setVeh']").attr('disabled', true);
            $("input[name='pass_id']").attr('readonly',true);
            $("input[name='zvehn']").attr('readonly',true);
            $("input[name='sdesc']").attr('readonly',true);
            break;
        case 'viewPass':
            $("input").attr('readonly', true);
            $("select").prop('disabled', true);
            $("button[name='setVeh']").attr('disabled', true);   
            break;
    }
});
function validateForm(obj,event) {
    debugger;
    veh = $(obj).find('input[name="vehno"]');
    msg = veh.next("span");
    if (!veh[0].checkValidity()) {
        msg.text("Invalid format (e.g., KA01AB1234)");
        event.preventDefault();
    } else {
        msg.text("Valid Vehicle Number");
    }
}
function getCities(obj) {
        let api_val = $(obj).val();
        let options = $('#cities');
        if(api_val=="") {
            options.empty();
        } else {
            let api_url = "https://api.countrystatecity.in/v1/countries/IN/states/"+ api_val +"/cities/";
            let api_key = 'NHhvOEcyWk50N2Vna3VFTE00bFp3MjFKR0ZEOUhkZlg4RTk1MlJlaA==';
        
            $.ajax({
                url: api_url,
                method : 'GET',
                dataType: 'json',
                headers : {
                    'X-CSCAPI-KEY' : api_key,
                    'Content-Type' : 'application/json'
                },
                success : function(data) {
                    console.log(data);
                    options.empty();
                    options.append('<option value="">Select an option</option>');
                    $.each(data, function(index, item) {
                        options.append('<option value="' + item.name + '">' + item.name + '</option>');
                    });
                } 
            });
        }
    }

    function addForm(obj) {
        $("input").attr('readonly', false);
        $("input[name='pass_id']").attr('readonly',true);
        $("input[name='zvehn']").attr('readonly',true);
        $("button[name='action']").prop("disabled",false);
    }
    function viewForm() {
        $("input").attr('readonly', true);
        $("select").prop('disabled', true);
    }
    function enInput(obj) {
        $("input").attr('readonly', false);
        $("input[name='vehno']").attr('readonly',true);
        $("button[name='setVeh']").prop("disabled",true);
        $("input[name='pass_id']").attr('readonly',true);
        $("input[name='zvehn']").attr('readonly',true);
        $("button[name='action']").prop("disabled",false);
    }
</script>

<?php
    include('../incld/footer.php');
?>