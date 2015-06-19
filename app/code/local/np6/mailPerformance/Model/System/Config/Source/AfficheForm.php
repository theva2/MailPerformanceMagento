<?php

class np6_mailPerformance_Model_System_Config_Source_AfficheForm
{

    public function toOptionArray()
    {

            return array(
                array('value' => 0, 'label' => 'Dans une Frame'),
                array('value' => 1, 'label' => 'Sur une page dédié'),
            ); 


    }

    public function searchArray($myarray, $value) {
          foreach ($myarray as $item) {
            if ($item['value'] == $value)
               return $item['label'];
          }
         return false;
      }

}