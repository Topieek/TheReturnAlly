<?php
	include("../../../../config.php");
	if(isset($_POST['pid']) && isset($_POST['owner']) && isset($_POST['alliantie'])) {
		$url = "http://nl.grepostats.com/world/nl44/player/".$_POST['pid']."/towns";
		$get = file_get_contents($url);
		$dom = new DOMDocument;
		@$dom->loadHTML($get);
		$xpath = new DOMXPath($dom);
		
		$elements = $xpath->query('//a[starts-with(@href, "/world/nl44/town/")]');
		
		if($elements->length > 0) {
		
			foreach($elements AS $key => $value) {
				$town = str_replace("/world/nl44/town/", "", $value->getAttribute("href"));
				mysqli_query($link, "INSERT INTO cities_update (name, city_id, owner, ally) VALUES ('".$value->nodeValue."', '".$town."', '".$_POST['owner']."', '".$_POST['alliantie']."')");
			}
			
		}
	}