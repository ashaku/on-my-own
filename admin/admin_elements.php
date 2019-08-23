<?
	// Admin page settings
	session_start();
	$_SESSION['current_section'] = "elmt";
	require_once("admin_head.php");
	if ( is_auth() ){
		
		// ELEMENTS ADMINISTRATION
		//////////////////////////
		// Create new element
		if ( $_POST['newel'] ){
			
			// insert row in database
			$name = $_POST['nom_element'];
			$description = $_POST['desc_element'];
			$sql = "insert into elements (nom,description,css,js,php,parmson_conf,actif) values ('$name','$description',0,0,0,'',0)";
			SQLquery ( $db, $sql );
			
			// create folder & files
			mkdir ( "../lib/$name" );
			$exts = array ( "js", "css", "php" );
			foreach ( $exts as $ext ){
				$file_name = "../lib/$name/".$name.".".$ext;
				$content = str_replace ( "[N]", $name, file_get_contents ( "../lib/elmt_templates/file.".$ext ) );
				save_in_file ( $content, $file_name );
			}
		}
		// Toggle element status (active/inactive)
		if ( $_GET['togel'] ){
			$sql = "update elements set actif=".$_GET['togs']." where id=".$_GET['togel'];
			SQLquery ( $db, $sql );
		}
		// Delete an element
		if ( $_GET['delel'] ){
			
			// delete line from database
			$sql = "delete from elements where id=".$_GET['delel'];
			SQLquery ( $db, $sql );
			
			// erase files & folder
			$name = $_GET['elname'];
			$exts = array ( "js", "css", "php" );
			foreach ( $exts as $ext )	unlink ( "../lib/$name/".$name.".".$ext );
			if (PHP_OS === 'Windows')	exec("rd /s /q {../lib/$name}");
			else 						exec("rm -rf {../lib/$name}");
		}
		
		
		// Erase previously chosen element ID
		$_SESSION['current_element_id'] = 0;
		$_SESSION['current_element_name'] = "*";
		
		// Erase previously chosen file ID
		$_SESSION['current_elmtfile_id'] = 0;
		$_SESSION['current_elmtfile_path'] = "";
		
		
		// Display navigation submenu
		echo "
			<div id='submenu'>
				<a href='admin.php'>Admin</a> &gt; 
				<a href='admin_elements.php'>Elements</a>
			</div>";
			
			
		// Display list of existing elements
		echo "
			<div class='content_group elmt'>
				<p>This section of admin allows you to administrate elements.<br>
				Elements are modules displaying content in pages.<br>
				Here you can create/edit elements and code the way they will display your content.</p>
				<br>
				<table cellspacing=0>
					<tr><td style='background-color:#eee;color:#999;'>name</td><td style='color:#999;background-color:#eee;'>description</td><td style='background-color:#eee;color:#999;'>&nbsp;</td></tr>";
		$sql = "SELECT * FROM elements
				order by actif desc";
		$res = SQLquery ( $db, $sql );
		while ( $row = getLine ( $res ) ){
			echo "
					<tr><td class='actif".$row['actif']."'>";
			echo "<a href='admin_element.php?id=".$row['id']."&n=".$row['nom']."'>";
			echo $row['nom'];
			echo "</a>";
			echo "</td>
						<td class='actif".$row['actif']."'>".$row['description']."</td>
						<td class='actif".$row['actif']."'>
							<a href='admin_elements.php?togel=".$row["id"]."&togs=".(($row["actif"]+1)%2)."' title='Toggle status'><img src='img/toggle.gif' height='16'/></a> &nbsp; 
							<a href='admin_elements.php?delel=".$row["id"]."&elname=".$row["nom"]."' title='DELETE element #".$row["id"]." : ".$row["nom"]."'><img src='img/delete.gif'/></a></td></tr>";
		}
		echo "		<tr><td colspan='3'>&nbsp;</td></tr><tr><td><form action='' method='POST'>
						<input type='hidden' name='newel' value=1 />
						<input type='text' name='nom_element' value='new element' size='24' onfocus='this.value=\"\"' /></td><td>
						<input type='text' name='desc_element' value='description' size='80' onfocus='this.value=\"\"' /></td><td>
						<input type='submit' value='Create new element'</td>
					</tr></form>
				</table>
			</div>";
		
		
		require_once("admin_foot.php");
	}
?>