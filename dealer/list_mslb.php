<?php 
    include('../incld/verify.php');
    include('../incld/header.php');
    include('../suppl/top_menu.php');
    include('../suppl/side_menu.php');
    include('../incld/dbconn.php');
    if (isset($_SESSION['supp_id'])) {
        $lifnr = $_SESSION['supp_id'];
        $dtset = $conn->query("select * from sc_stock where lifnr = '$lifnr'");
        $items = json_decode(json_encode($dtset->fetch_all(MYSQLI_ASSOC)));
    }
    $conn->close();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Subcontracting Stocks</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">SC Stocks</li>
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
            <th>Vendor</th>
            <th>Material</th>
            <th>Description</th>
            <th>Plant</th>
            <th>Batch</th>
            <th>Unre Stock </th>
            <th>Insp Stock</th>
        </tr>
    </thead>
    <?php 
        if(isset($items)) {
            foreach($items as $item) {
                $item->lifnr = ltrim($item->lifnr,'0');
                $item->matnr = ltrim($item->matnr,'0');
    ?>
             <tr>
                 <td><?php echo $item->lifnr; ?></td>
                 <td><?php echo $item->matnr; ?></td>
                 <td><?php echo $item->txz01; ?></td>          
                 <td><?php echo $item->werks; ?></td>          
                 <td><?php echo $item->charg; ?></td>          
                 <td><?php echo $item->lblab; ?></td>          
                 <td><?php echo $item->lbins; ?></td>          
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