<?
	// Clean a text and make it engine-friendly url
	function url_name ( $str ){
		$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
		$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
		$clean = strtolower(trim($clean));
		$clean = preg_replace("/[\/_|+ -]+/", '-', $clean);
		return $clean;
	}
	
	
	// Return X first characters in a string
	function teaser ( $str, $size ){
		if ( strlen($str) <= $size ){
			return $str;
		}else{
			return substr ( $str, 0, $size-4 )." ...";
		}
	}
	// Return visible text in html content
	function visible_html ( $html ){
		$t = explode ( '<', $html );
		foreach ( $t as $line ){
			$end = strpos($line,'>');
			$tag = substr($line,0,$end);
			if (strpos($tag,' ')!==false){
				$tag = substr($tag,0,strpos($tag,' '));
			}
			if ( $tag == "br" ) $str .= " ";
			$str .= substr($line,$end+1);
		}
		return $str;
	}
	function teaser_html ( $html, $size ){
		return teaser ( visible_html($html), $size );
	}
	
	
	// save content in a file
	function save_in_file ( $content, $file_name ){
		$handle = fopen ( $file_name, 'w' );
		fwrite ( $handle, $content );
		fclose ( $handle );
	}
	
	