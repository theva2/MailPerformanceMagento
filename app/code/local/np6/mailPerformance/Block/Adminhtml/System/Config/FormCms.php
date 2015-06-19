<?php

class np6_mailPerformance_Block_Adminhtml_System_Config_FormCms extends Mage_Adminhtml_Block_System_Config_Form_Field
{
     protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {

        $html = '<p> A implémenter pour afficher les page cms créer  avec  : nom , lien vers la modification du cms , lien vers la page cms</p>';

       return $html;
    }
}