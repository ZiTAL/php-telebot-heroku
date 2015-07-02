<?php
include('curl.php');

class telegram
{
	private $url = 'https://api.telegram.org/bot';
	private $api_key;

	public function __construct($api_key)
	{
		$this->api_key = $api_key;
	}

	public function request($method, $params = array())
	{
		$c = new curl();
		$r = $c->request($this->url.$this->api_key."/".$method, 'POST', $params);

		$j = json_decode($r, true);
		if($j)
			return $j;
		else
			return $r;
	}

	public function parse($item)
	{
		$text = $item['message']['text'];
		switch($text)
		{
			case '/papeo':
			{
				$chat_id = $item['message']['chat']['id'];
				$reply_to_message_id = $item['message']['message_id'];

				$response = "Gai Sai: 946884998
Itxas Gane: 946880946
Izaro: 946881112
Izokin: 946884891
Kebab: 946477895
Zokoa: 946028395
Napolis: 946186385
Txurrerue: 946882073";

				$params = array
				(
					'chat_id' => $chat_id,
					'text' => $response,
					'disable_web_page_preview' => null,
					'reply_to_message_id' => $reply_to_message_id
				);
				$this->request('sendMessage', $params);
				break;
			}

			case '/golazo':
			{
                                $chat_id = $item['message']['chat']['id'];
                                $reply_to_message_id = $item['message']['message_id'];

				$filename = PATH."/tmp/senor.jpg";

				if(class_exists('CURLFile'))
					$cfile = new CURLFile($filename);
				else
					$cfile = "@".$filename;

                                $params = array
                                (
					'chat_id' => $chat_id,
					'photo' => $cfile,
					'reply_to_message_id' => $reply_to_message_id,
					'reply_markup' => null
                                );
				$this->request('sendPhoto', $params);

				break;
			}

			default:
				break;
		}
	}
}
