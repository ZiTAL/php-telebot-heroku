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

			case '/estropadak':
			{
				include('estropadak.php');
				$a = new estropadak();
				$response = $a->get(date('Y'));

				$text = "";
				foreach($response['calendar'] as $calendar)
				{
					$text.= $calendar['iz']."\n";
					
					for($i=0; $i<strlen($calendar['iz']); $i++)
						$text.='*';
					$text.="\n";
					
					$text.= $calendar['le']." ".$calendar['da']." ".$calendar['or']."\n";
					if($calendar['ta']!=='')
						$text.= "Irebazlie: ".$calendar['ta']."\n";
					$text.="\n";
				}
				
				$text.= "\nSailkapena:\n\n";
				foreach($response['clasif'] as $clasif)
				{
					$text.= $clasif['po'].". ".$clasif['iz'];
					if($clasif['ba']>0)
						$text.= " - banderak: ".$clasif['ba'];
					$text.= "\n";
				}

                                $params = array
                                (
                                        'chat_id' => $chat_id,
                                        'text' => $text,
                                        'disable_web_page_preview' => null,
                                        'reply_to_message_id' => $reply_to_message_id
                                );
                                $this->request('sendMessage', $params);
                                break;
			}

			case '/athletic':
			{
				include('athletic.php');
				$response = athletic::get();


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
