<?
	require_once("../lib/base.php");
	$db = connectToDatabase();
?>
<html lang='fr'>
	<head>
		<!-- Site files -->
		<title>Project - Admin</title>
		<link rel='stylesheet' type='text/css' href='admin.css'>
		<link rel='stylesheet' type='text/css' href='../lib/site.css'>
		<link href='http://fonts.googleapis.com/css?family=Oleo+Script' rel='stylesheet' type='text/css'>
		
		<!-- External files -->
<?	if ( $_SESSION['current_section'] == "site" || $_SESSION['current_section'] == "elmt" ){	?>
		<link rel="stylesheet" href="../ext/codemirror/codemirror.css" />
		<script src="../ext/codemirror/codemirror.js"></script>
		<script src="../ext/codemirror/mod.js"></script>
		<link rel="stylesheet" href="../ext/codemirror/fullscreen.css" />
		<script src="../ext/codemirror/fullscreen.js"></script>
		<style type="text/css">
			.CodeMirror {
				border-top:1px solid black;
				border:1px solid #eee;
				background:#f8f8f8;
				height: auto;
			}
		</style>
<?	}
		// Call all active elements styles for page editing
		if ( $_SESSION['current_section'] == "pages" ){
			$sql = "select nom from elements where actif=1";
			$res = SQLquery ( $db, $sql );
			while ( $row = getLine ( $res ) ) echo "<link rel='stylesheet' type='text/css' href='../lib/".$row["nom"]."/".$row["nom"].".css'>";
		}
?>
		<meta charset='utf-8' />
	</head><body>
		<div id='back'>
			<center><div id='wrapper'>
<?
	// Display website structure
	require_once ( "../lib/header.php" );
	require_once ( "../lib/nav.php" );
	require_once ( "../lib/footer.php" );
	require_once ( "../lib/toolbox.php" );
	echo generate_site_header();
	echo generate_site_nav ( $db );
	echo "<content>";
	
	
	// AUTHENTICATION
	/////////////////
	require_once ( "auth.php" );
	if ( !is_auth() ) ask_auth();
	else{
		
		// User authenticated : display Admin Menu
?>
					<div id="admin_nav">
						<ul>
							<li style="background:linear-gradient(0deg,#efe,#dfd);cursor:pointer;" onclick="window.location.assign('admin_site.php');">
								<b>Site</b> : customize site's structure<br>
								<ul>
									<li><a href='admin_site_file.php?f=head' title="Edit php code generating the <header> section">head</a></li>
									<li><a href='admin_site_file.php?f=nav' title="Edit php code generating the <nav> section">nav</a></li>
									<li><a href='admin_site_file.php?f=foot' title="Edit php code generating the <footer> section">foot</a></li>
									<li><a href='admin_site_file.php?f=php' title="Edit server-side code of website">php</a></li>
									<li><a href='admin_site_file.php?f=css' title="Edit stylesheet of website">css</a></li>
									<li><a href='admin_site_file.php?f=js' title="Edit cient-side code of website">js</a></li>
								</ul>
							</li>
							
							<li style="background:linear-gradient(0deg,#eef,#ddf);cursor:pointer;" onclick="window.location.assign('admin_category.php');">
								<b>Pages</b> : edit content<br>
								<ul>
									<li><a href='admin_category.php' title='View list of categories'>categories</a></li>
									<li><a href='admin_pages.php?cat=1&n=system' title='View category : system'>system</a></li>
									<!-- TODO/NEXT : list most recent categories -->
								</ul>
							</li>
							<li style="background:linear-gradient(0deg,#fee,#fdd);cursor:pointer;" onclick="window.location.assign('admin_elements.php');">
								<b>Elements</b> : display<br>
								<ul>
									<li><a href='admin_elements.php' title='Administrate site elements'>elements</a></li>
									<li><a href='admin_element.php?id=1&n=rich_text' title='View element : rich_text'>rich_text</a></li>
									<!-- TODO/NEXT : list most recent elements -->
								</ul>
							</li>
							<li style="background:linear-gradient(0deg,#fef,#fdf);cursor:pointer;" onclick="window.location.assign('admin_documents.php');">
								<b>Documents</b> &nbsp; <br>
								<ul>
									<li><a href='admin_documents.php' title='Administrate site documents'>documents</a></li>
								</ul>
							</li>
							<li style="background:linear-gradient(0deg,#f88,#f55);cursor:pointer;color:#fff;" onclick="window.location.assign('logout.php');">
								<b>X</b><br>
								<ul>
									<li><a href='admin.php' title='Exit admin session'>logout</a></li>
								</ul>
							</li>
						</ul>
					</div>
<?	}	?>