<?php

require_once (__Dir__.'/MPAPI.php');
require_once (__Dir__.'/APIConnector.class.php');
require_once (__Dir__.'/ContactsConnector.class.php');
require_once (__Dir__.'/modelMP/Contact.class.php');

class np6_mailPerformance_Model_Api
{
	var $rest_client;
	var $ContactsConnector;
	var $user_id;
	var $authKeys;

	public function __construct()
	{
		$this->rest_client = new np6_mailPerformance_Model_MPAPI();
		$this->ContactsConnector = new ContactsConnector($this->rest_client);


		$key = Mage::getStoreConfig('mailPerformance_authentification_section/mailPerformance_group/apikey_field');

		if($key != null && $key != "")
		{
			$this->ValideAuthKey($key);
		}		
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
			//echo("<script>console.log(".$result.")</script>");
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
		if(!(isset($this->authkeys)) || ($this->authkeys != Mage::getStoreConfig('mailPerformance_authentification_section/mailPerformance_group/apikey_field')) )
		{
			$this->ValideAuthKey(Mage::getStoreConfig('mailPerformance_authentification_section/mailPerformance_group/apikey_field'));
		}

		if(isset($this->user_id))
		{
			$contact = $this->ContactsConnector->getContactById($this->user_id);

			return $arrayName = array(
				'mail' => $contact->email, 
				'firstname' => $contact->identity->first_name,
				'lastname' => $contact->identity->last_name,
				'expire' => $contact->expire,
				);
		}

		return false;	
	}

	public function isConnected()
	{
		if($authKeys != null || $authKeys != " ")
		{
			return true;
		}
		else
		{
			return false;
		}
	}



}