<?php
	
class np6_mailPerformance_Model_System_Config_Backend_Serialized extends Mage_Core_Model_Config_Data
{
    protected function _afterSave()
    {
        if(Mage::getSingleton('mailPerformance/api')->isAllUnitObligFieldUse())
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