<?php

// needed to extract data from table

class np6_mailPerformance_Model_mailPerformance extends Mage_Core_Model_Abstract
{
     public function _construct()
     {
         parent::_construct();
         $this->_init("mailPerformance/mailPerformance");
     }
}
