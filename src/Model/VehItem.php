<?php
    namespace Model;
    use Model\Conn;
    class VehItem extends Conn {
       public $pass_id = "";
       public $chln_id = "";
       public $item_id = "";
       public $ebeln   = "";
       public $ebelp   = "";
       public $matnr   = "";
       public $txz01   = "";
       public $netpr   = "";
       public $lfimg   = "";
       public $vrkme   = "";
       public $netwr   = "";      
        protected function setVehItem($rqst) {
            $conn = $this->connect();
            $sqls = "insert ignore 
                       into veh_item (pass_id,
                                      chln_id,
                                      item_id,
                                      ebeln,
                                      ebelp,
                                      matnr,
                                      txz01,
                                      werks,
                                      lgort,
                                      netpr,
                                      lfimg,
                                      vrkme,
                                      netwr,
                                      zpmat,
                                      zpqty,
                                      zpuom,
                                      zcomm)
                            values  ( :pass_id,
                                      :chln_id,
                                      :item_id,
                                      :ebeln,
                                      :ebelp,
                                      :matnr,
                                      :txz01,
                                      :werks,
                                      :lgort,
                                      :netpr,
                                      :lfimg,
                                      :vrkme,
                                      :netwr,
                                      :zpmat,
                                      :zpqty,
                                      :zpuom,
                                      :zcomm)";
            $stmt = $conn->prepare($sqls);

            $rset = $stmt->execute([   ':pass_id'   => $rqst->pass_id,
                                       ':chln_id'   => $rqst->chln_id,
                                       ':item_id'   => $rqst->item_id,
                                       ':ebeln'     => $rqst->ebeln,
                                       ':ebelp'     => $rqst->ebelp,
                                       ':matnr'     => $rqst->matnr,
                                       ':txz01'     => $rqst->txz01,
                                       ':werks'     => $rqst->werks,
                                       ':lgort'     => $rqst->lgort,
                                       ':netpr'     => $rqst->netpr,
                                       ':lfimg'     => $rqst->lfimg,
                                       ':vrkme'     => $rqst->vrkme,
                                       ':netwr'     => $rqst->netwr,
                                       ':zpmat'     => $rqst->zpmat,
                                       ':zpqty'     => $rqst->zpqty,
                                       ':zpuom'     => $rqst->zpuom,
                                       ':zcomm'     => $rqst->zcomm  ]  );
            $sqls = "update veh_chln 
                         set cinv_vl = ( select sum(netwr) as value 
                                           from veh_item 
                                          where pass_id = ?
                                            and chln_id = ? )
                       where pass_id = ?
                         and chln_id = ?";
            $stmt = $conn->prepare($sqls);
            $rset = $stmt->execute( array($rqst->pass_id,
                                          $rqst->chln_id,
                                          $rqst->pass_id,
                                          $rqst->chln_id));
           
            $sqls = "update veh_pass 
                         set netwr = ( select sum(netwr) as value 
                                         from veh_item 
                                        where pass_id = ? )
                       where pass_id = ?";
            $stmt = $conn->prepare($sqls);
            $rset = $stmt->execute(array($rqst->pass_id,
                                         $rqst->pass_id));
            $conn = null;
        }
        protected function modVehItem($rqst) {
            $conn = $this->connect();
            $rqst->ebeln = substr($rqst->pitem,0,10);
            $rqst->ebelp = substr($rqst->pitem,11);

            $sqls = "update veh_item set    ebeln = :ebeln,
                                            ebelp = :ebelp,
                                            matnr = :matnr,
                                            txz01 = :txz01,
                                            werks = :werks,
                                            lgort = :lgort,
                                            netpr = :netpr,
                                            lfimg = :lfimg,
                                            vrkme = :vrkme,
                                            netwr = :netwr,
                                            zpmat = :zpmat,
                                            zpqty = :zpqty,
                                            zpuom = :zpuom,
                                            zcomm = :zcomm
                                    where pass_id = :pass_id
                                      and chln_id = :chln_id
                                      and item_id = :item_id ";
                            

            $stmt = $conn->prepare($sqls);
            $rset = $stmt->execute([    ':ebeln'   => $rqst->ebeln,
                                        ':ebelp'   => $rqst->ebelp,
                                        ':matnr'   => $rqst->matnr,
                                        ':txz01'   => $rqst->txz01,
                                        ':werks'   => $rqst->werks,
                                        ':lgort'   => $rqst->lgort,
                                        ':netpr'   => $rqst->netpr,
                                        ':lfimg'   => $rqst->lfimg,
                                        ':vrkme'   => $rqst->vrkme,
                                        ':netwr'   => $rqst->netwr,
                                        ':zpmat'   => $rqst->zpmat,
                                        ':zpqty'   => $rqst->zpqty,
                                        ':zpuom'   => $rqst->zpuom,
                                        ':zcomm'   => $rqst->zcomm,
                                        ':pass_id' => $rqst->pass_id,
                                        ':chln_id' => $rqst->chln_id,
                                        ':item_id' => $rqst->item_id]);
            $sqls = "update veh_chln 
                        set cinv_vl = ( select sum(netwr) as value 
                                            from veh_item 
                                            where pass_id = ?
                                              and chln_id = ? )
                        where pass_id = ?
                          and chln_id = ?";
            $stmt = $conn->prepare($sqls);
            $rset = $stmt->execute(array( $rqst->pass_id,
                                          $rqst->chln_id,
                                          $rqst->pass_id,
                                          $rqst->chln_id));
            $sqls = "update veh_pass 
                         set netwr = ( select sum(netwr) as value 
                                            from veh_item 
                                           where pass_id = :pass_id )
                       where pass_id = :pasx_id";
            $stmt = $conn->prepare($sqls);
            $rset = $stmt->execute(array(':pass_id' => $rqst->pass_id,
                                          ':pasx_id'=> $rqst->pass_id));
            $conn = null;
        }
        protected function delVehItem($rqst) {
            $conn = $this->connect();
            $sqls = "delete from veh_item 
                      where pass_id = :pass_id
                        and chln_id = :chln_id
                        and item_id = :item_id";
            $stmt = $conn->prepare($sqls);
            $rset = $stmt->execute([ ':pass_id' => $rqst->pass_id,
                                     ':chln_id' => $rqst->chln_id,
                                     ':item_id' => $rqst->item_id]);
            $conn = null;
        }

    }
?>