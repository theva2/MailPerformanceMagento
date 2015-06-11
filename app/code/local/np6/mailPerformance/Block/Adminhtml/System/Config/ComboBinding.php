 <?php

/* block to print distinctValues combobox  */
class np6_mailPerformance_Block_Adminhtml_System_Config_ComboBinding
    extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        //all the possibility
        $values = $element->getValues();
        //current selected
        $selectedValue = $element->getValue();

        $html = '<select  id="'.$element->getHtmlId().'" name="'.$element->getName().'" >';

        foreach ($values as $val) {

            $selected = "";
            if ($val['value'] == $selectedValue)
            {
                $selected= ' selected="selected"';
            }


            $color = "";
            if(strpos($val['label'],'!'))
            {
            	$color = 'style="color:#FF0000"';
            }
            else
            {
            	$color = 'style="color:#000000"';
            }


            $html .= "<option value=\"{$val['value']}\" ".$selected." ".$color.">{$val['label']}</option>";
        }
        $html .= '</select>';

        return $html;
    }
}
