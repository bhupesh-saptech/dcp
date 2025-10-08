<?php 
    require '../incld/verify.php';
    require '../incld/header.php';
    require '../suppl/top_menu.php';
    require '../suppl/side_menu.php';
    require '../incld/autoload.php';
    $sess = json_decode(json_encode($_SESSION));
    $conn = new Model\Util();
    $query = "select * from data_inv where lifnr = :lifnr and budat between :start_dt and :upto_dt";
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
            <h1 class="m-0">Vendor Invoices</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Invoices</li>
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
            <th>PO Num</th>
            <th>Item</th>
            <th>CC</th>
            <th>Doc No</th>
            <th>DYr</th>
            <th>Ln</th>
            <th>Inv No</th>
            <th>Inv Dt</th>
            <th>Pstg Dt</th>
            <th>GR Num</th>
            <th>GRYr</th>
            <th>Item</th>
            <th>Miro Value</th>
        </tr>
    </thead>
    <?php 
        if(isset($items)) {
            foreach($items as $item) {
                $item->lifnr = ltrim($item->lifnr,'0');
    ?>
             <tr>
                 <td><?php echo $item->lifnr; ?></td>
                 <td><?php echo $item->ebeln; ?></td>           
                 <td><?php echo $item->ebelp; ?></td>           
                 <td><?php echo $item->bukrs; ?></td>           
                 <td><?php echo $item->belnr; ?></td>           
                 <td><?php echo $item->gjahr; ?></td>           
                 <td><?php echo $item->buzei; ?></td>           
                 <td><?php echo $item->xblnr; ?></td>           
                 <td><?php echo $item->bldat; ?></td>           
                 <td><?php echo $item->budat; ?></td>           
                 <td><?php echo $item->lfbnr; ?></td>           
                 <td><?php echo $item->lfgja; ?></td>           
                 <td><?php echo $item->lfbln; ?></td>           
                 <td><?php echo $item->wrbtr; ?></td>           
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