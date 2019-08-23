<?
	// SERVER SIDE CODE FOR RICH_TEXT ELEMENT

	// while generating the html for a segment of this element, the rich_text_start() function
	// will be called with the json containing the content to display
	function rich_text_start ( $parmson ){
		$json = json_decode ( $parmson );
		if ( $json->{'titre'} )	$html .= "<div class='rich_text_titre'>".$json->{'titre'}."</div>";
		if ( $json->{'contenu'} )	$html .= "<div class='rich_text_desc'>".html_entity_decode($json->{'contenu'})."</div>";
		return $html;
	}

?>