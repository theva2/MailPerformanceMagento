<?php

// needed to extract data from table

class np6_mailPerformance_Model_Mysql4_mailPerformance extends Mage_Core_Model_Mysql4_Abstract
{
     public function _construct()
     {
         $this->_init("mailPerformance/mailPerformance", "id_magento");
         $this->_isPkAutoIncrement = false;
     }
}
