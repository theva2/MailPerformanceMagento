<?php

require_once (__Dir__.'/Request.php');


class np6_mailPerformance_Model_Api
{
	var $authkey;
	var $rest_client;

	public function __construct()
	{
		$this->rest_client = new RequestRest();

		// check arguments
		$ctp = func_num_args();
		$args = func_get_args();
		if ($ctp == 1)
		{
			if (Tools::strlen($args[0]) == 112)
			{
				$this->ValideAuthKey($args[0])
			}
			else
			{
				$this->authkey = null;
			}
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
				'alKey' => $this->auto_login_key
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
			$this->authkey = null;
			return;
		}

		// check connection et get user infos
		if ($result && APIConnector::verifNoError($result) == '')
		{
			$this->authkey = $key;
			return;
		}

		$this->authkey = null;
		return;
	}


}