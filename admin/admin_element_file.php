<?
	// Admin page settings
	session_start();
	$_SESSION['current_section'] = "elmt";
	require_once("admin_head.php");
	if ( is_auth() ){
		
		// ELEMENT FILES ADMINISTRATION
		///////////////////////////////
		// Save current file (BEFORE CALL HEADER !!)
		if ( $_POST['file'] ){
			$content = $_POST['content'];
			save_in_file ( $content, $_SESSION['current_elmtfile_path'] );
		}
		
		// Save chosen file ID
		//////////////////////
		$file = $_GET['f'];
		if ( strlen($file) > 0 ) {
			// Save file identifier (head,nav,foot,css,js,php)
			$_SESSION['current_elmtfile_id'] = $file;
			// Save file relative path
			$path = "../lib/".$_SESSION['current_element_name']."/".$_SESSION['current_element_name'].".".$file;
			$_SESSION['current_elmtfile_path'] = $path;
		}
		
		// Retrieve file content
		$file_content = file_get_contents ( $_SESSION['current_elmtfile_path'] );
?>
			<!-- Display navigation submenu -->
			<div id="submenu">
				<a href='admin.php'>Admin</a> &gt; 
				<a href='admin_elements.php'>Elements</a> &gt; 
				<a href='admin_element.php'><? echo $_SESSION['current_element_name']; ?></a> &gt; 
				<a href='admin_site_file.php?f=<? echo $_SESSION['current_elmtfile_id']; ?>'><? echo $_SESSION['current_elmtfile_id']; ?></a>
			</div>
			
			<!-- File edition form -->
			<div class='content_group elmt'>
				<form action='admin_element_file.php' method='POST'>
					<input type='hidden' name='file' value='1' />
					<input type='submit' value='Save file' />
					<textarea id='code_edit' name='content'><? echo $file_content; ?></textarea>
				</form>
			</div>
			<script>
				var editor = CodeMirror.fromTextArea(document.getElementById("code_edit"), {
				  mode: "<? echo get_file_type($file); ?>",
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
	
	
	// translate file name application/xml
	function get_file_type($file){
		switch ( $file ){
			case "php":return "application/x-httpd-php";
			case "css":return "text/css";
			case "js":return "javascript";
		}
	}
?>