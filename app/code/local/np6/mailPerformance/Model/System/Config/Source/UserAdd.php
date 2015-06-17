<?php

class np6_mailPerformance_Model_System_Config_Source_UserAdd
{

    public function toOptionArray()
    {
            return array(
                array('value' => 0, 'label' => 'No'),
                array('value' => 1, 'label' => 'Only user who suscribe newsletter'),
                array('value' => 2, 'label' => 'Yes (all user)'),
            ); 
    }

}
