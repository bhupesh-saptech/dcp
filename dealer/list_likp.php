<?php 
    require '../incld/verify.php';
    require '../incld/header.php';
    require '../dealer/top_menu.php';
    require '../dealer/side_menu.php';
    require '../dealer/dashboard.php';
    require '../incld/autoload.php';
    $util = new Model\Util();
    $sess = json_decode(json_encode($_SESSION));
    $query = "select * from zobd where kunnr = ?";
    $param = array($sess->cust_id);
    $items = $util->execQuery($query,$param);
?>
<div class="card">
    <div class="card-header">
    </div>
    <!-- /.card-header -->
    <div class="card-body">
            <table id="dtbl" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Customer </th> 
                    <th>Delivery No</th>
                    <th>Item</th>
                    <th>Create Dt</th>
                    <th>Material</th>
                    <th>Description</th>
                    <th>Plant</th>
                    <th>Del Qty</th>
                    <th>UoM</th>
                </tr>
            </thead>
            <?php 
                if(isset($items)) {
                    foreach($items as $item) {
                        $item->kunnr = ltrim($item->kunnr,'0');
                        $item->matnr = ltrim($item->matnr,'0');
            ?>
                    <tr>
                        <td><?php echo $item->kunnr; ?></td>
                        <td><?php echo $item->vbeln; ?></td>
                        <td><?php echo $item->posnr; ?></td>
                        <td><?php echo $item->erdat; ?></td>
                        <td><?php echo $item->matnr; ?></td>
                        <td><?php echo $item->arktx; ?></td>
                        <td><?php echo $item->werks; ?></td>
                        <td class="text-right"><?php echo $item->lfimg; ?></td>
                        <td><?php echo $item->vrkme; ?></td>
                    </tr>
            <?php 
                    } 
                }
            ?>
        </table>
    </div>
</div>


<?php
    include('../incld/jslib.php');
    include('../incld/footer.php');
?>