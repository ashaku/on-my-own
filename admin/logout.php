<?	
	// Admin page settings
	session_start();
	unset($_SESSION['auth']);
	
	header("location:admin.php");
?>