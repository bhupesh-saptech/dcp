<?php 
    require '../incld/verify.php';
    require '../incld/header.php';
    require '../dealer/top_menu.php';
    require '../dealer/side_menu.php';
    require '../dealer/dashboard.php';
    require '../incld/autoload.php';
    $util = new Model\Util();
    $sess = json_decode(json_encode($_SESSION));
    $query = "select * from zord where kunnr = ?";
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
                <th>Order No</th>
                <th>Item</th>
                <th>Order Date</th>
                <th>Material</th>
                <th>Description</th>
                <th>Plant</th>
                <th>SO Qty</th>
                <th>UoM</th>
                <th>SOrg</th>
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
                    <td><?php echo $item->aedat; ?></td>
                    <td><?php echo $item->matnr; ?></td>
                    <td><?php echo $item->arktx; ?></td>
                    <td><?php echo $item->werks; ?></td>
                    <td class="text-right"><?php echo $item->menge; ?></td>
                    <td><?php echo $item->vrkme; ?></td>
                    <td><?php echo $item->vkorg; ?></td>
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