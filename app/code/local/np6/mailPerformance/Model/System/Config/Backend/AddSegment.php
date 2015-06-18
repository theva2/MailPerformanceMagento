<?php

require_once (__Dir__.'/../../../modelMP/Segment.class.php');

class np6_mailPerformance_Model_System_Config_Backend_AddSegment extends Mage_Core_Model_Config_Data
{
    public function save()
    {
        $name =  $this->_data['groups']['AddSegment_group']['fields']['name_field']['value'];
        $description =  $this->_data['groups']['AddSegment_group']['fields']['description_field']['value'];
        $date =  $this->_data['groups']['AddSegment_group']['fields']['expirationDate_field']['value'];

         $this->_data['groups']['AddSegment_group']['fields']['name_field']['value'] = "" ;
         $this->_data['groups']['AddSegment_group']['fields']['description_field']['value'] = "" ;
         $this->_data['groups']['AddSegment_group']['fields']['expirationDate_field']['value'] = "";
         
        $array = array(
            "type" => "static",
            "name" => $name,
            "description" => $description,
            "expiration" => $date
            );
       

        if(isset($name) && $name != "" && isset($description) && $description != "" && isset($date) && $date != "")
        {
            $segment = new segment($array);
            Mage::getSingleton('mailPerformance/api')->addSegment($segment);

            Mage::getSingleton('core/session')->addSuccess('Segment added'); 
        }
        else
        {
        	Mage::getSingleton('core/session')->addWarning('Fail to add semgent , all field needed '); 
        }
    }


}