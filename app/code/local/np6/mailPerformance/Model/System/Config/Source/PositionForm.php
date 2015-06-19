<?php

class np6_mailPerformance_Model_System_Config_Source_PositionForm
{

    public function toOptionArray()
    {
        return array(

            array("value" => 0, "label" => "Top"),
            array("value" => 1, "label" => "Down"),
            array("value" => 2, "label" => "Home"),
            array("value" => 3, "label" => "Column left"),
            array("value" => 4, "label" => "Column rigth"),
            array("value" => 5, "label" => "Column product left"),
            array("value" => 6, "label" => "Column product right"),
            array("value" => 7, "label" => "Under product"),
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