<?php 
    require '../incld/verify.php';
    require '../incld/header.php';
    require '../suppl/top_menu.php';
    require '../suppl/side_menu.php';
    require '../incld/autoload.php';

    $sess = json_decode(json_encode($_SESSION));
    $conn = new Model\Util();
    $query = "select * from data_pos where lifnr = :lifnr and eindt between :start_dt and :upto_dt";
    $param = array($sess->supp_id,$sess->from_dt,$sess->upto_dt);
    $items = $conn->execQuery($query,$param);

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Delivery Schedules</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Delivery Schedules</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div><!-- /.content-header -->
    <div class="container">
        <div class="row">
            <div class="col-md-12">

            </div><!-- /.col-md-col12 -->
        </div><!-- /.row -->
    </div><!-- /.container -->

    <div class="card">
        <div class="card-header">
        </div>
        <!-- /.card-header -->
        <div class="card-body">
    <table id="dtbl" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Order Number</th>
            <th>Order Item</th>
            <th>SchLine</th>
            <th>Sch Date</th>
            <th>Material</th>
            <th>Description</th>
            <th>PO Quantity</th>
        </tr>
    </thead>
    <?php 
        if(isset($items)) {
            foreach($items as $item) { 
                $item->matnr = ltrim($item->matnr,'0');
    ?>
             <tr>
                 <td><?php echo $item->ebeln; ?></td>
                 <td><?php echo $item->ebelp; ?></td>
                 <td><?php echo $item->etenr; ?></td>
                 <td><?php echo $item->eindt; ?></td>
                 <td><?php echo $item->matnr; ?></td>
                 <td><?php echo $item->txz01; ?></td>
                 <td><?php echo $item->menge; ?></td>
             </tr>
    <?php 
            }
        }
    ?>
</table>
</div>
    </div>
</div>
<?php
    include('../incld/jslib.php');
    include('../incld/footer.php');
?>