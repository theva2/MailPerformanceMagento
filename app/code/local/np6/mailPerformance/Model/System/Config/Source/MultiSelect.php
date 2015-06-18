<?php

class np6_mailPerformance_Model_System_Config_Source_MultiSelect
{

    protected $_account_details = FALSE;


    public function __construct()
    {
        if (!$this->_account_details) {
            $this->_account_details = Mage::getSingleton('mailPerformance/api')->getFieldType("multipleSelectList"); 
        }
    }



    public function toOptionArray()
    {
         if ($this->_account_details != FALSE && $this->_account_details != "" ) {


            $array =  array(array('value' => 0, 'label' => 'no bind'));
        

           foreach ($this->_account_details as $field) {


                if($field->is_obligatory == TRUE)
                {
                    $array[] =  array('value' => $field->id , 'label' => $field->name."  (obligatory !)");
                }
                else if($field->is_unicity == TRUE)
                {
                    $array[] =  array('value' => $field->id , 'label' => $field->name."  (Unicity !)");
                }
                else
                {
                    $array[] =  array('value' => $field->id , 'label' => $field->name);
                }

            }

            return $array;
        } else {
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