<?php

// needed to extract data from table

class np6_mailPerformance_Model_Mysql4_mailPerformance_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
 {
     public function _construct()
     {
         parent::_construct();
         $this->_init("mailPerformance/mailPerformance");
     }
}