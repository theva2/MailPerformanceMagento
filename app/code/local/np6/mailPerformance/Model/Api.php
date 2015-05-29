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
			echo("<script>console.log(\"ValideAuthKey:Error\")</script>");
			$this->getIdentityInfo($result);
			$this->authkey = null;
			return;
		}

		// check connection et get user infos
		if ($result && APIConnector::verifNoError($result) == '')
		{
			echo("<script>console.log(\"ValideAuthKey:trust\")</script>");
			echo("<script>console.log(".$result.")</script>");
			$this->getIdentityInfo($result);
			return;
		}

		$this->authkey = null;
		return;
	}

	private function getIdentityInfo($result)
	{
		if (isset($result['response']['identity']))
		{
			echo("<script>console.log(\"getIdentityInfo\")</script>");
				$this->user_id = $result['response']['identity']['contact'];
		}
	}


	public function getContact()
	{
		if(isset($this->user_id))
		{
			echo("<script>console.log(\"getContact\")</script>");


			$contact = $this->ContactsConnector->getContactById($this->user_id);

			return $arrayName = array('mail' => $contact->email );
		}

		return false;	
	}

}