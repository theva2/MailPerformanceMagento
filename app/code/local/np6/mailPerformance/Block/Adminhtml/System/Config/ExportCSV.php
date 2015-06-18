<?php


class np6_mailPerformance_Block_Adminhtml_System_Config_ExportCSV extends Mage_Adminhtml_Block_System_Config_Form_Field
{

    /**
     * Set template to itself
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()) {
            $this->setTemplate('mailPerformance/ExportCSV.phtml');
        }
        return $this;
    }

    /**
     * Unset some non-related element parameters
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

     protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $originalData = $element->getOriginalData();

        $label = $originalData['button_label'];

        $this->addData(array(
            'button_label' => $label,
            'html_id' => $element->getHtmlId(),
        ));
        return $this->_toHtml();
    }

   


}
