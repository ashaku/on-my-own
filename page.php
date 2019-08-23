<?
	session_start();
	require_once ( "lib/base.php" );		// Database connection
	$db = connectToDatabase();
	require_once ( "lib/toolbox.php" );		// General server-side code
	require_once ( "lib/publi.php" );		// HTML Publication functions
	//require_once ( "lib/queries.php" );	// TODO/NEXT : Business Logic Layer
	$page_status = 200;
	
	// Get the ID of page
	$page_id = $_GET['id'];
	$res_segments = "";
	if ( strlen($page_id) == 0 )	$page_status = 404;
	else{
		
		// Retrieve infos about page
		if ( !isset($_SESSION['auth']) ) $filter = " and publie=1";
		$sql = "select titre,description,tags,date_generation,date_modification from pages where id=$page_id".$filter;
		$res = SQLquery ( $db, $sql );
		// Test if page exists
		if ( nbRows($res) == 0 )	$page_status = 404;
		else{
			
			// Page exists : collect infos
			$row = getLine ( $res );
			$page_title = $row['titre'];
			//$page_name = $page_id."-".url_name($page_title).".html";
			$page_desc = $row['description'];
			$page_tags = $row['tags'];
			// Content in page
			$sql = "	SELECT s.id num,s.parmson,e.nom element
						FROM pages p
						left join segments s on s.page=p.id
						left join elements e on s.element=e.id
						where p.id=".$page_id."
						order by num";
			$res_segments = SQLquery ( $db, $sql );
		}
	}
	
	// Generate content if page is not ok
	if ( $page_status != 200 ){
		$page_title = "Error : ".$page_status;
		$page_desc =  "Page error status ".$page_status;
		$page_tags = "";
		//$res_segments = "";
	}
	
	// Display page's header & navigation
	$html = generate_page_head ( $page_title, $page_desc, $page_tags, $res_segments );
	$html .= generate_page_nav ( $db );
	if ( $page_status == 200 ){
		
		// Content of page
		//$res_segments = SQLquery ( $db, $sql );
		$html .= generate_page_content ( $page_title, $res_segments );
	}else{
		
		// Error message
		$html .= generate_system_message ( $page_status );
	}
	
	// TODO/NEXT : Test if page up to date
	/*********************************************************************************************************
	if ( $row['date_generation'] < $row['date_modification'] ){
		// Page out of date : regenerate page content
		// Save in html file
		save_in_file ( $html, $page_name );
		// Update generation_date
		//SQLquery ( $db, "update pages set date_generation='".date("Y-m-d H:i:s")."' where id=".$page_id );
	
	}else{
		// Page is up to date : read html in file
		$html = file_get_contents ( $page_name );
	}
	**********************************************************************************************************/
	
	
	// Close Database
	closeDatabase ( $db );
	
	// Display page
	echo $html;
	
?>