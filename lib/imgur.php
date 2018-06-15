<?php
require_once('curl.php');

class imgur
{
	private $url = 'http://imgur.com/r/#tag#/new/page/#index#/hit?scrolled';
	private $folder = '/tmp/imgur/#tag#/';

	private $curl_obj;

	public function __construct($tag, $chat_id)
	{
		$chat_id = preg_replace("/^\-/", "n", $chat_id);

		$this->url = preg_replace("/#tag#/", $tag, $this->url);
		$this->folder = PATH.preg_replace("/#tag#/", $tag, $this->folder);

		if(!is_dir($this->folder))
			mkdir($this->folder);

		$this->folder .= $chat_id."/";

		if(!is_dir($this->folder))
			mkdir($this->folder);

		$this->curl_obj = new curl();
	}

	public function getResource()
	{
		$found = false;
		$i = 0;
		do
		{
		        $url = preg_replace("/#index#/", $i, $this->url);

			$opts = $this->getCurlOpts();

		        $r = $this->curl_obj->request($url, 'GET', array(), $opts);

			$dom = $this->createHtmlDom($r);

			$links = $this->getLinks($dom);

			$multimedias = $this->getMultimedias($links);

			$file = $this->getFile($multimedias, true);
			if($file!==false)
				return $file;
		}
		while($found===false);
	}

	private function getCurlOpts()
	{
		$opts = array
		(
			CURLOPT_HTTPHEADER => array
			(
				'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
				'Accept-Encoding' => 'gzip, deflate, sdch',
				'Accept-Language' => 'en-US,en;q=0.8,es;q=0.6',
				'Connection' => 'keep-alive',
				'Host' => 'imgur.com',
				'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/43.0.2357.130 Chrome/43.0.2357.130 Safari/537.36'
			)
		);

		return $opts;
	}

	private function createHtmlDom($html)
	{
		$html = "<html>
<head>
        <title>a</title>
</head>
<body>".$html."
</body>
</html>";

		$dom = new DOMDocument();
		$dom->loadHTML($html);

		return $dom;
	}

	private function getLinks($dom)
	{
	        $xpath = new DOMXPath($dom);

	        $links = array();
	        $as = $xpath->query("//a[@class=\"image-list-link\"]");
	        foreach($as as $a)
	        {
        	        $href = $a->getAttribute('href');
			$href = preg_replace("/^\/\//", '', $href);
			$href = "https://imgur.com{$href}";
                       	$links[] = $href;
	        }
		return $links;
	}

	private function getMultimedias($links)
	{
		$images = array();
		foreach($links as $link)
		{
			$image = '';

			$r = $this->curl_obj->request($link);

			$dom = new DOMDocument();
			@$dom->loadHTML($r);

			$xpath = new DOMXPath($dom);

			$imgs = $xpath->query("//link[@rel=\"image_src\"]");
			if($imgs->length>0)
			{
				$image = $imgs->item(0);
				$image = $image->getAttribute('href');
			}
			if($image!=='')
				$images[] = $image;
		}
		return $images;
	}

	private function getFile($multimedias, $shuffle = false)
	{
		if($shuffle)
			shuffle($multimedias);

		foreach($multimedias as $image)
		{
			$basename = basename($image);
			$filename = $this->folder.$basename;
			if(!is_file(realpath($filename)))
			{
				$content = $this->curl_obj->request($image);
				file_put_contents($filename, $content);

				return $filename;
			}
		}
		return false;
	}
}
