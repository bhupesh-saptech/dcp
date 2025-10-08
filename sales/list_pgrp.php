<?php 
    include('../incld/verify.php');
    include('../incld/header.php');
    include('../incld/top_menu.php');
    include('../buyer/side_menu.php');
    include('../incld/dbconn.php');
    if(isset($_SESSION['user'])) {
        $user = $_SESSION['user'];
        $dtset  = $conn->query("select * from prgroup where user_id = '$user->user_id';");
        $items = json_decode(json_encode($dtset->fetch_all(MYSQLI_ASSOC)));
        $conn->close();
    }
    
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Purchasing Groups</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Purch Group</li>
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
            <th>Group</th> 
            <th>Group Name</th>
            <th>Email ID</th>
            <th>Contact</th>
            <th>Mobile No</th>
            <th>Extension</th>
            <th>User ID</th>
        </tr>
    </thead>
    <?php 
        foreach($items as $item) { 
    ?>
            <tr>
                <td><?php echo $item->ekgrp; ?> <input type="hidden" name="ekgrp" value="<?php echo $pgrp->ekgrp ;?>"></td>
                <td><?php echo $item->eknam; ?> </td>
                <td><?php echo $item->email; ?> </td>
                <td><?php echo $item->ektel; ?> </td>
                <td><?php echo $item->phone; ?> </td>
                <td><?php echo $item->extno; ?> </td>
                <td><?php echo $item->user_id; ?></td>
             </tr>
    <?php 
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