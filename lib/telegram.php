<?php
require_once('curl.php');

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

		$text = preg_replace("/@[^$]+$/", '', $text);

		// txupintzako tranpie
		if(preg_match("/^\/[a-z]+arrote$/", $text) && $text!=='/garrote')
		        $text = '/qarrote';

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
Txurrerue: 946882073
Txinue: 946028392";

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
*/
			case '/athletic':
			{
				include('athletic.php');
				$response = athletic::get();
/*
				$response = "
UEFA F Taldie: Ateleti - Rapid Wien		2016/09/29 21:05
Ligie 07: Malaga - Ateleti			2016/10/02 18:30
Amistosue: Sestao River - Athletic Club		2015/12/30 20:30
Ligie 08: Real Sociedad - Athletic Club 	2016/01/03 18:15
Ligie 19: Sevilla - Athletic Club		2016/01/09 18:15
Ligie 20: Barcelona - Athletic Club		2016/01/17 20:30
Ligie 21: Athletic Club - Eibar			2016/01/24 23:59
Ligie 22: Getafe - Athletic Club		2016/01/31 23:59
Ligie 23: Athletic Club - Villareal		2016/02/07 23:59
Ligie 24: Real Madrid - Athletic Club		2016/02/14 23:59
UEFA 1/16: Olympique Marseille - Athletic Club	2016/02/18 21:05
Ligie 25: Athletic Club - Real Sociedad		2016/02/21 23:59
UEFA 1/16: Athletic Club - Olympique Marseille	2016/02/25 19:00
Ligie 26: Valencia - Athletic Club		2016/02/28 23:59
Ligie 27: Athletic Club - Deportivo		2016/03/02 23:59
Ligie 28: Sporting - Athletic Club 		2016/03/06 23:59
Ligie 29: Athletic Club - Betis			2016/03/13 23:59
Ligie 30: Espanyol - Athletic Club		2016/03/20 23:59
Ligie 31: Athletic Club - Granada		2016/04/03 23:59
Ligie 32: Athletic Club - Rayo Vallecano	2016/04/10 23:59
Ligie 33: Málaga - Athletic Club		2016/04/13 23:59
Ligie 34: Athletic Club - Atlético Madrid	2016/04/20 23:59
Ligie 35: Levante - Athletic Club		2016/04/24 23:59
Ligie 36: Athletic Club - Celta			2016/05/01 23:59
Ligie 37: Las Palmas - Athletic Club		2016/05/08 23:59
Ligie 38: Athletic Club - Sevilla 		2016/05/15 23:59";
*/

				$partiduek = preg_split("/\n/", $response);

				$now = mktime(0, 0, 0, date('m'), date('d'), date('Y'));

				$tmp = "";
				$i = 0;
				foreach($partiduek as $partidue)
				{
					preg_match("/([0-9]{4})\/([0-9]{2})\/([0-9]{2})/", $partidue, $m);

					$p_time = mktime(0, 0, 0, $m[2], $m[3], $m[1]);
					if($p_time>=$now)
					{
						if($i===0)
							$h_partidue = $partidue;
						$tmp.=$partidue."\n\n";
						$i++;
					}
				}

				if(isset($h_partidue))
					$tmp.="\nHurrengo partidue:\n".$h_partidue;

				$response = $tmp;

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
/*

				$params = array
				(
						'chat_id' => $chat_id,
						'text' => "debug: ".$filename,
						'disable_web_page_preview' => null,
						'reply_to_message_id' => $reply_to_message_id
				);
				$this->request('sendMessage', $params);
*/

				file_put_contents($filename, '');
				break;
			}
			case '/andobuek':
			{
				include('imgur.php');

				//$imgur = new imgur('spaceporn', $chat_id);
				$imgur = new imgur('malemodels', $chat_id);
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
/*

                                $params = array
                                (
                                        'chat_id' => $chat_id,
                                        'text' => "debug: ".$filename,
                                        'disable_web_page_preview' => null,
                                        'reply_to_message_id' => $reply_to_message_id
                                );
                                $this->request('sendMessage', $params);
*/

				file_put_contents($filename, '');
                                break;
			}			

			// txupintzako tranpie
			case '/qarrote':
			{
				$response = "MARIKA";

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
