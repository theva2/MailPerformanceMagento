<?php

const DELIMITER = ';';

//index controleur for admin panel
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

	public function csvAction()
	{
		Mage::log("Csv clic");

		//get the columns name
		$column_header_name = array();
        $column_header_name[] = explode(" ", Mage::getModel('mailPerformance/system_config_source_numeric')->searchArray(Mage::getModel('mailPerformance/system_config_source_numeric')->toOptionArray(),Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/id_custormer_field')))[0];
        $column_header_name[] = explode(" ", Mage::getModel('mailPerformance/system_config_source_combo_AllText')->searchArray(Mage::getModel('mailPerformance/system_config_source_combo_AllText')->toOptionArray(),Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/firstname_field')))[0];
        $column_header_name[] = explode(" ", Mage::getModel('mailPerformance/system_config_source_combo_AllText')->searchArray(Mage::getModel('mailPerformance/system_config_source_combo_AllText')->toOptionArray(),Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/lastname_field')))[0];
        $column_header_name[] = explode(" ", Mage::getModel('mailPerformance/system_config_source_combo_allList')->searchArray(Mage::getModel('mailPerformance/system_config_source_combo_allList')->toOptionArray(),Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/gender_field')))[0];
        $column_header_name[] = explode(" ", Mage::getModel('mailPerformance/system_config_source_email')->searchArray(Mage::getModel('mailPerformance/system_config_source_email')->toOptionArray(),Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/email_field')))[0];
        $column_header_name[] = explode(" ", Mage::getModel('mailPerformance/system_config_source_date')->searchArray(Mage::getModel('mailPerformance/system_config_source_date')->toOptionArray(),Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/birthday_field')))[0];

        //start file
		$output = fopen('php://output', 'w');

		// add http header to download the file
		header('Content-type: text/csv');
		header('Content-Type: application/force-download; charset=UTF-8');
		header('Cache-Control: no-store, no-cache');
		header('Content-disposition: attachment; filename="ExportPrestaNp6.csv"');

		// add the csv header
		fputcsv($output, $column_header_name, DELIMITER);

		//get id table for know id mp 
        $mperfTable = Mage::getModel('mailPerformance/mailPerformance')->getCollection()->getData();

        $idArray = array();
        foreach ($mperfTable as $key => $value) {
        	$idArray[] = $value['id_mailperf'];
        }

        foreach ($idArray as $key => $value) {

        	$target = Mage::getSingleton('mailPerformance/api')->getTargetWithId($value);

        	$row = array();
        	$row[] = $this->FieldValue($target,Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/id_custormer_field'));
        	$row[] = $this->FieldValue($target,Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/firstname_field'));
        	$row[] = $this->FieldValue($target,Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/lastname_field'));
        	$row[] = $this->FieldValue($target,Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/gender_field'));
        	$row[] = $this->FieldValue($target,Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/email_field'));
        	$row[] = $this->FieldValue($target,Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/birthday_field'));

			fputcsv($output, $row, DELIMITER);
        }
        die();

		//redirect to orginal position
		header('Location: '.Mage::helper("adminhtml")->getUrl("adminhtml/system_config/edit/section/mailPerformance_action_section"));
		die();
	}


	// helper //

	private function FieldValue($target, $idField)
	{
		foreach ($target->fields as $key => $value) {
			if($key == $idField)
			{
				MAge::log("key = ".$key." ; idfield = ".$idField);
				return $value;
			}
		}
		MAge::log("field id = ".$idField." does not match");
		return "";
	}

}
