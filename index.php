<?php
require_once('lib/telegram.php');

define('PATH', realpath('./'));
$config = json_decode(file_get_contents(PATH."/config.json"), true);

// telegram instance
$telegram = new telegram($config['TELEGRAM_API_KEY']);

// get Uri
$uri = $_SERVER['REQUEST_URI'];

switch($uri)
{
	case '/webHookUpdates':
	{
		$result = getRequest();
		$telegram->parse($result);

		break;
	}
	case '/getUpdates':
	{
		$offset = null;
		$method = 'getUpdates';

		$params = array
		(
			'offset' => $offset,
			'limit' => null,
			'timeout' => null
		);

		do
		{
			$result = $telegram->request($method, $params);

			echo "<pre>";
			print_r($result);
			echo "</pre>";

			// parse each comment
			foreach($result['result'] as $r)
			{
				$params['offset'] = $r['update_id'];
				$telegram->parse($r);
			}

			// to remove notifications, else always get the last
			$params['offset']++;
		}
		while($result['ok']===true && count($result['result'])>0);
		break;
	}

	case '/setWebhook':
	{
		$method = 'setWebhook';
		$url = "https://".$config['APP_ID'].".herokuapp.com/webHookUpdates";
		$params = array
		(
			'url' => $url
		);
		$result = $telegram->request($method, $params);

		echo "<pre>";
		print_r($result);
		echo "</pre>";

		break;
	}

	case '/unsetWebhook':
	{
                $method = 'setWebhook';
                $params = array
                (
                        'url' => null
                );
                $result = $telegram->request($method, $params);

                echo "<pre>";
                print_r($result);
                echo "</pre>";

		break;
	}

	default:
	{
		include('view/default.tpl');
	}
}

function getRequest()
{
	$postdata = file_get_contents("php://input");
	$json = json_decode($postdata, true);
	if($json)
		return $json;
	return $postdata;
}
