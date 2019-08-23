<?
	session_start();
	require_once ( "auth.php" );
	if ( is_auth() ){
		// SITE FILES ADMINISTRATION
		////////////////////////////
		// Save current file (before calling header to view the result)
		if ( $_POST['modif_file'] ){
			//$content = $_POST['content'];
			//save_in_file ( $content, $_SESSION['current_sitefile_path'] );
			$handle = fopen ( $_SESSION['current_sitefile_path'], 'w' );
			fwrite ( $handle, $_POST['content'] );
			fclose ( $handle );
		}
	}
	
	// Admin page settings
	$_SESSION['current_section'] = "site";
	require_once("admin_head.php");
	if ( is_auth() ){
		
		
		// Save chosen file ID
		//////////////////////
		$file = $_GET['f'];
		if ( strlen($file) > 0 ) {
			// Save file identifier (head,nav,foot,css,js,php)
			$_SESSION['current_sitefile_id'] = $file;
			// Save file relative path
			$file_path = get_file_path ( $file );
			$_SESSION['current_sitefile_path'] = $file_path;
		}
		
		// Retrieve file content
		$file_content = file_get_contents ( $_SESSION['current_sitefile_path'] );
?>
			<!-- Display navigation submenu -->
			<div id="submenu">
				<a href='admin.php'>Admin</a> &gt; 
				<a href='admin_site.php'>Site</a> &gt; 
				<a href='admin_site_file.php?f=<? echo $_SESSION['current_sitefile_id']; ?>'><? echo substr($_SESSION['current_sitefile_path'],7); ?></a>
			</div>
			
			<!-- File edition form -->
			<div class='content_group site'>
				<form action='admin_site_file.php' method='POST'>
					<input type='hidden' name='modif_file' value='1' />
					<input type='submit' value='Save file' />
					<textarea id='code_edit' name='content'><? echo $file_content; ?></textarea>
				</form>
			</div>
			<script>
				var editor = CodeMirror.fromTextArea(document.getElementById("code_edit"), {
				  mode: "<? echo get_file_type($_SESSION['current_sitefile_id']); ?>",
				  lineNumbers: true,
				  indentUnit: 4,
				  indentWithTabs: true,
				  extraKeys: {
					"F11": function(cm) {
					  cm.setOption("fullScreen", !cm.getOption("fullScreen"));
					},
					"Esc": function(cm) {
					  if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
					}
				  }
				});
			</script>
		
<?		require_once("admin_foot.php");
	}
	
	
	// translate file name
	function get_file_path($file){
		switch ( $file ){
			case "head":return "../lib/header.php";
			case "nav":return "../lib/nav.php";
			case "foot":return "../lib/footer.php";
			case "php":return "../lib/toolbox.php";
			case "css":return "../lib/site.css";
			case "js":return "../lib/site.js";
		}
	}
	// translate file name application/xml
	function get_file_type($file){
		switch ( $file ){
			case "head":
			case "nav":
			case "foot":
			case "php":return "application/x-httpd-php";
			case "css":return "text/css";
			case "js":return "javascript";
		}
	}
?>