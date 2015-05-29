<?php

class np6_mailPerformance_Model_System_Config_Source_Account
{

    /**
     * Account details storage
     *
     * @access protected
     * @var bool|array
     */
    protected $_account_details = FALSE;

    /**
     * Set AccountDetails on class property if not already set
     *
     * @return void
     */
    public function __construct()
    {
        if (!$this->_account_details) {
            $this->_account_details = Mage::getSingleton('mailPerformance/api')
                ->getContact();
        }
    }

    /**
     * Return data if API key is entered
     *
     * @return array
     */
    public function toOptionArray()
    {
        if (is_array($this->_account_details)) {
            return array(
                array('value' => 0, 'label' => 'mail:' . ' ' . $this->_account_details['mail']),
            );
        } else {
            return array(array('value' => 0, 'label' => '--- Enter your API KEY first ---'));
        }
    }

}
