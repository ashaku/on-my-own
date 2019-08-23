<?
	// Page publication script
	// Gather all page information and generate html
	
	// Call all active elements server-side code (in order to display them)
	$sql = "select nom from elements where actif=1";
	$res = SQLquery ( $db, $sql );
	while ( $row = getLine ( $res ) ) require_once ( "lib/".$row["nom"]."/".$row["nom"].".php" );

	// Call site sections scripts (can be customized in admin)
	require_once ( "lib/header.php" );
	require_once ( "lib/nav.php" );
	require_once ( "lib/footer.php" );
	
	// Generate the starting HTML code : <html><head> & <body><header>
	//////////////////////////////////////////////////////////////////
	function generate_page_head ( $titre, $description, $tags, $res_segments ){
		$html = "<html lang='fr'>
	<head>
		<title>".$titre."</title>
		<link rel='shortcut icon' type='image/png' href='img/favicon.png'>
		<link rel='stylesheet' type='text/css' href='lib/site.css'>
		<link href='http://fonts.googleapis.com/css?family=Oleo+Script' rel='stylesheet' type='text/css'>
		<meta charset='utf-8'>
		<meta name='description' content='".$description."'>
		<meta name='keywords' content='".$tags."'>";
		
		// If page has segments
		//$html .= sizeof($res_segments);
		if ( $res_segments != "" ){
			
			// List all elements involved in page
			while ( $row = getLine ( $res_segments ) )	$tab_elm[$row['element']] = 1;
			
			// Include each element css file in html head
			foreach ( $tab_elm as $elm=>$v )	$html .= "
			<link rel='stylesheet' type='text/css' href='lib/$elm/$elm.css'>";
		}
			
		// Generate site's structure : header & nav sections
		$html .= "
	</head><body>
		<div id='back'>
			<center><div id='wrapper'>";
		$html .= "
				".generate_site_header();
		return $html;
	}
	
	
	// Generate navigation menu : code is in nav.php, give it the database token
	////////////////////////////////////////////////////////////////////////////
	function generate_page_nav ( $db ){
		return "
				".generate_site_nav($db);
	}		
	
	
	// Generate content : elements scripts to include & segments
	////////////////////////////////////////////////////////////
	function generate_page_content ( $titre, $res_segments ){
		
		// For each segment in page
		while ( $row = getLine ( $res_segments ) ){
			
			// Save segment parameters as JSON
			$segments_parmson[$row['id']] = $row['parmson'];
			$segments_element[$row['id']] = $row['element'];
			
			// List all elements involved in page
			$tab_elm[$row['element']] = 1;
		}
			
		// Generate page elements
		$html .= "
				<content>
					<!--<h3>".$titre."</h3>-->";
		foreach ( $segments_parmson as $id=>$parmson ){
			if ( $segments_element[$id] ){
				//echo "<br>SEGMENT id:[$id] ; name:[".$segments_element[$id]."]";
				$html .= "
					<div id='seg_$id' class='segment'>";
				$fname = $segments_element[$id]."_start";
				$html .= $fname ( $parmson );
				$html .= "
					</div>";
			}
		}
		$html .= "
				</content>";
			
		// Generate site's footer section
		$html .= "
				".generate_site_footer();
		
		// Call client side code
		$html .= "
				<script src='lib/jquery.js'></script>
				<script src='lib/site.js'></script>";
		// Call each element js file at the end of html
		foreach ( $tab_elm as $elm=>$v )	$html .= "
				<script src='lib/$elm/$elm.js'></script>";
		// Call _start function for all elements
		$html .="
				<script language='javascript'>";
		foreach ( $tab_elm as $elm=>$v )	$html .= "
	".$elm."_start();";
		$html .="
				</script>";
			
		// End html code by closing all opened tags
		$html .= "
			</div></center>
		</div>
	</body>
</html>";

		return $html;
	}
	
	
	// Generate a message from the site according to a given status
	///////////////////////////////////////////////////////////////
	function generate_system_message ( $status ){
		$html = "
				<content><div class='system_message'>
					<img src='img/info.png' style='position:relative;top:6px;' /> &nbsp;";
				
		// Check status
		switch ( $status ){
			
			// Page wasn't found : either no ID given or ID doesn't exists or page is offline
			case 404 :	$html .= "The page requested can't be displayed.<br>Either it never existed, or doesn't exist anymore, or is hidding somewhere private.<br>Anyway you can't see it. Sorry.";
						break;
		}
		
		$html .= "
				</div></content>";
				
		// Generate site's footer section
		$html .= "
				".generate_site_footer();
		
		// End html code by closing all opened tags
		$html .= "
			</div></center>
		</div>
	</body>
</html>";

		return $html;
	}
?>