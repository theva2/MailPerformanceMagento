<?php
	
class np6_mailPerformance_Model_System_Config_Backend_Databinding extends Mage_Core_Model_Config_Data
{
    protected function _afterSave()
    {
        $idarray = array();
        $idarray[] =  $this->_data['groups']['DataBinding_group']['fields']['id_custormer_field']['value'];
        $idarray[] =  $this->_data['groups']['DataBinding_group']['fields']['lastname_field']['value'];
        $idarray[] =  $this->_data['groups']['DataBinding_group']['fields']['firstname_field']['value'];
        $idarray[] =  $this->_data['groups']['DataBinding_group']['fields']['gender_field']['value'];
        $idarray[] =  $this->_data['groups']['DataBinding_group']['fields']['email_field']['value'];
        $idarray[] =  $this->_data['groups']['DataBinding_group']['fields']['suscriptionDate_field']['value'];
        $idarray[] =  $this->_data['groups']['DataBinding_group']['fields']['birthday_field']['value'];
        $idarray[] =  $this->_data['groups']['DataBinding_group']['fields']['optin_field']['value'];
        


        if(Mage::getSingleton('mailPerformance/api')->isAllUnitObligFieldUse($idarray))
        {
            Mage::getSingleton('core/session')->addSuccess('No Problem'); 
        }
        else
        {
        	Mage::getSingleton('core/session')->addWarning('All unicity or obligatory aren\'t bind'); 

        }
         return parent::_afterSave();
    }


}