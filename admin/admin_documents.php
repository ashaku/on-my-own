<?
	// Admin page settings
	session_start();
	$_SESSION['current_section'] = "docs";
	require_once("admin_head.php");
	if ( is_auth() ){
		
		// DOCUMENTS ADMINISTRATION
		///////////////////////////
		// Add new document
		if ( $_POST['newdoc'] ){
			
			// insert row in database
			$name = basename ( $_FILES['document']['name'] );
			$taille = filesize($_FILES['document']['tmp_name']);
			$sql = "insert into documents (path,taille,date_upload,public) values ('$name','".round($taille/1000,1)."','".date("Y-m-d H:i:s")."',0)";
			SQLquery ( $db, $sql );
			
			// copy file
			if ( move_uploaded_file($_FILES['document']['tmp_name'], "../doc/".$name) );
				else	print_r($_FILES);
		}
		// Toggle document status (public/private)
		if ( $_GET['togdoc'] ){
			$sql = "update documents set public=".$_GET['togs']." where id=".$_GET['togdoc'];
			SQLquery ( $db, $sql );
		}
		// Delete a document
		if ( $_GET['deldoc'] ){
			
			// delete line from database
			$sql = "delete from documents where id=".$_GET['deldoc'];
			SQLquery ( $db, $sql );
			
			// erase file
			unlink ( "../doc/".$_GET['path'] );
		}
		
		
		// Display navigation submenu
		echo "
			<div id='submenu'>
				<a href='admin.php'>Admin</a> &gt; 
				<a href='admin_documents.php'>Documents</a>
			</div>";
			
			
		// Display list of existing documents
		echo "
			<div class='content_group elmt'>
				<p>This section of admin allows you to upload files and link them in your pages.</p>
				<br>
				<table cellspacing=0>
					<tr><td style='background-color:#eee;color:#999;'>file name</td>
					<td style='color:#999;background-color:#eee;'>size</td>
					<td style='color:#999;background-color:#eee;'>uploaded on</td>
					<td style='background-color:#eee;color:#999;'>&nbsp;</td></tr>";
		$sql = "SELECT * FROM documents
				order by public desc,date_upload desc";
		$res = SQLquery ( $db, $sql );
		while ( $row = getLine ( $res ) ){
			echo "
					<tr><td class='public".$row['public']."'>";
			echo "<a href='../doc/".$row['path']."'>".$row['path']."</a></td>
						<td class='public".$row['public']."'>".display_filesize($row['taille'])."</td>
						<td class='public".$row['public']."'>".$row['date_upload']."</td>
						<td class='public".$row['public']."'>
							<a href='admin_documents.php?togdoc=".$row["id"]."&togs=".(($row["public"]+1)%2)."' title='Toggle status'><img src='img/toggle.gif' height='16'/></a> &nbsp; 
							<a href='admin_documents.php?deldoc=".$row["id"]."&path=".$row["path"]."' title='DELETE document #".$row["id"]." : ".$row["path"]."'><img src='img/delete.gif'/></a></td></tr>";
		}
		
		// New document
		echo "		<tr><td colspan='4'>&nbsp;</td></tr><tr><td><form action='admin_documents.php' method='POST' enctype='multipart/form-data'>
						<input type='hidden' name='newdoc' value=1 />
						<input type='file' name='document' /></td><td>
						&nbsp;</td><td>&nbsp;</td><td>
						<input type='submit' value='Upload document'</td>
					</tr></form>
				</table>
			</div>";
		
		
		require_once("admin_foot.php");
	}
	
	function display_filesize ( $size ){
		$t = array ( "Ko", "Mo", "Go" );
		$order = 0;
		while ( $size > 1024 ){
			$size /= 1024;
			$order++;
		}
		return round($size,1)." ".$t[$order];
	}
?>