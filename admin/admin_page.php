<?
	// Admin page settings
	session_start();
	$_SESSION['current_section'] = "pages";
	require_once("admin_head.php");
	if ( is_auth() ){
		
		// Call all active elements server-side code
		$sql = "select nom from elements where actif=1";
		$res = SQLquery ( $db, $sql );
		while ( $row = getLine ( $res ) ) require_once ( "../lib/".$row["nom"]."/".$row["nom"].".php" );
		
		// PAGE ADMINISTRATION
		//////////////////////
		// Create a new Page
		if ( $_GET["new"] ){
			$sql = "select max(id) from pages";
			$res = SQLquery ( $db, $sql );
			$row = getLine ( $res );
			$page_id = $row[0] + 1;
			$np_date = date ("Y-m-d H:i:s");
			$res = SQLquery ( $db, "	insert into pages (id,titre,description,tags,categorie,date_creation,date_modification) 
										values ($page_id,'New Page','','',".$_SESSION['current_categorie_id'].",'".$np_date."','".$np_date."')" );
			$_SESSION['current_page_id'] = $page_id;
			$_SESSION['current_page_name'] = "New Page";
		}
		// Modify Page : general infos
		if ( $_POST["modif_site"] ){
			$page_titre = htmlentities ( $_POST['titre'], ENT_QUOTES );
			$page_desc = htmlentities ( $_POST['description'], ENT_QUOTES );
			$page_tags = htmlentities ( $_POST['tags'], ENT_QUOTES );
			$page_cat = $_POST['categorie'];
			$page_pub = $_POST['publie'];
			$sql = "update pages set titre='".$page_titre."', description='$page_desc', tags='$page_tags', categorie=$page_cat, publie='$page_pub', date_modification='".date("Y-m-d H:i:s")."' where id=".$_SESSION['current_page_id'];
			SQLquery ( $db, $sql );
		}
		// Add new empty segment to page
		if ( $_POST["add_segment"] ){
			$element = $_POST["element"];
			$sql = "select parmson_conf from elements where id=$element";
			$res = SQLquery ( $db, $sql );
			$row = getLine ( $res );
			$parmson = $row[0];
			//$page_id = $_POST["page_id"];
			$order = $_POST["order"];
			$sql = "insert into segments (page,element,parmson,ordre) values (".$_SESSION['current_page_id'].",$element,'$parmson',$order)";
			SQLquery ( $db, $sql );
		}
		// Update existing segment
		if ( $_POST["modif_seg"] ){
			$num_field = 0;
			while ( $nom = $_POST["field_".$num_field] ){
				$fields[$nom] = htmlentities (  $_POST[$nom] , ENT_QUOTES );
				$num_field++;
			}
			$parmson = "{";
			foreach ( $fields as $k => $v ){
				$parmson .= "\"$k\":\"$v\",";
			}
			$parmson = substr($parmson,0,strlen($parmson)-1);
			$parmson .= "}";
			$sql = "update segments set parmson='$parmson' where id=".$_POST["modif_seg"];
			SQLquery ( $db, $sql );
			$sql = "update pages set date_modification='".date("Y-m-d H:i:s")."' where id=".$_SESSION['current_page_id'];
			SQLquery ( $db, $sql );
		}
		// Delete segment
		if ( $_GET["delseg"] ){
			//$page_id = $_GET['id'];
			$sql = "delete from segments where id=".$_GET["delseg"];
			SQLquery ( $db, $sql );
			$sql = "update pages set date_modification='".date("Y-m-d H:i:s")."' where id=".$_SESSION['current_page_id'];
			SQLquery ( $db, $sql );
		}
		
		
		// Save chosen page ID
		//////////////////////
		$page_id = $_GET['id'];
		if ( strlen($page_id) > 0 ) {
			$_SESSION['current_page_id'] = $page_id;
			$_SESSION['current_page_name'] = $_GET['n'];
		}
		
		// Collect info from page to edit
		$sql = "select * from pages where id=".$_SESSION['current_page_id'];
		$res = SQLquery ( $db, $sql );
		$row_page = getLine ( $res );
		
		// Display navigation submenu
		echo "
			<div id='submenu'>
				<a href='admin.php'>Admin</a> &gt; 
				<a href='admin_category.php'>Categories</a> &gt; 
				<a href='admin_pages.php'>".$_SESSION['current_categorie_name']."</a> &gt; 
				<a href='admin_page.php'>".$_SESSION['current_page_name']."</a>
			</div>";
		
?>		
		<!-- Edition form for general informations about page -->
		<div class="content_group">
		
			<form id="page_edit" action="" method="post">
				<input type='hidden' name='modif_site' value='1' />
				
				<table><tr><td>
					<b>Page title</b><br>
					<input type='text' name='titre' value='<? echo $row_page['titre']; ?>' /><br>
					<span class="caption">will be use to create page url, links to this page in website and of course the html tag 'title'</span><br><br>
					
					<b>Page description</b><br>
					<input type='text' name='description' value='<? echo $row_page['description']; ?>' /><br>
					<span class="caption">will be used to set the meta tag 'description'</span><br><br>
					
					<b>Page tags</b><br>
					<input type='text' name='tags' value='<? echo $row_page['tags']; ?>' /><br>
					<span class="caption">will be used to set the meta tag 'keywords'</span>
				</td><td style="width:60px;">&nbsp;</td><td>
					<b>Page Category</b><br>
					<select name="categorie">
					<?	
						$res = SQLquery ( $db, "select * from categories" );
						while ( $row = getLine ( $res ) ){
							echo "
						<option value='".$row['id']."'";
							if ($row['id']==$row_page['categorie']) echo " selected";
							echo ">".$row['nom']."</option>";
							
						}
					?>
					</select><br>
					<span class="caption">the page will appears in this category on the website</span><br><br>
					
					<b>Page publication status</b><br>
					<input type="radio" name="publie" value="0" <? if (!$row_page['publie']) echo " checked=checked"; ?>> <span style="color:#600;">Offline</span> &nbsp; &nbsp; &nbsp; 
					<input type="radio" name="publie" value="1" <? if ($row_page['publie']) echo " checked=checked"; ?>> <span style="color:#060;">Online</span><br>
					<span class="caption">indicate wether the page will appears online or not</span><br><br>
					
					<input type='submit' value='Update page info' style='width:140px;height:60px;align:right;' />
				</td></tr></table>
			</form>
		</div>
		
		
		<!-- Page segments -->
		<div class="content_group">
		<script src='../ext/nicedit/nicEdit.js'></script>
		<div id="myNicPanel" style="width: 650px;"></div><br>
		<?
			// Collect segments in page
			$sql = "select s.id,s.parmson,e.nom,e.parmson_conf
					from pages p
					left join segments s on s.page=p.id
					left join elements e on s.element=e.id
					where p.id=".$_SESSION['current_page_id']."
					order by s.ordre";
			$res = SQLquery ( $db, $sql );
			$new_seg_order = 0;$i = 0;
			if ( nbRows ( $res ) > 0 ){
				
				// For each segment
				while ( $row = getLine ( $res ) ){
					$seg_id = $row['id'];
					if ( $seg_id ){
						$new_seg_order ++;
						echo "
						<div id='seg".$seg_id."' class='page_segment ".$row['nom']."'>
							<form action='admin_page.php' method='POST'>
								<input type='hidden' name='modif_seg' value=".$seg_id." />";
						
						// Display its content in edition mode
						$json_conf = json_decode ( $row['parmson_conf'], true );
						$json_parm = json_decode ( $row['parmson'], true );
						$num_field = 0; 
						foreach ( $json_conf as $k=>$v ){
							
							// Create fields according to the json config
							switch ( $v ){
								// Simple text
								case "text":	echo "
								<input type='hidden' name='field_$num_field' value='".$k."' />
								<input type='text' name='".$k."' value='".$json_parm[$k]."' size='100' />";	break;
								
								// Wyswyg
								case "textarea":
								$nom_textarea = $k.$seg_id.$num_field;
								echo "
								<input type='hidden' name='field_$num_field' value='".$k."' />
								<textarea name='".$k."' id='".$nom_textarea."_valide' style='display:none;'></textarea>
								<div class='rich_text_content' id='".$nom_textarea."_visible'>".html_entity_decode($json_parm[$k])."</div>";
								$ne[$i++] = $nom_textarea."_visible";
								break;
							}
							echo "<br>";
							$num_field ++;
						}
						echo "
								<div class='segment_menubar'>
									<font style='font-size:12px;color:#666;'>#".$row['nom']."</font> &nbsp; <a href='admin_page.php?id=$page_id&delseg=".$row['id']."' title='DELETE this segment'><img src='img/delete.gif'/></a> &nbsp; &nbsp; 
									<input type='image' src='img/save.png' onmouseover='$(\"#".$nom_textarea."_valide\").val($(\"#".$nom_textarea."_visible\").html())' title='SAVE segment' />
								</div>
							</form>
						</div>";
					}
				}
			}
			
			// Create new segment
			echo "
						<div id='new_seg' class='new_segment'>
							<form action='admin_page.php' method='POST'>
								<input type='hidden' name='add_segment' value='1' />
								<input type='hidden' name='page_id' value='$page_id' />
								<input type='hidden' name='order' value='$new_seg_order' />
								<select name='element'>";
			$sql = "select * from elements where actif=1";
			$res = SQLquery ( $db, $sql );
			while ( $row = getLine ( $res ) ){
				echo "
									<option value='".$row['id']."'>".$row['nom']."</option>";
			}
			echo "				</select> &nbsp; &nbsp; 
								<input type='submit' value='Create new segment' />
							</form>
						</div>
					</div>
<script type='text/javascript'>
	bkLib.onDomLoaded(function() {
		var myNicEditor = new nicEditor({iconsPath:'../ext/nicedit/nicEditorIcons.gif'});
		myNicEditor.setPanel('myNicPanel');";
			
			foreach ( $ne as $k ){
				echo "
		myNicEditor.addInstance('".$k."');";
			}
			echo "
	});
</script>";
		require_once("admin_foot.php");
		closeDatabase ( $db );
	}
?>