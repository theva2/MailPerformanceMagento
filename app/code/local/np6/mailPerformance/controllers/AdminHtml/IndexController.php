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

	public function csvAction()
	{
		Mage::log("Csv clic");

		//get the columns name
		$column_header_name = array();

      /*  $column_header_name[] = explode(" ", Mage::getModel('mailPerformance/system_config_source_numeric')->searchArray(Mage::getModel('mailPerformance/system_config_source_numeric')->toOptionArray(),Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/id_custormer_field')))[0];
        $column_header_name[] = explode(" ", Mage::getModel('mailPerformance/system_config_source_combo_AllText')->searchArray(Mage::getModel('mailPerformance/system_config_source_combo_AllText')->toOptionArray(),Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/firstname_field')))[0];
        $column_header_name[] = explode(" ", Mage::getModel('mailPerformance/system_config_source_combo_AllText')->searchArray(Mage::getModel('mailPerformance/system_config_source_combo_AllText')->toOptionArray(),Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/lastname_field')))[0];
        $column_header_name[] = explode(" ", Mage::getModel('mailPerformance/system_config_source_combo_allList')->searchArray(Mage::getModel('mailPerformance/system_config_source_combo_allList')->toOptionArray(),Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/gender_field')))[0];
        $column_header_name[] = explode(" ", Mage::getModel('mailPerformance/system_config_source_email')->searchArray(Mage::getModel('mailPerformance/system_config_source_email')->toOptionArray(),Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/email_field')))[0];
        $column_header_name[] = explode(" ", Mage::getModel('mailPerformance/system_config_source_date')->searchArray(Mage::getModel('mailPerformance/system_config_source_date')->toOptionArray(),Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/birthday_field')))[0];
		*/

       $arrayAllField = array('email','phone','textArea','numeric','textField','date','singleSelectList','multipleSelectList','singleSelectList');
       $allFields = Mage::getSingleton('mailPerformance/api')->getFieldType($arrayAllField);

       foreach ($allFields as $key => $value) {
      		$column_header_name[] = $value->name;
       }


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
        	
        	foreach ($allFields as $key => $value) {
      			$row[] = $this->FieldValue($target,$value->id);
      		 }
			
			/*
        	$row[] = $this->FieldValue($target,Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/id_custormer_field'));
        	$row[] = $this->FieldValue($target,Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/firstname_field'));
        	$row[] = $this->FieldValue($target,Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/lastname_field'));
        	$row[] = $this->FieldValue($target,Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/gender_field'));
        	$row[] = $this->FieldValue($target,Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/email_field'));
        	$row[] = $this->FieldValue($target,Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/birthday_field'));
			*/
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
