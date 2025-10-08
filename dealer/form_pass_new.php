<?php
    //require '../incld/verify.php';
    require '../suppl/check_auth.php';
    require '../incld/autoload.php';
    $cntr = new Contr\PassContr();
    if(isset($_REQUEST['pass_id'])) {
        $pass_id = $_REQUEST['pass_id'];
        if($pass_id=="") {
            $action="veh";
        } else {
            $action = 'view';
        }
           
    }  else {
        $pass_id = "";
        $action = 'veh'; 
    }
    $query = "select * from veh_data where pass_id = ?";
    $param = array($pass_id);
    $items = $cntr->readPass($query,$param);
    $item  = $items[0];
    if(isset($_POST['setPass'])) {
        $rqst = json_decode(json_encode($_POST));
        switch($_POST['setPass']) {
            case 'add' :
                $items = $cntr->createPass($rqst);
                $item  = $items[0];
                $_SESSION['status'] = 'Vehicle Pass : '.$item->pass_id. ' Created';
                $action = 'inv';
                break;
            case 'mod' :
                $items = $cntr->modifyPass($rqst);
                $item  = $items[0];
                $action = 'view';
                break;
            case 'view' :
                $action = 'mod';
                break;
            case 'inv' : 
                $query = "select * from veh_data where pass_id = ?";
                $param = array($pass_id);
                $items = $cntr->readPass($query,$param);
                $item  = $items[0];
                if ($item->lifnr != $rqst->lifnr) {
                    $cntr->createVehSupp($rqst->pass_id,$rqst->lifnr);
                }
                header("Location:disp_zibd.php?pass_id=".$item->pass_id);
                exit(0);
                break;
        }
    }

    if(isset($_POST['setVeh'])) {
        $vehn = $_POST['vehno'];
        $item = $cntr->readVehn($vehn);
        switch($item->cstat) {
            case 'VP' :
                if($item->pass_id == "") {
                    $action = 'add';
                } else {
                    $action = 'inv';
                }
                break;
            case 'CO' :
                $item->pass_id = '';
                $item->zlrno   = '';
                $item->cstat   = 'VP';
                $action = 'add';
                break;
            default   :
                $action = 'view';
                break;
        }
        
    }
    include('../incld/header.php');
    include('../incld/top_menu.php');
    include('../suppl/side_menu.php');
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
            
        <form method="POST" class="was-validated" >
            <div class="row">
                <div class="col-md-2">
                </div>
                <div class="col-md-8">
                      <div class="row">
                            <div class="col-md-4 mt-4">
                                <div class="form-group">

                                    <input class="form-control"  oninput="this.value = this.value.toUpperCase();" name="vehno"  pattern="[A-Z]{2}[0-9]{2}[A-Z]{2}[0-9]{4}" placeholder = "Search Vehicle" value="" required> 
                                </div>
                            </div>
                            <div class="col-md-2 mt-4">
                                <div class="form-group">
                                    <button type="submit" class="form-control btn btn-primary" name="setVeh" value="" onclick="<?php if($action == 'view') { echo 'addForm();';} else { echo 'viewForm();';} ?>">Search</button>                                
                                </div>
                            </div>
                  
                         <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Pass Number</label>
                                <input type="text"   name="pass_id" class="form-control"  value="<?php echo $item->pass_id; ?>" readonly> 
                            </div>
                         </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="">Status</label>
                                <input type="text" name="sdesc" class="form-control" value="<?php echo $item->sdesc;?>"  readonly>
                            </div>
                        </div>
    
                       </div>
                         <div class="card">
                            <div class="row">
                                 <div class="col-md-12">
                                 </div>
                            </div>
                        </div>
                    
             </form>
            <form method="POST" >
                <div class="row">
                <input type="hidden" id="actPass"   class="form-control"  value="<?php echo $action; ?>">
                <input type="hidden" name="ernam"   class="form-control"  value="<?php echo $_SESSION['user_id']; ?>">
                <input type="hidden" name="lifnr"   class="form-control"  value="<?php echo $_SESSION['supp_id']; ?>">
                <input type="hidden" name="shtyp"   class="form-control"  value="<?php echo $item->shtyp; ?>" >
                <input type="hidden" name="tplst"   class="form-control"  value="<?php echo $item->tplst; ?>" >
                <input type="hidden" name="zbunt"   class="form-control"  value="<?php echo $item->zbunt; ?>" >
                <input type="hidden" name="zdref"   class="form-control"  value="<?php echo $item->zdref; ?>"   >
                <input type="hidden" name="zzins"   class="form-control"  value="<?php echo $item->zzins; ?>"    >
                <input type="hidden" name="cstat"   class="form-control"  value="<?php echo $item->cstat; ?>" >
               </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="">Vehicle Number</label>
                            <input type="text" name="zvehn" class="form-control" value="<?php echo $item->zvehn;?>" >
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="">Transporter Name</label>
                            <input type="text" name="ztnam" class="form-control" value="<?php echo $item->ztnam; ?>"  required>
                             <div class="valid-feedback">Valid...</div>
                            <div class="invalid-feedback">Please enter values</div>  
                 </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="">Driver Name</label>
                            <input type="text" name="zdnam" class="form-control" value="<?php echo $item->zdnam; ?>"  required>
                            <div class="valid-feedback">Valid...</div>
                            <div class="invalid-feedback">Please enter values</div>  

                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="">LR Number</label>
                            <input type="text" name="zlrno" class="form-control" value="<?php echo $item->zlrno; ?>"  >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="">Driver Phone</label>
                            <input type="tel" name="zmbno" class="form-control" pattern="[0-9]{10}" value="<?php echo $item->zmbno; ?>" required>
                         </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="">LR Date</label>
                            <input type="date" name="zlrdt" class="form-control" value="<?php echo $item->zlrdt; ?>"  >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="">Driver License</label>
                            <input type="text" name="zlcno" class="form-control" value="<?php echo $item->zlcno;?>"  required>
                            <div class="valid-feedback">Valid...</div>
                            <div class="invalid-feedback">Please enter values</div>  
                        </div>
                    </div>
                    
                      <div class="col-sm-3">
                            <div class="form-group">
                                <label for="">State</label>
                                <select name="stateSelect" class="form-control" onchange="getCity();">
                                    <option selected>Select State</option>
                                     <option selected>MH</option>
                                     <option selected>AP</option>
                                </select>
                               </div>
                      </div>
                      <div class="col-sm-3">
                            <div class="form-group">
                                <label for="">City</label>
                                 <select name="citySelect" class="form-control" >
                                    <option selected>Select City</option>
                                </select>
                            </div>
                      </div>

                </div>     
                <div class="row">
                     <div class="col-sm-12 mt-4">
                        <button type="button" name="btnClose" class="btn btn-danger  float-right ml-4" value="cls" onclick="window.close();">Close</button>
                        <button type="submit" name="setPass"  class="btn btn-primary float-right ml-4" value="inv" <?php switch($action) {
                                                                                                                            case 'veh'  : echo 'disabled'; break;
                                                                                                                            case 'add'  : echo 'disabled'; break;
                                                                                                                            case 'mod'  : echo 'disabled'; break;
                                                                                                                            case 'view' : if($item->cstat != 'VP') { echo 'disabled';} break; 
                                                                                                                            }?>
                                                                                                                                >Add Invoices</button>
                        <button type="submit" name="setPass"  class="btn btn-success float-right "  value="<?php    switch($action) { 
                                                                                                                            case 'add' : echo 'add';  break; 
                                                                                                                            case 'mod' : echo 'mod';  break;
                                                                                                                            default    : echo 'view'; break;
                                                                                                                        } ?>" <?php    switch($action) { 
                                                                                                                            case 'veh'  : echo 'disabled';break;
                                                                                                                            case 'view' : if($item->cstat != 'VP') { echo 'disabled';} break; 
                                                                                                                        } ?>>
                                                                                                                <?php   switch($action) {
                                                                                                                            case 'veh' : echo 'Create vehPass';break; 
                                                                                                                            case 'add' : echo 'Create vehPass';break;
                                                                                                                            case 'mod' : echo 'Update Pass'; break;
                                                                                                                            default    :  echo 'Modify'; break;
                                                                                                                        } ?></button>
                    

                    </div>
                </div>
            </form>
            </div>
            <div class="col-md-2">  
            </div>
            </div>
        </div>
    </div>
</div>
<?php
    include('../incld/jslib.php');

?>
<script>
function getCity(obj) {
      debugger;
      let state = $(obj).val();
      ckey= 'NHhvOEcyWk50N2Vna3VFTE00bFp3MjFKR0ZEOUhkZlg4RTk1MlJlaA==';
      $.get("https://api.countrystatecity.in/v1/countries/IN/states/", {headers: {"X-CSCAPI-KEY": ckey}}, function(data) {
        console.log(data);
      });
</script>
    