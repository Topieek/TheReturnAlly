<?php
	include("../../../config.php");
	if(isset($_GET['id'])) {
		$i = $_GET['id'];
		
		$url = "http://nl.grepostats.com/world/nl44/town/" . $i;
		$doc = new DOMDocument;
		$contents = file_get_contents($url);
		@$doc->loadHTML($contents);
		$xpath = new DOMXpath($doc);
		$elements = $xpath->query('//a[@href="/world/nl44/town/'.$i.'"]');
		
		$id = $i;
		$name = $elements[0]->nodeValue;
		
		$elements1 = $xpath->query('//a[starts-with(@href, "/world/nl44/player/")]');
		$owner =  $elements1[0]->nodeValue;
		
		$elements2 = $xpath->query('//a[starts-with(@href, "/world/nl44/alliance/")]');
		$ally = $elements2[0]->nodeValue;
		if(isset($name)) {
			mysqli_query($link, "INSERT INTO cities_update (name, city_id, owner, ally) VALUES ('".$name."', '".$id."', '".$owner."', '".$ally."')");
		}
		http_response_code(202);
	}
