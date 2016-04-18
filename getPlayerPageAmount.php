<?php
	$url = "http://nl.grepostats.com/world/nl44/rankings/players";
	$get = file_get_contents($url);
	$dom = new DOMDocument;
	@$dom->loadHTML($get);
	$xpath = new DOMXPath($dom);
	
	$elements = $xpath->query('//a[@href="?page=*&search=&sortby="]');
	$elements = $xpath->query('//a[starts-with(@href, "?page=")]');
	echo $elements[5]->nodeValue;
	http_response_code(202);