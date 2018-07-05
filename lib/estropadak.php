<?php
require_once('curl.php');

class estropadak
{
    public function __construct()
    {
    }

    public function get($year)
    {
        $url = "http://www.euskolabelliga.com/json/estropadakLSM.php?d={$year}";

		$c = new curl();
		$r = $c->request($url, 'GET');
		$r = json_decode($r, true);

		$result = array
		(
			'calendar' => array(),
			'clasif' => array()
		);

		foreach($r['items'] as $data)
		{
			$data['iz'] = html_entity_decode($data['iz']);
			$data['le'] = html_entity_decode($data['le']);
			$data['da'] = preg_replace("/^([0-9]{2})\-([0-9]{2})\-([0-9]{4})$/", '$3-$2-$1', $data['da']);
			$data['or'] = preg_replace("/\./", ':', $data['or']);

			$date = trim($data['da']." ".$data['or']);
			preg_match("/^([0-9]{4})\-([0-9]{2})\-([0-9]{2})\ ([0-9]{2}):([0-9]{2})$/", $date, $m);
			
			$date = array
			(
				'Y' => $m[1],
				'm' => $m[2],
				'd' => $m[3],
				'H' => $m[4],
				'i' => $m[5],
				's' => '00'
			);
			
			$date = mktime($date['H'], $date['i'], $date['s'], $date['m'], $date['d'], $date['Y']);
			$now = time();

			if($date < $now)
				$data['ta'] = $this->getWinner($data['id']);
			else
				$data['ta'] = '';

			$result['calendar'][] = $data;
		}
		
		$result['clasif'] = $this->getClasif($year);

		return $result;
	}
	
	private function getWinner($id)
	{
		$url = "http://www.euskolabelliga.com/json/emaitzakLSM.php?e={$id}";

		$c = new curl();
		$r = $c->request($url, 'GET');
		$r = json_decode($r, true);

		foreach($r['items'] as $data)
		{
			if($data['po']===1)
				return $data['ta'];
		}
		return '';
	}

	private function getClasif($year)
	{
		$url = "http://www.euskolabelliga.com/json/sailkapenaLSM.php?d={$year}";

		$c = new curl();
		$r = $c->request($url, 'GET');
		$r = json_decode($r, true);

		$result = array();

		foreach($r['items'] as $data)
		{
			$data['iz'] = html_entity_decode($data['iz']);
			$result[] = $data;
		}		
		return $result;
	}
}

$a = new estropadak();
$response = $a->get(date('Y'));
$text = "";
foreach($response['calendar'] as $calendar)
{
/*
	Array
	(
		[po] => 1
		[id] => 1521888579
		[iz] => IX Bandera de Bilbao
		[le] => Bilbao
		[da] => 2018-06-24
		[or] => 12:00
		[ta] => Urdaibai Avia
	)
*/
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
/*
    [11] => Array
        (
            [po] => 12
            [ta] => 38
            [iz] => Kaiku Producha
            [pu] => 4
            [ba] => 0
		)
*/
}


echo $text;