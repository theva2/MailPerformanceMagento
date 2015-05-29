<?php

const COOKIE_FILE = 'cookies.txt';

class np6_mailPerformance_Model_MPAPI
{
	var $url_base;
	var $url_path;
	var $content;
	protected $result;
	var $method;
	var $clear_cache;
	var $xkey;

	public function __construct($url_base = 'http://v8.mailperformance.com')
	{
		$this->url_base = rtrim($url_base, '/');
	}

	public function get($route)
	{
		return $this->doRequest('GET', $route);
	}

	public function post($route, $contents)
	{
		return $this->doRequest('POST', $route, $this->convertToJson($contents));
	}

	public function put($route, $contents)
	{
		return $this->doRequest('PUT', $route, $this->convertToJson($contents));
	}

	public function delete($route)
	{
		return $this->doRequest('DELETE', $route);
	}

	/**
	 *
	 * @param string $action One of GET, PUT, POST, DELETE
	 * @param unknown $route Web API endpoint to request
	 * @param string $contents Body of request (or null)
	 * @return multitype:NULL string The result of the request decoded from json
	 */
	public function doRequest($action, $route, $contents = null)
	{
		// curl configuration
		$req = curl_init($this->url_base.$route);
		curl_setopt($req, CURLOPT_CUSTOMREQUEST, $action);
		curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($req, CURLOPT_FAILONERROR, true);

		if (!$this->clear_cache)
			curl_setopt($req, CURLOPT_COOKIEFILE, COOKIE_FILE); // load cookies

		curl_setopt($req, CURLOPT_COOKIEJAR, COOKIE_FILE); // save cookies

		// headers + body contents
		$httpheaders[0] = 'Content-Type: application/json';
		if (!empty($contents))
		{
			curl_setopt($req, CURLOPT_POSTFIELDS, $contents);
			$httpheaders[1] = 'Content-Length: '.strlen($contents);
		}
		if (!empty($this->xkey))
			$httpheaders[2] = 'X-Key: '.$this->xkey;

		curl_setopt($req, CURLOPT_HTTPHEADER, $httpheaders);

		// get result and test for error
		$error = null;
		if (($result = curl_exec($req)) === false)
			$error = curl_error($req);

		// clear memory
		curl_close($req);
		unset($req);

		return array($this->convertFromJson($result), $error);
	}

	/**
	 * Do the request and Send back the request result
	 *
	 * @param bool $encode_in_json
	 *        	encode or not the request content in json (default by true)
	 * @return mixed[2] The result of the request and any associated error
	 */
	public function getResult($encode_in_json = true)
	{
		$data_string = $this->getFormatContent($encode_in_json);
		// curl configuration
		$req = curl_init($this->url_base.$this->url_path);
		curl_setopt($req, CURLOPT_CUSTOMREQUEST, $this->method);
		curl_setopt($req, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($req, CURLOPT_FAILONERROR, true);
		if (!$this->clear_cache)
			curl_setopt($req, CURLOPT_COOKIEFILE, COOKIE_FILE); // load cookies
		curl_setopt($req, CURLOPT_COOKIEJAR, COOKIE_FILE); // save cookies
		curl_setopt($req, CURLOPT_HTTPHEADER, array (
				'Content-Type: application/json',
				'Content-Length: '.strlen($data_string)
		));
		$result = curl_exec($req);

		// clear memory
		curl_close($req);
		unset($req);

		return $result;
	}

	/**
	 * Convert object to json
	 * @var mixed $contents
	 */
	public function convertToJson($object)
	{
		// transform the array in Json
		$json = json_encode($object);
		$json = preg_replace('/"ISODate\(\\\"([T0-9:\-]+Z)\\\"\)"/', 'ISODate("$1")', $json); // add dates

		return $json;
	}

	/**
	 * Convert json to object
	 * @var string $contents
	 */
	public function convertFromJson($json)
	{
		$json = preg_replace('/new\ Date\(([0-9]+)\)/', '$1', $json); // remove the new date for a valid Json
		return  json_encode($json, true);
	}

	/**
	 * get the format content
	 *
	 * @param $encode_in_json bool
	 *        	encode in Json
	 */
	public function getFormatContent($encode_in_json)
	{
		if (!$encode_in_json && !is_array($this->content))
			$data_string = $this->content;
		else
		{
			// transform the array in Json
			$data_string = convertToJson($this->content);
		}
		return $data_string;
	}

	/**
	 * Content send as payload during the request
	 *
	 * @param $content can
	 *        	be an array that will later convert in json or text
	 */
	public function setContent($content)
	{
		$this->content = $content;
	}

}
