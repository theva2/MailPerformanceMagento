<?php


//block de test pour ajouter des valeur manuellement dans la base de donnée
class np6_mailPerformance_Block_Monblock extends Mage_Core_Block_Template
{
     public function methodblock()
     {
          //on initialize la variable
        $retour='';

        $collection = Mage::getModel("mailPerformance/mailPerformance")->getCollection()
                                 ->setOrder("id_magento","asc");

        foreach($collection as $data)
        {
             $retour .= 'id magento = '. $data->getData("id_magento").' and id mailPerf ='.$data->getData("id_mailperf")
                     ."<br />";
         }
         
         return $retour;

     }
}