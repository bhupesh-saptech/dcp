<?php 
    include('../incld/autoload.php'); 
    date_default_timezone_set('Asia/Kolkata');
    $util = new Model\Util();
    if(isset($_POST['action'])) {
      $rqst = json_decode(json_encode($_POST));
      switch($rqst->action) {
        case 'setPlant': 
          $_SESSION['supp_id'] = $rqst->supp_id;
          break;
        case 'setDFilter' :
          $_SESSION['from_dt'] = $rqst->from_dt;
          $_SESSION['upto_dt'] = $rqst->upto_dt;
          break;
      }
    }
    if(!isset($_SESSION['from_dt'])) {
      $_SESSION['from_dt'] = date('Y-m-d', strtotime('-15 days'));
      $_SESSION['upto_dt'] = date('Y-m-d');
    }
    $sess = json_decode(json_encode($_SESSION));
   
    $query = "select * from usr_data where user_id = ?";
    $param = array($sess->user_id);
    $user  = $util->execQuery($query,$param,1);
    
    $query = "select * from obj_type where objty = 'PLNT'";
    $param = array();
    $objt  = $util->execQuery($query,$param,1);
    
    $query = "select * from usr_auth where objty = 'PLNT' and user_id = ? and objky not in (?)";
    $param = array($sess->user_id,$user->objky);
    $items  = $util->execQuery($query,$param);

    $item = new stdClass();
    $item->user_id = $user->user_id;
    $item->objty   = 'PLNT';
    $item->objky   = $user->objky;

    if(isset($items)) {
        array_push($items,$item);
    } else {
        $items = [];
        array_push($items,$item);
    }

?>
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="<?php echo "{$sess->home_pg}"; ?>" class="nav-link">
            Home
        </a>
      </li>
      <li class="nav-item">
            <select class="form-control" id="supp_id" onchange="setPlant(this);">
            <?php  foreach($items as $item) { 
                        if (!empty($item->objky)) {
                            $query = "select * from {$objt->table} where objky = ?";
                            $param = array($item->objky);
                            $objk  = $util->execQuery($query,$param,1);
                        } else {
                            $objk = new stdClass();
                            $objk->objnm = "";
                        }?>
                        <option value="<?php echo $item->objky; ?>" <?php if($item->objky == $user->objky ) { echo "selected"; } ?>> 
                            <?php echo "{$item->objky} : {$objk->objnm}"; ?>
                        </option> 
            <?php }?>
            </select>
        </li>
    <li class="nav-item">
        <a href="index.php" class="nav-link"><img src="../assets/dist/img/go.png" width="60px" hight="6px"></a>
    </li>
    <li class="nav-item">
      <form method="post">
        <input type="date" class="form-control ml-3" name="from_dt" value="<?php echo $sess->from_dt; ?>">
    </li>
    <li class="nav-item">  
        <input type="date" class="form-control ml-3" name="upto_dt" value="<?php echo $sess->upto_dt; ?>">
    </li>
    <li class="nav-item">
        <button type="submit" class="form-control ml-3" name="action" value="setDFilter"><i class="fa fa-filter"></i></button>
        </form>
    </li> 
    
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <div class="dropdown">
              <button class="btn btn-secondary dropdown-toggle" name="dropd" type="button" data-toggle="dropdown" aria-expanded="false">
                <?php if(isset($_SESSION['user_nm'])) {
                        echo $_SESSION['user_nm'];
                      } else {
                          echo 'Not Logged In';
                      }
                ?>
              </button>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="#">User Profile</a>
                <a class="dropdown-item" href="#">Messages</a>
                <form method="POST" action="../admin/logout.php">
                    <button type="submit" name="logout" class="dropdown-item">Logout</button>
                </form>
              </div>
            </div>
        </li>
    </ul>
  </nav>
  <!-- /.navbar -->