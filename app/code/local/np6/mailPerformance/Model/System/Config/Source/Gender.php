<?php

class np6_mailPerformance_Model_System_Config_Source_Gender
{



  protected $_account_details = FALSE;


    public function __construct()
    {
        if (!$this->_account_details) {
            $this->_account_details = Mage::getSingleton('mailPerformance/api')->getValueList(Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/gender_field')); 
        }
    }


    public function toOptionArray()
    {
        if ($this->_account_details != FALSE && $this->_account_details != "" ) 
        {

            $array =  array(array('value' => 0, 'label' => 'no bind'));

            foreach ($this->_account_details->values as $field) 
            {
                $array[] =  array('value' => $field['index'] , 'label' => $field['value']);
            }
            return $array;
        } 
        else 
        {
            return  $array =  array(array('value' => 0, 'label' => 'no bind found'));
        }
    }

    public function searchArray($myarray, $value) {
          foreach ($myarray as $item) {
            if ($item['value'] == $value)
               return $item['label'];
          }
         return false;
      }

}