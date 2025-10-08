    <div class="card">
        <div class="row">
            <div class="col-md-12">
                <div class="card-header">
                    <?php include('../incld/messages.php'); ?>
                    <h1 class="card-title">Add Challans to Vehicle Pass</h1> 
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="tblPass">
                <tr class="bg-dark">
                    <th class="col-sm-2">
                        <label for="">Vehicle Number</label>
                        <input type="text" name="zvehno"   class="form-control bg-secondary"  value="<?php echo $pass->zvehn;   ?>" readonly>
                    </th>
                    <th class="col-sm-2">
                        <label for="">Transporter</label>
                        <input type="text" name="ztnam"   class="form-control bg-secondary"  value="<?php echo $pass->ztnam;   ?>" readonly>
                    </th>
                    <th class="col-sm-2">
                        <label for="">Driver Name</label>
                        <input type="text" name="zdnam"   class="form-control bg-secondary"  value="<?php echo $pass->zdnam;   ?>" readonly>
                    </th>
                    <th class="col-sm-1">
                        <label for="">Contact</label>
                        <input type="text" name="zmbno"   class="form-control bg-secondary"  value="<?php echo $pass->zmbno;   ?>" readonly>
                    </th>
                    <th class="col-sm-2">
                        <label for="">LR Number</label>
                        <input type="text" name="zlrno"   class="form-control bg-secondary"  value="<?php echo $pass->zlrno;   ?>" readonly>
                    </th>
                    <th class="col-sm-1">
                        <label for="">LR Date</label>
                        <input type="date" name="zlrdt"   class="form-control bg-secondary"  value="<?php echo $pass->zlrdt;  ?>" readonly >
                    </th>
                    <th class="col-sm-2">
                        <label for="">Vehicle Pass</label>
                        <input type="text" name="pass_id"  class="form-control bg-secondary"  value="<?php echo $pass->pass_id; ?>" readonly>       
                    </th>
                </tr>
            <?php
                if(isset($chlns)) {
                    foreach($chlns as $chln) { ?>
                        <form method="post">
                        <input type="hidden" name="pass_id" value="<?php echo $chln->pass_id; ?>" >
                        <input type="hidden" name="chln_id" value="<?php echo $chln->chln_id; ?>" >
                        <input type="hidden" name="chln_yr" value="<?php echo '2024'; ?>" >
                        <input type="hidden" name="cinv_yr" value="<?php echo '2024'; ?>" >
                        <tr class="bg-primary" id="rowChln">
                            <td>
                                <label for="">Challan Number</label>
                                <input type="text" name="chln_no" class="form-control bg-secondary" value="<?php echo "{$chln->chln_no}";?>" readonly>
                            </td>
                            <td>
                                <label for="">Challan Date</label>
                                <input type="date" name="chln_dt" class="form-control bg-secondary" value="<?php echo "{$chln->chln_dt}";?>" readonly> 
                            </td>    
                            <td>
                                <label for="">Invoice Number</label>
                                <input type="text" name="cinv_no" class="form-control bg-secondary" value="<?php echo "{$chln->cinv_no}";?>" readonly>
                            </td>
                            <td>
                                <label for="">Invoice Date</label>
                                <input type="date" name="cinv_dt" class="form-control bg-secondary" value="<?php echo "{$chln->cinv_dt}";?>" readonly>
                            </td>
                            <td>
                                <label for="">LR Number</label>
                                <input type="text" name="trlr_no" class="form-control bg-secondary" value="<?php echo "{$chln->trlr_no}";?>" readonly>
                            </td>
                            <td>
                                <label for="">LR Date</label>
                                <input type="date" name="trlr_dt" class="form-control bg-secondary" value="<?php echo "{$chln->trlr_dt}";?>" readonly>
                            </td>
                            <td>
                                <label for="">Inv Value</label><button type="submit" name="action"  class="btn btn-default float-right" value="modChln" ><img src="../assets/dist/img/chg-record.png" style="width:20px;height:20px"></button>
                                <input type="text"  name="cinv_vl" class="form-control bg-secondary text-right"  value="<?php echo "{$chln->cinv_vl}";?>" readonly >
                            </td>
                        </tr>
                        </form>
            <?php
            
                        if(isset($items)) {
                            $cnt = 0;
                            foreach($items as $item) { 
                                if ($item->chln_id == $item->chln_id) { $cnt = $cnt + 1; ?>
                                    <form method="post">     
                                    <input type="hidden" name="pass_id" value="<?php echo $item->pass_id; ?>" >
                                    <input type="hidden" name="chln_id" value="<?php echo $item->chln_id; ?>" > 
                                    <input type="hidden" name="item_id" value="<?php echo $item->item_id; ?>" >              
                                    <tr class="bg-info">
                                        <td>
                                            <?php if($cnt == 1) { echo "<label for=''>PO Item</label>";} ?>
                                            <input type="text" name="pitem" class="form-control bg-secondary" value="<?php echo "{$item->ebeln}_{$item->ebelp}"; ?>" readonly></td>
                                        <td>
                                            <?php if($cnt == 1) { echo "<label for=''>Material</label>";} ?>
                                            <input type="text" name="matnr" class="form-control bg-secondary" value="<?php echo "{$item->matnr}"; ?>" readonly></td>
                                        <td colspan="2">
                                            <?php if($cnt == 1) { echo "<label for=''>Description</label>";} ?>
                                            <input type="text" name="txz01" class="form-control bg-secondary" value="<?php echo "{$item->txz01}"; ?>" readonly></td>
                                        <td>
                                            <?php if($cnt == 1) { echo "<label for=''>PO Rate</label>";} ?>
                                            <input type="text" name="netpr" class="form-control bg-secondary" value="<?php echo "{$item->netpr}"; ?>" readonly></td>
                                        <td>
                                            <?php if($cnt == 1) { echo "<label for=''>Delivery Qty</label>";} ?>
                                            <input type="text" name="lfimg" class="form-control text-right bg-secondary" value="<?php echo "{$item->lfimg}"; ?>" readonly></td>
                                        <td>
                                            <div class="row">                        
                                                <div class="col-sm-8"> 
                                                    <?php if($cnt == 1) { echo "<label for=''>Uom</label>";} ?>
                                                    <input type="text" name="meins" class="form-control bg-secondary"  value="<?php echo "{$item->meins}"; ?>"  readonly>
                                                </div>
                                                <div class="col-sm-4">
                                                    <?php if($cnt == 1) { echo "<label for=''>_</label>";} ?>    
                                                    <button type="submit" name="action"  class="btn btn-default float-right" value="modItem"><img src="../assets/dist/img/chg-record.png" style="width:20px;height:20px"></button>
                                                </div>
                                            </div>    
                                        </td>
                                    </tr>
                                    </form>
            <?php 
                                }
                            }
                        } 
            ?>
                <form method="post">
                    <input type="hidden" name="pass_id" value="<?php echo $chln->pass_id; ?>" >
                    <input type="hidden" name="chln_id" value="<?php echo $chln->chln_id; ?>" >
                    <input type="hidden" name="item_id" value="<?php echo $item->item_id + 1; ?>" >
                    <tr class="bg-info" id="rowItem">
                        <td colspan="1">
                            <select name="pitem" class="form-control"  onchange="getPOItem(this);" required>
                                <?php foreach($pitem as $pitm) { ?>
                                    <option value="<?php echo "{$pitm->ebeln}_{$pitm->ebelp}"; ?>"><?php echo "{$pitm->ebeln}_{$pitm->ebelp} [{$pitm->matnr}]_{$pitm->txz01}_{$pitm->netpr}"; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td colspan="1">
                            <input type="text" name="matnr" class="form-control" value="<?php echo ""; ?>" readonly></td>
                        <td colspan="2">
                            <input type="text" name="txz01" class="form-control" value="<?php echo ""; ?>" readonly></td>
                        <td colspan="1">
                            <input type="text" name="netpr" class="form-control" value="<?php echo ""; ?>" readonly></td>
                        <td colspan="1">
                            <input type="text" name="lfimg" class="form-control" value="<?php echo ""; ?>" required></td>
                        <td colspan="1">
                            <div class="row">                        
                                <div class="col-sm-8"> 
                                    <input type="text" name="meins" class="form-control"  value="";  readonly>
                                </div>
                                <div class="col-sm-4">  
                                    <button type="submit" name="action"  class="btn btn-default" value="newItem" ><img src="../assets/dist/img/add-record.png" style="width:20px;height:20px"></button>
                                </div>
                            </div>  
                        </td>
                    </tr>
                </form>
            <?php 
                    }
                }
                if($actn == "addChln") { ?>
                <form method="post">
                    <input type="hidden" name="pass_id" value="<?php echo $pass->pass_id; ?>" >
                    <?php if(isset($chln)) { ?>
                        <input type="hidden" name="chln_id" value="<?php echo $chln->chln_id + 1;  ?>" >
                    <?php } else { ?>
                        <input type="hidden" name="chln_id" value="<?php echo '01'; ?>" > 
                    <?php } ?>
                    <input type="hidden" name="item_id" value="<?php echo '01'; ?>" >
                    <input type="hidden" name="supp_id" value="<?php echo $pass->lifnr; ?>" >
                    <input type="hidden" name="chln_yr" value="<?php echo '2024'; ?>" >
                    <input type="hidden" name="cinv_yr" value="<?php echo '2024'; ?>" >
                    <tr class="bg-primary" id="rowChln">
                        <td>
                            <label for="">Challan Number</label>
                            <input type="text" name="chln_no" class="form-control" oninput="updtInvoice(this);" value="" required >
                        </td>
                        <td>
                            <label for="">Challan Date</label>
                            <input type="date" name="chln_dt" class="form-control" oninput="updtInvdate(this);" value="" required> 
                        </td>    
                        <td>
                            <label for="">Invoice Number</label>
                            <input type="text" name="cinv_no" class="form-control" value="" required>
                        </td>
                        <td>
                            <label for="">Invoice Date</label>
                            <input type="date" name="cinv_dt" class="form-control" value="" required>
                        </td>
                        <td>
                            <label for="">LR Number</label>
                            <input type="text" name="trlr_no" class="form-control" value="<?php echo "{$pass->zlrno}"; ?>" >
                        </td>
                        <td>
                            <label for="">LR Date</label>
                            <input type="date" name="trlr_dt" class="form-control" value="<?php echo "{$pass->zlrdt}"; ?>">
                        </td>
                        <td> 
                            <label for="">Invoice Value</label>
                            <input type="text"  name="cinv_vl" class="form-control text-right"  value=""  required>
                        </td>
                    </tr>
                    <tr class="bg-info" id="rowItem">
                        <td colspan="1">
                            <label for="">PO Item</label>
                            <select name="pitem" class="form-control"  onchange="getPOItem(this);" required>
                                <?php foreach($pitem as $pitm) { ?>
                                    <option value="<?php echo "{$pitm->ebeln}_{$pitm->ebelp}"; ?>"><?php echo "{$pitm->ebeln}_{$pitm->ebelp} [{$pitm->matnr}]_{$pitm->txz01}_{$pitm->netpr}"; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td colspan="1">
                            <label for="">Material</label>
                            <input type="text" name="matnr" class="form-control" value="<?php echo ""; ?>" readonly></td>
                        <td colspan="2">
                            <label for="">Description</label>
                            <input type="text" name="txz01" class="form-control" value="<?php echo ""; ?>" readonly></td>
                        <td colspan="1">
                            <label for="">PO Rate</label>
                            <input type="text" name="netpr" class="form-control text-right" value="" readonly></td>
                        <td colspan="1">
                            <label for="">Delivery Qty</label>
                            <input type="text" name="lfimg" class="form-control text-right" value="" required></td>
                        <td colspan="1">
                            <label for="">UoM</label><button type="submit" name="action"  class="btn btn-default float-right" value="newChln" ><img src="../assets/dist/img/add-record.png" style="width:20px;height:20px"></button> 
                            <input type="text" name="meins" class="form-control"  value="";  readonly>
                        </td>
                    </tr>
                </form>
            <?php } else { ?>
                <tr>
                    <td colspan="7"><form method="post" ><button type="submit" name="action" class="btn btn-primary float-right" value="addChln">Add Invoice</button></form></td>
                </tr>
            <?php } ?>

            </table>
        </div>
    </div>
</div>
