<?
	function is_auth (){
		if ( isset($_SESSION['auth']) ){
			if ( $_SESSION['auth'] )	return true;
		}
		return false;
	}
	
	
	function ask_auth( ){
?>		
		<center>
		<form action="check_auth.php" method="post" class="login_form">
			<b>Authentication please</b><br><br>
			<table><tr>
				<td>login : </td>
				<td><input type='text' name='login'></td>
			</tr><tr>
				<td>password :</td>
				<td><input type='password' name='passwd'></td>
			</tr></table>
			<input type='submit' value='OK'>
		</form>
		</center>
		
<?	
	}
?>