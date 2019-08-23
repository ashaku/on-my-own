<?
	session_start();
	
	// Set authenticated login
	$auth_login = "admin";
	$auth_passwd = "admin";
	
	// Collect posted informations
	$login = $_POST['login'];
	$passwd = $_POST['passwd'];
	
	// Compare
	if ( $login == $auth_login && $passwd == $auth_passwd ){
		
		// Login OK : Set Session Info
		$_SESSION['auth'] = true;
		
	}else{
		
		// Login KO
		$_SESSION['auth'] = false;
	}
	
	header ( "location:admin.php" );
?>