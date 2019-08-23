<?

	// string = json_encode ( array OR object );
	// array = json_decode

	$f = $_GET['f'];
	switch ( $f ){
		case 1 : // test
			$str = $_GET['str'];
			$ret = test ( $str );		
	}
	echo $ret;
	
	
	
	
	function test ( $str ){
		return "{test : $str";
	}
?>