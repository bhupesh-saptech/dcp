<?php
    namespace Contr;
    use \stdClass;
    use Model\VehPass;
    
    class VehPassContr extends VehPass {
        private $pass;
        private $list;
        public function __construct() {
            
        }
        public function readCount($query,$param = []) {
            $this->list = $this->getCount($query,$param);
            return $this->list;
        }
        public function createPass($rqst) {
            $pass_id = $this->setPass($rqst);
            $query   = "select * from veh_data where pass_id = ?";
            $param   = array($pass_id);
            $pass    = $this->execQuery($query,$param,1);
            return $pass;
        }
        public function createVehSupp($pass_id,$supp_id) {
            $this->setVehSupp($pass_id,$supp_id);
        }
        public function createVlog($rqst) {
            $this->setVlog($rqst);
        }
        public function updtQAD($query,$param = []) {
            $this->modQAD($query,$param);
        }
        public function updtVPStatus($rqst) {
            $this->modVPStatus($rqst); 
            $rqst->vlog_id = $this->getNextNo($rqst);
            $this->setVLog($rqst);
        }
        public function modifyPass($rqst) {
            $pass = $this->modPass($rqst);
            return $pass;
            
        }
    }