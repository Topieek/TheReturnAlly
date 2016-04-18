<?php
	if(isset($_GET['page'])) {
		$url = "http://nl.grepostats.com/world/nl44/rankings/players?page=".$_GET['page']."&search=&sortby=";
		$get = file_get_contents($url);
		$doc = new DOMDocument;
		@$doc->loadHTML($get);
		$xpath = new DOMXpath($doc);
		
		$elements = $xpath->query('//td[@style="width: 24%"]/a[starts-with(@href, "/world/nl44/player/")]');
		$elements1 = $xpath->query('//td[@style="width: 24%"]/a[starts-with(@href, "/world/nl44/alliance/")]');
			
		$json = array();
		
		foreach($elements AS $key => $value) {
			$id = str_replace("/world/nl44/player/", "", $value->getAttribute('href'));
			$json[] = array("owner" => $value->nodeValue, "id" => $id, "alliantie" => $elements1[$key]->nodeValue);
		}
		
		echo json_encode($json);
		
		http_response_code(202);
	}