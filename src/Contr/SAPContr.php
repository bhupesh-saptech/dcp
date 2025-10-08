<?php
    namespace Contr;
    use Model\SAP;
    class SAPContr extends SAP {

        public function __construct() {
            
        }
        public function createSlsGroup($rqst) {
            $this->setSlsGroup($rqst);

        }
        public function createCustomer($rqst) {
            $this->setCustomer($rqst);

        }
        public function createSOItem($rqst) {
            $this->setSOItem($rqst);

        }
        public function createSOSline($rqst) {
            $this->setSOSline($rqst);

        }
        public function createShipment($rqst) {
            $this->setShipment($rqst);

        }
        public function createDelivery($rqst) {
            $this->setDelivery($rqst);
        }
        public function createGoodsMvt($rqst) {
            $this->setGoodsMvt($rqst);

        }
        public function createInvoice($rqst) {
            $this->setInvoice($rqst);
        }
        public function createPlant($rqst) {
            $this->setPlant($rqst);
        }
        public function createStrLoc($rqst) {
            $this->setStrLoc($rqst);
        }
    }