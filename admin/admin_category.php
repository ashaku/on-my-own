<?	
	// Admin page settings
	session_start();
	$_SESSION['current_section'] = "pages";
	require_once("admin_head.php");
	if ( is_auth() ){
		
		// Erase previously chosen category ID
		$_SESSION['current_categorie_id'] = 0;
		$_SESSION['current_categorie_name'] = "";
		$_SESSION['current_page_id'] = 0;
		$_SESSION['current_page_name'] = "";
		
		// CATEGORIES ADMINISTRATION
		////////////////////////////
		// Create a new category
		if ( $_POST['cat_name'] ){
			$sql = "insert into categories (nom) values ( '".$_POST['cat_name']."' )";
			SQLquery ( $db, $sql );
		}
		// Delete a category
		if ( $_GET['delcat'] ){
			// delete pages in category
			$delcat = $_GET['delcat'];
			$sql = "delete from pages where categorie=".$delcat;
			SQLquery ( $db, $sql );
			// delete category
			$sql = "delete from categories where id=".$delcat;
			SQLquery ( $db, $sql );
		}
		
		
		// Display navigation submenu
		echo "
			<div id='submenu'>
				<a href='admin.php'>Admin</a> &gt; <a href='admin_category.php'>Categories</a>
			</div>
			<div class='content_group'>
				<p>This section of admin allows you to administrate the content displayed on your website.<br>
				The content in website exists in the form of a page. Pages are stored in categories.<br>
				In this page, you can choose a category to view the pages it contains, create a new category or delete an existing one.</p><br><br>";
		
		// Display categories
		$sql = "select * from categories order by nom";
		$res = SQLquery ( $db, $sql );
		while ( $row = getLine ( $res ) ) echo "
				<div class='icon categorie' id='cat_".$row["id"]."' title='View pages in the category #".$row["id"]." : ".$row["nom"]."'>
					<div class='txt'>
						<a href='admin_category.php?delcat=".$row["id"]."' title='DELETE category #".$row["id"]." : ".$row["nom"]."'><img src='img/delete.gif'/></a>
						<br><br><a href='admin_pages.php?cat=".$row["id"]."&n=".$row["nom"]."'>".$row["nom"]."</a>
				</div></div>";
		
		// Icon "Create New category"
		echo "
				<div class='icon categorie new' title='Create a new category'>
					<div class='txt'>
						<form action='admin_category.php' method='POST' >
							<br><br><input type='text' name='cat_name' value='new category' size='12' onfocus='this.value=\"\";' style='z-index:4;' />
							<br><input type='image' src='img/new.png' width='40' alt='' style='margin-top:-2px;z-index:3;' />
						</form>
				</div></div>
			</div>";
	}
	require_once("admin_foot.php");
?>