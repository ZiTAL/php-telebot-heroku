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
		$chat_id = $item['message']['chat']['id'];
                $reply_to_message_id = $item['message']['message_id'];

		switch($text)
		{
			case '/papeo':
			{
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
/*
			case '/golazo':
			{
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
*/
			case '/estropadak':
			{
				$response = "Málaga              2015/06/28 12:00
Sanxenxo            2015/07/04 18:00
Moaña               2015/07/05 12:00
Pasai San Pedro     2015/07/05 18:00
Donostia            2015/07/12 12:00
Orio                2015/07/18 18:00
Portugalete         2015/07/19 12:00
Noja                2015/07/25 18:00
Getxo               2015/07/26 12:00
Meira (Moaña)       2015/08/08 18:00
Boiro               2015/08/09 12:00
Zarautz             2015/08/15 18:00
Zarautz             2015/08/16 12:00
Hondarribia         2015/08/22 18:00
Portugalete         2015/08/23 12:00
Bilbao              2015/08/29 18:00
Zierbena            2015/08/30 12:00
Donosti Kontxa      2015/09/06
Donosti Kontxa      2015/09/13
Bermeo PlayOff      2015/09/19 18:00
Bermeo              2015/09/19 18:30
Portugalete PlayOff 2015/09/20 12:00
Portugalete         2015/09/20 12:30";

				$estropadak = preg_split("/\n/", $response);
				$orain = time();
				foreach($estropadak as $estropada)
				{
				        preg_match("/([0-9]{4})\/([0-9]{2})\/([0-9]{2})\ ([0-9]{2}):([0-9]{2})/", $estropada, $m);
				        if(!$m)
				        {
				                preg_match("/([0-9]{4})\/([0-9]{2})\/([0-9]{2})/", $estropada, $m);
				                if($m)
				                {
				                        $m[4] = '00';
				                        $m[5] = '00';
				                }
				        }
				        $time = mktime($m[4], $m[5], '00', $m[2], $m[3], $m[1]);
				        if($time>$orain)
				        {
				                $response.="\n\nHurrengue:\n".$estropada;
				                break;
				        }
				}

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

			case '/garrote':
			{
				include('imgur.php');

				//$imgur = new imgur('spaceporn', $chat_id);
				$imgur = new imgur('nsfw', $chat_id);
				$filename = $imgur->getResource();

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

				$method = 'sendPhoto';
				if(preg_match("/\.mp4$/", $filename))
				{
					unset($params['photo']);
					$params['video'] = $cfile;
					$method = 'sendVideo';
				}

                                $this->request($method, $params);

                                $params = array
                                (
                                        'chat_id' => $chat_id,
                                        'text' => "debug: ".$filename,
                                        'disable_web_page_preview' => null,
                                        'reply_to_message_id' => $reply_to_message_id
                                );
                                $this->request('sendMessage', $params);

				file_put_contents($filename, '');
                                break;
			}

			case '/getInfo':
                                $params = array
                                (
                                        'chat_id' => $chat_id,
                                        'text' => print_r($item, true),
                                        'disable_web_page_preview' => null,
                                        'reply_to_message_id' => $reply_to_message_id
                                );
                                $this->request('sendMessage', $params);
			default:
				break;
		}
	}
}
