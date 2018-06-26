<?php
require_once('curl.php');

class athletic
{
	public static function get()
	{
		$url = 'http://www.aupaathletic.com/bancodatos/equipo-calendario.asp?id=1';

		$c = file_get_contents($url);

                $c = new curl();
                $r = $c->request($url, 'GET');

		$dom = new DOMDocument('1.0', 'utf-8');
		@$dom->loadHTML($r);

		$xpath = new DOMXpath($dom);

		$trs = $xpath->query('//table[@class="table table-striped"]/tbody/tr');

		$result = array();
		foreach($trs as $tr)
		{
			$params = array();

			$tds = $xpath->query('td', $tr);
			$i = 0;
			foreach($tds as $td)
			{
				$text = trim($td->textContent);
				$text = utf8_decode($text);
				// ENTER-ak kendu
				$text = preg_replace("/\r\n/", '', $text);

				switch($i)
				{
					case 1: // data
						if(preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})\s+([0-9]{2})\:([0-9]{2})h./", $text, $m))
							$text = "{$m[3]}/{$m[2]}/{$m[1]} {$m[4]}:{$m[5]}";
						elseif(preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/", $text, $m))
							$text = "{$m[3]}/{$m[2]}/{$m[1]}";

						$params['data'] = $text;
					break;
					case 2: // partidu mota
						if(preg_match("/Liga 1ª DivisiónJornada ([0-9]+)/", $text, $m))
						{
							if(intval($m[1])<10)
								$m[1] = "0{$m[1]}";

							$text = "Ligie {$m[1]}";
						}
						elseif(preg_match("/UEFA/", $text))
							$text = 'UEFA';
						elseif(preg_match("/Amistoso/", $text))
							$text = 'Amistosue';
						elseif(preg_match("/copa/i", $text))
							$text = 'Kopie';

						$params['mota'] = $text;
						break;
					case 3: // etxeko taldea
						$params['etxekoa'] = $text;
						break;
					case 4: // emaitza
						$params['emaitza'] = $text;
						break;
					case 5: // kanpoko taldea
						$params['kanpokoa'] = $text;
						break;
					default:
						break;
				}
				$i++;
			}
			$result[] = $params;
		}
		$txt = "";
		foreach($result as $r)
		{
			$txt.="{$r['mota']}: {$r['etxekoa']} - {$r['kanpokoa']}";
			if($r['emaitza']!='-')
				$txt.= " ({$r['emaitza']})";
			$txt.=" {$r['data']}\n";
		}
		return $txt;
	}
}