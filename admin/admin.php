<?	
	// Admin page settings
	session_start();
	$_SESSION['current_section'] = "home";
	require_once("admin_head.php");
	if ( is_auth() ){
		
		//show_session_vars();
?>		
		<!-- Display navigation submenu -->
		<div id='submenu'>
			<a href='admin.php'>Admin</a>
		</div>
		<div class='content_group elmt'>
		
			<!-- Site generation : create html file for all public pages -->
			<div>
				<b>Site Generation</b><br>
				<i>Coming in future version ...</i>
			</div>
			
			
			<!-- Site skin edition : edit basic settings for site -->
			<br><br>
			<div>
				<b>Site skin edition</b><br>
				<i>Coming in future version ...</i>
			</div>
			
			
			<!-- Admin section with quick description -->
			<br><br>
			<table cellspacing=10 id="element_files"><tr>
				<td onclick="window.location.assign('admin_site.php');"><a href="admin_site.php">Site structure</a><br><br>
				Customize the look of your site by<br>
				directly editing the code.<br>
				Site's behavior run with 6 files,<br>
				all is automated, you just have to<br>
				write your functions and call them.<br>&nbsp;</td>
				<td onclick="window.location.assign('admin_category.php');"><a href="admin_category.php">Site content</a><br><br><br>
				Customize your website content by<br>
				creating web pages and fill them.<br>
				Pages are stored in categories.</td>
				<td onclick="window.location.assign('admin_elements.php');"><a href="admin_elements.php">Elements</a><br><br><br>
				Hairy stuff.<br><br>
				You should read the doc first.</td>
			</tr></table>
			<br><br>
		</div>
		
		<div style='height:150px;'>&nbsp;</div>
<?
	}
	require_once("admin_foot.php");
?>