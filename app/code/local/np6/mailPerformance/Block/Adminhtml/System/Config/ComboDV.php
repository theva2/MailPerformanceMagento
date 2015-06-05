 <?php

/* block to print distinctValues combobox  */
class np6_mailPerformance_Block_Adminhtml_System_Config_ComboDV
    extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        //all the possibility
        $values = $element->getValues();
        //current selected
        $value = $element->getValue();

        $html = '<select  id="'.$element->getHtmlId().'" name="'.$element->getName().'" style="margin-left : 30px; width : 250px;">';

        foreach ($values as $val) {

            $selected = "";
            if ($val['value'] == $value['value'])
            {
                $selected= ' selected="selected"';
            }

            $html .= "<option value=\"{$val['value']}\" ".$selected."> {$val['label']}</option>";
        }
        $html .= '</select>';

        return $html;
    }
}


