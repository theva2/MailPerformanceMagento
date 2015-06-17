<?php

class np6_mailPerformance_Model_System_Config_Source_Segment
{

    protected $_account_details = FALSE;


    public function __construct()
    {
        if (!$this->_account_details) {
           $this->_account_details = Mage::getSingleton('mailPerformance/api')->getSegments(); 
        }
    }



    public function toOptionArray()
    {
         if ($this->_account_details != FALSE && $this->_account_details != "" ) {


           $array =  array(array('value' => 0, 'label' => 'no bind'));
        
           foreach ($this->_account_details as $field) {
                $array[] =  array('value' => $field->id , 'label' => $field->name);
            }

            return $array;
        } else {
            return  $array =  array(array('value' => 0, 'label' => 'no bind found'));
        }
    }



}