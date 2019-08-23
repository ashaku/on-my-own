<?
	// SERVER SIDE CODE FOR [N] ELEMENT
	
	// while generating the html for a segment of this element, the [N]_start() function
	// will be called with the json containing the content to display
	function [N]_start ( $parmson ){
		$json = json_decode ( $parmson );
		
		// $json->{'field_name'}
		
		return $html;
	}

?>