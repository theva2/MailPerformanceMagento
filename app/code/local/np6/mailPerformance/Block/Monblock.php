<?php
class np6_mailPerformance_Block_Monblock extends Mage_Core_Block_Template
{
     public function methodblock()
     {

          //on initialize la variable
        $retour='';

  		$collection = Mage::getModel("mailPerformance/mailPerformance")->getCollection()
                                 ->setOrder("id_np6_mailPerformance","asc");

        foreach($collection as $data)
        {
             $retour .= $data->getData("nom").' '.$data->getData("prenom")
                     .' '.$data->getData("telephone")."<br />";
         }
         
        return $retour;

     }
}
 