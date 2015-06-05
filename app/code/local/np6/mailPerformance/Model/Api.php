<?php

require_once (__Dir__.'/MPAPI.php');
require_once (__Dir__.'/APIConnector.class.php');
require_once (__Dir__.'/ContactsConnector.class.php');
require_once (__Dir__.'/modelMP/Contact.class.php');
require_once (__Dir__.'/FieldsConnector.class.php');
require_once (__Dir__.'/modelMP/Field.class.php');
require_once (__Dir__.'/ValueListConnector.class.php');
require_once (__Dir__.'/modelMP/ValueList.class.php');

class np6_mailPerformance_Model_Api
{
	var $rest_client;
	var $ContactsConnector;
	var $user_id;
	var $authKeys;
	var $fields;
	var $value_list;

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
			return;
		}

		// check connection and get user infos
		if ($result && APIConnector::verifNoError($result) == '')
		{
			if($this->getIdentityInfo($result))
			{
				$this->authkeys = $key;
				return;
			}
		}
		$this->authkeys = null;
		return;
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


	public function getFieldType($type = null)
	{
		$this->ConnectIfNot();

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

	public function getValueList($fieldId)
	{
		$this->ConnectIfNot();

		if(isset($this->user_id) && $fieldId != null)
		{
			$field = $this->findField($fieldId);
			if($field != null)
			{
				return $this->value_list->getValueListByField($field);
			}
		}

		return false;	


	}

	public function findField($fieldId)
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

	public function ConnectIfNot()
	{
		if(!(isset($this->authkeys)) || ($this->authkeys != Mage::getStoreConfig('mailPerformance_authentification_section/mailPerformance_group/apikey_field')) )
		{
			$this->ValideAuthKey(Mage::getStoreConfig('mailPerformance_authentification_section/mailPerformance_group/apikey_field'));
		}
	}

	public function isAllUnitObligFieldUse()
	{

		$this->ConnectIfNot();

		// on récupère tous les fields
		$arrayAllField =  array(TypeField::LISTE,TypeField::CHECKBOX,TypeField::EMAIL,TypeField::TEL,TypeField::TEXTAREA,TypeField::NUMERIC,TypeField::STRING,TypeField::DATE);

		$allFields = $this->fields->getListFieldsByType($arrayAllField) ;

		// on récupère tous les fields selectionné
		$idarray = array(
							'id' => Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/id_field'),
							'lastname' => Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/lastname_field'),
							'firstname' => Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/firstname_field'),
							'gender' => Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/gender_field'),
							'email' => Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/email_field'),
							'newsletterDate' => Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/suscriptionDate_field'),
							'birthday' => Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/birthday_field'),
							'ThirdPArtyOffers' => Mage::getStoreConfig('mailPerformance_dataBinding_section/DataBinding_group/optin_field'),
						);

	

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


}