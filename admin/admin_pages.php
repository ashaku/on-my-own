<?	
	// Admin page settings
	session_start();
	$_SESSION['current_section'] = "pages";
	require_once("admin_head.php");
	if ( is_auth() ){
	
		
		// PAGES ADMINISTRATION
		///////////////////////
		// Toggle page status (online/offline)
		if ( $_GET['togpage'] ){
			$sql = "update pages set publie=".$_GET['togs']." where id=".$_GET['togpage'];
			SQLquery ( $db, $sql );
		}
		// Delete a page
		if ( $_GET['delpage'] ){
			// Delete all page's segments
			$sql = "delete from segments where page=".$_GET['delpage'];
			SQLquery ( $db, $sql );
			// Delete page
			$sql = "delete from pages where id=".$_GET['delpage'];
			SQLquery ( $db, $sql );
		}
		
		// Save chosen category ID
		//////////////////////////
		$cat = $_GET['cat'];
		if ( strlen($cat) > 0 ) {
			$_SESSION['current_categorie_id'] = $cat;
			$_SESSION['current_categorie_name'] = $_GET['n'];
		}
		// Erase previously chosen page ID
		$_SESSION['current_page_id'] = 0;
		$_SESSION['current_page_name'] = "";
		
		// Display navigation submenu
		echo "
			<div id='submenu'>
				<a href='admin.php'>Admin</a> &gt; 
				<a href='admin_category.php'>Categories</a> &gt; 
				<a href='admin_pages.php'>".$_SESSION['current_categorie_name']."</a>
			</div>
			<div class='content_group'>
				<p>Here are the pages in categorie <b>".$_SESSION['current_categorie_name']."</b>.<br>
				You can directly toggle their online/offline status or delete it.<br>
				Click the name of a page to edit it.</p><br><br>";
		
		// Display pages in category
		$sql = "select id,titre,publie from pages where categorie=".$_SESSION['current_categorie_id']." order by publie desc";
		$res = SQLquery ( $db, $sql );
		while ( $row = getLine ( $res ) ){
			echo "
				<div class='icon page";
			if ( !$row["publie"] ) echo " offline ";
			echo "' id='page_".$row["id"]."' title='Edit page #".$row["id"]." : ".$row["titre"]."'>
					<div class='txt'>
						<div class=''>
							<a href='admin_pages.php?togpage=".$row["id"]."&togs=".(($row["publie"]+1)%2)."' title='Toggle status'><img src='img/toggle.gif' height='16'/></a>
							<a href='admin_pages.php?delpage=".$row["id"]."' title='DELETE page #".$row["id"]." : ".$row["titre"]."'><img src='img/delete.gif'/></a></div>
						<a href='admin_page.php?id=".$row["id"]."&n=".$row["titre"]."'>".teaser($row["titre"],32)."</a>
				</div></div>";
		}
		
		// Icon "Create New page"
		echo "
				<div class='icon page new' title='Create a new page'>
					<div class='txt'><br><img src='img/new.png' width='52' alt=''/><br><a href='admin_page.php?new=1'>new page</a></div></div>
			</div>";
		
		
	}
	require_once("admin_foot.php");
?>