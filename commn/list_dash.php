<?php
    $conn = new Model\Util();
    $sess = json_decode(json_encode($_SESSION));
    switch($sess->role_nm) {
        case 'sgate' :
            $param = ['VP','VA','QA','CI','SC','CO'];
            break;
        case 'qinsp' :
            $param = ['VP','VA','QA','QR','QX','CI'];
            break;
        case 'gpent' :
            $param = ['CI','GP','ID','ID','GR','SC'];
            break;
        case 'stloc' :
            $param = ['GP','ID','SL','GW','UL','GR'];
            break;
        case 'waybr' :
            $param = ['SL','GW','UL','TW','GR','GR'];
            break;
        default      :
            $param = ['VP','VA','QA','QR','QX','CI'];
            break;
    }
    $place = implode(',', array_fill(0, count($param), '?'));
    echo $conn->writeLog($place);
    $query = "select * from veh_data where cstat in ($place)
                order by pass_id desc limit 10";
    $items = $conn->execQuery($query,$param);
    $_SESSION['pref_id'] = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; 
?>
<div class="card ">
    <div class="card-header">
        <?php include('../incld/messages.php'); ?>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="list_pass" class="table table-bordered table-striped">
            <tr class="bg-primary">
                <th>QR</th>
                <th>VGP Num</th>
                <th>VGP Date </th>
                <th>Vehicle No</th>
                <th>Transporter</th>
                <th>Driver Name</th>
                <th>Mobile</th>
                <th>GatePass</th>
                <th>Status</th>
                <th>Log</th>
            </tr>
        <?php 
            if(isset($items)) {
                foreach($items as $item) { ?>
                    <tr>
                        <td class="text-center"><a href="javascript:void(0);" onclick="newTab('<?php echo '../api/scp_qrcd.php?pass_id='.$item->pass_id;?>');"><i class="fas fa-qrcode"></i></a></td>
                        <td><a href="javascript:void(0);" onclick="newTab('<?php echo 'form_pass.php?pass_id='.$item->pass_id;?>');"><?php echo $item->pass_id; ?></a></td>
                        <td><?php echo $item->erdat;  ?></td>
                        <td><?php echo $item->zvehn;  ?></td>
                        <td><?php echo $item->ztnam;  ?></td>
                        <td><?php echo $item->zdnam;  ?></td>
                        <td><?php echo $item->zmbno;  ?></td>
                        <td><?php echo $item->zvpno;  ?></td>
                        <td class="<?php echo "{$item->cscol}"?>"><?php echo $item->sdesc;  ?></td>
                        <td class="text-center"><a href='<?php echo "../commn/list_vlog.php?pass_id={$item->pass_id}"; ?>'><i class="fa fa-history"></i></a></td>
                        
                    </tr>
        <?php   }
            } 
        ?>
        </table>
    </div>
</div>


<?php
    include('../incld/jslib.php'); ?>
    <script>
        $(function () {
            $("#list_pass").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#list_pass_wrapper .col-md-6:eq(0)');

        });
    </script>
<?php
    include('../incld/footer.php');
?>