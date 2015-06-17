<?php

require_once (__Dir__.'/MPAPI.php');
require_once (__Dir__.'/APIConnector.class.php');
require_once (__Dir__.'/ContactsConnector.class.php');
require_once (__Dir__.'/modelMP/Contact.class.php');
require_once (__Dir__.'/FieldsConnector.class.php');
require_once (__Dir__.'/modelMP/Field.class.php');
require_once (__Dir__.'/ValueListConnector.class.php');
require_once (__Dir__.'/modelMP/ValueList.class.php');
require_once (__Dir__.'/SegmentsConnector.class.php');
require_once (__Dir__.'/modelMP/Segment.class.php');
require_once (__Dir__.'/modelMP/Target.class.php');
require_once (__Dir__.'/TargetsConnector.class.php');



class np6_mailPerformance_Model_Api
{
	// rest to request
	var $rest_client;
	// var nedded 
	var $user_id;
	var $authKeys;
	// all connector use
	var $ContactsConnector;
	var $fields;
	var $value_list;
	var $segments;
	var $targets;


	public function __construct()
	{
		$this->rest_client = new np6_mailPerformance_Model_MPAPI();
		
		$key = Mage::getStoreConfig('mailPerformance_authentification_section/mailPerformance_group/apikey_field');

		if($key != null && $key != "")
		{
			$this->ValideAuthKey($key);
		}		

		$this->ContactsConnector = new ContactsConnector($this->rest_client);
		$this->fields = new FieldsConnector($this->rest_client);
		$this->value_list = new ValueListConnector($this->rest_client);
		$this->segments = new SegmentsConnector($this->rest_client);
		$this->targets = new TargetsConnector($this->rest_client);

	}

	public function ValideAuthKey($key)
	{
		$post_content = array(
			'method' => array(
				'name' => 'authenticateFromAutoLoginKey',
				'version' => 1
			),
			'parameters' => array(
				'alKey' => $key
			),
			'debug' => array(
				'forceSync' => true
			)
		);

		// create a POST request for the connection
		$this->rest_client->clear_cache = true;
		list($result, $error) = $this->rest_client->post('/api/auth', $post_content);
		$this->rest_client->clear_cache = false;

		if ($error)
		{
			$this->getIdentityInfo($result);
			$this->authkeys = null;
			return false;
		}

		// check connection and get user infos
		if ($result && APIConnector::verifNoError($result) == '')
		{
			if($this->getIdentityInfo($result))
			{
				$this->authkeys = $key;
				return true;
			}
		}
		$this->authkeys = null;
		return false;
	}

	private function getIdentityInfo($result)
	{
		if (isset($result['response']['identity']))
		{
			$this->user_id = $result['response']['identity']['contact'];
		 	return true;
		}
		$this->user_id = null;
		return false;
	}

	// get the contact information
	public function getContact()
	{	
		$this->ConnectIfNot();

		if(isset($this->user_id))
		{
			$contact = $this->ContactsConnector->getContactById($this->user_id);

			$arrayContact = array(
				'mail' => $contact->email, 
				'firstname' => $contact->identity->first_name,
				'lastname' => $contact->identity->last_name,
				'expire' => $contact->expire,
				);

			if($arrayContact['expire'] == null || $arrayContact['expire'] = "")
			{
				$arrayContact['expire'] = "no expiration";
			}

			return $arrayContact;
		}

		return false;	
	}

	// get all field from type pass in parameters, accept string or array
	public function getFieldType($type = null)
	{
		if($this->ConnectIfNot() == false)
		{
			Mage::log("getFieldType Abort : No connection");
			return;
		}
		if(isset($this->user_id) && $type != null)
		{
			if(! is_array($type))
			{
				$array = array($type);
			}
			else
			{
				$array = $type;
			}

			return $this->fields->getListFieldsByType($array) ;
		}

		return false;	
	}	

	// get all value form a field value list
	public function getValueList($fieldId)
	{
		if($this->ConnectIfNot() == false)
		{
			Mage::log("getValueList Abort : No connection");
			return;
		}

		if(isset($this->user_id) && $fieldId != null)
		{
			$field = $this->findFieldValueList($fieldId);
			if($field != null)
			{
				return $this->value_list->getValueListByField($field);
			}
		}

		return false;	


	}


	//get all the segement
	public function getSegments()
	{
		if($this->ConnectIfNot() == false)
		{
			Mage::log("getSegments Abort : No connection");
			return;
		}

		if(isset($this->user_id))
		{
			return $this->segments->getSegments();
		}

		return false;	


	}


	//find a field value liste with id
	public function findFieldValueList($fieldId)
	{
		$array =  array(TypeField::LISTE,TypeField::CHECKBOX);
		$allfields = $this->fields->getListFieldsByType($array) ;

		if($allfields == null)
		{
			return null;
		}

		foreach ($allfields as $field) {
			if($fieldId == $field->id)
			{
				if(isset($field->value_list))
				{
					return $field;
				}
			}
		}
		return null;
	}

	//test if connect
	public function isConnected()
	{
		if((isset($this->authkeys)) && $this->authkeys != "")
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	//if authkeys not set or différent from config, try to connect 
	public function ConnectIfNot()
	{
		if(!(isset($this->authkeys)) || ($this->authkeys != Mage::getStoreConfig('mailPerformance_authentification_section/mailPerformance_group/apikey_field')) )
		{
			return $this->ValideAuthKey(Mage::getStoreConfig('mailPerformance_authentification_section/mailPerformance_group/apikey_field'));
		}
		else
		{
			return $this->isConnected();
		}
	}

	//Return a boolean, true if all field Oblige and Unicity field are use, else false
	public function isAllUnitObligFieldUse($idarray)
	{

		if($this->ConnectIfNot() == false)
		{
			Mage::log("isAllUnitObligFieldUse Abort : No connection");
			return;
		}

		// on récupère tous les fields
		$arrayAllField =  array(TypeField::LISTE,TypeField::CHECKBOX,TypeField::EMAIL,TypeField::TEL,TypeField::TEXTAREA,TypeField::NUMERIC,TypeField::STRING,TypeField::DATE);
		$allFields = $this->fields->getListFieldsByType($arrayAllField) ;


		//on récupère les fields selectionné
		$arraySelectedField = array();
		foreach ($idarray as $key => $value) {
			$arraySelectedField[] = $this->fields->getFieldById($value);
		}

		$TabAll = $this->countObliUni($allFields);
		$TabSelected = $this->countObliUni($arraySelectedField);

		foreach ($TabAll as $id) {
			if(!in_array($id, $TabSelected))
			{
				return false;
			}
		}
		foreach ($TabSelected as $id) {
			if(!in_array($id, $TabAll))
			{
				return false;
			}
		}

		return true;
	}


	// return a array with id of unicity/obligatory field
	public function countObliUni (array $listField)
	{
		$arrayId = array();
		foreach ($listField as $field) {

			
			if(isset($field->is_unicity) && $field->is_unicity == TRUE)
			{
				$arrayId[] = $field->id;
			}
			else if(isset($field->is_obligatory) && $field->is_obligatory == TRUE)
			{
				$arrayId[] = $field->id;
			}
		}
		
		return $arrayId;
	}


	//Add a new target in MP, need a array of field and magento id
	public function CreateNewTarget(array $TargetInformation,$id_magento)
	{
		if($this->ConnectIfNot() == false)
		{
			Mage::log("Target Creation Abort : No connection");
			return;
		}

		//tableau a envoyer a l'API
		$send_array = array();

		// on récupère tous les fields
		$arrayAllField =  array(TypeField::LISTE,TypeField::CHECKBOX,TypeField::EMAIL,TypeField::TEL,TypeField::TEXTAREA,TypeField::NUMERIC,TypeField::STRING,TypeField::DATE);
		$allFields = $this->fields->getListFieldsByType($arrayAllField) ;


		foreach ($allFields as $field) {
			if(isset($TargetInformation[$field->id]))
			{
				$send_array[$field->id] = $TargetInformation[$field->id];
			}
			else
			{
				if($field->type == 'multipleSelectList')
				{
					$send_array[$field->id] = array();
				}
				else
				{
					$send_array[$field->id] = null;
				}
			}
		}

		$target_result = $this->targets->createTarget($send_array);

		if($target_result)
		{
			$contact = Mage::getModel('mailPerformance/mailPerformance');
	      	$contact->addData(array('id_magento' => $id_magento, 'id_mailperf' => (string)$target_result->id));  
	      	$contact->save();
		}
	}

	//Update an existing target
	public function UpdateTarget(array $TargetInformation,$id_mp)
	{
		if($this->ConnectIfNot() == false)
		{
			Mage::log("Target UpdateTarget Abort : No connection");
			return;
		}

		//tableau a envoyer a l'API
		$send_array = array();

		// on récupère tous les fields
		$arrayAllField =  array(TypeField::LISTE,TypeField::CHECKBOX,TypeField::EMAIL,TypeField::TEL,TypeField::TEXTAREA,TypeField::NUMERIC,TypeField::STRING,TypeField::DATE);
		$allFields = $this->fields->getListFieldsByType($arrayAllField) ;


		foreach ($allFields as $field) {
			if(isset($TargetInformation[$field->id]))
			{
				$send_array[$field->id] = $TargetInformation[$field->id];
			}
			else
			{
				if($field->type == 'multipleSelectList')
				{
					$send_array[$field->id] = array();
				}
				else
				{
					$send_array[$field->id] = null;
				}
			}
		}

		 Mage::log("Customer Update, id mp = ".$id_mp);
		 Mage::log("Customer Update, send array = ".$send_array);

		$result = $this->targets->updateTargetFromValues($id_mp, $send_array);

		Mage::log("Customer Update, result = ".$result);

	}


}