<?php

class curl
{
	private $curl_obj;

	public function __construct()
	{
        	if(!function_exists('curl_init'))
        	{
			echo 'ERROR: Install CURL module for php';
			exit();
		}
		$this->init();
	}

	private function init()
	{
		$this->curl_obj = curl_init();
	}

	public function request($url, $method = 'GET', $params = array(), $opts = array())
	{
		$method = trim(strtoupper($method));

		// default opts
		$opts[CURLOPT_FOLLOWLOCATION] = true;
		$opts[CURLOPT_RETURNTRANSFER] = 1;
		//$opts[CURLOPT_FRESH_CONNECT] = true;

		if($method==='GET')
		{
			$params = http_build_query($params);
			$url .= "?".$params;
		}
		elseif($method==='POST')
		{
			$opts[CURLOPT_POST] = 1;
			$opts[CURLOPT_POSTFIELDS] = $params;
		}

		$opts[CURLOPT_URL] = $url;

		curl_setopt_array($this->curl_obj, $opts);

		$content = curl_exec($this->curl_obj);
		return $content;
	}

	public function close()
	{
		if(gettype($this->curl_obj) === 'resource')
			curl_close($this->curl_obj);
	}

	public function __destruct()
	{
		$this->close();
	}
}
