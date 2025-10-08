<?php    
    $conn = new Model\Conn();
    if(isset($_GET['pstatus'])) {
        $_SESSION['pstatus'] = $_GET['pstatus'];
    } else {
        $_SESSION['pstatus'] = "";
    }
    $sess = json_decode(json_encode($_SESSION));
    $query = "select * from veh_data where cstat = ? and erdat between ? and ? order by pass_id desc";
    $param = array($sess->pstatus,
                   $sess->from_dt,
                   $sess->upto_dt);
    $items = $conn->execQuery($query,$param);
    $_SESSION['pref_id'] = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; 
?>
<div class="card">
    <div class="card-header">
        <?php include('../incld/messages.php'); ?>
    </div>
    <!-- /.card-header -->
    <div class="card-body"> 
        <table id="list_pass" class="table table-bordered table-striped">
            <thead>
                <tr class="bg-primary">
                    <th>QR</th>
                    <th>VehPass</th>
                    <th>Vehicle No</th>
                    <th>Transporter</th>
                    <th>Driver Name</th>
                    <th>Mobile</th>
                    <th>GatePass</th>
                    <th>Curr.Status</th>
                    <th>Next Action</th>
                    <th>Log</th>
                </tr>
            </thead>
            <tbody>
        <?php 
            if(isset($items)) {
                foreach($items as $item) { ?>
                    <tr>
                    <td class="text-center"><a href="javascript:void(0);" onclick="newTab('<?php echo '../qinsp/print_pass.php?pass_id='.$item->pass_id;?>');"><i class="fa fa-file-pdf"></i></a></td>
                        <td><a href="javascript:void(0);" onclick="newTab('<?php echo '../api/scp_pass.php?pass_id='.$item->pass_id;?>');"><?php echo $item->pass_id; ?></a></td>
                        <td><?php echo $item->zvehn;  ?></td>
                        <td><?php echo $item->ztnam;  ?></td>
                        <td><?php echo $item->zdnam;  ?></td>
                        <td><?php echo $item->zmbno;  ?></td>
                        <td><?php echo $item->zvpno;  ?></td>
                        <td class="bg-success"><?php echo $item->sdesc;  ?></td>
                        <td class="bg-warning"><?php echo $item->ntask;  ?></td>
                        <td class="text-center"><a href="javascript:void(0);" onclick="newTab('<?php echo '../commn/list_vlog.php?pass_id='.$item->pass_id;?>');"><i class="fa fa-history"></i></a></td>
                    </tr>
        <?php   }
            } 
        ?>
            </tbody> 
        </table>
    </div>
</div>
<?php
    include('../incld/jslib.php'); 
?>
<script>
    $(function () {
        $("#list_pass").DataTable({
        "pageLength": 20,"responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#list_pass_wrapper .col-md-6:eq(0)');

    });
    function newTab(url) {
        window.open(url,'_blank');
    }
</script>
<?php
    include('../incld/footer.php');
?>