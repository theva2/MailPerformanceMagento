<?php

class np6_mailPerformance_Adminhtml_IndexController extends Mage_Adminhtml_Controller_Action
{
  public function indexAction()
  {
          $this->loadLayout();
          $this->renderLayout();
  }

  public function saveAction()
	{
	    //on recuperes les données envoyées en POST
	    $id_mp = $this->getRequest()->getPost('id_mp');
	    $id_mag = $this->getRequest()->getPost('id_mag');
	    //on verifie que les champs ne sont pas vide
	    if(isset($id_mp)&&($id_mp!='') && isset($id_mag)&&($id_mag!=''))
	   {
	      //on cree notre objet et on l'enregistre en base
	      $contactconnector = Mage::getModel('mailPerformance/mailPerformance');
	      $contactconnector->setData('id_magento', $id_mag);
	      $contactconnector->setData('id_mailperf', $id_mp);

	      Mage::log("id_magento (test) = ".$id_mag);
	      Mage::log("id_mailperf (test) = ".$id_mp);

	      try
	      {
	      	$contactconnector->save();
	      }
	      catch(Exception $e)
	      {
	      	 //test fonction message warning
	      	 Mage::getSingleton("adminhtml/session")->addWarning("ID magento déjà utiliser ou fail de co !!");
	      }
	      //test fonction message success
         Mage::getSingleton("adminhtml/session")->addSuccess("Cool Ca marche !!");
	   }
	   //on redirige l’utilisateur vers la méthode index du controller indexController
	   $this->_redirect('adminMP/adminhtml_index');
	}
}
