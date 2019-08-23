<?
	// Admin page settings
	session_start();
	$_SESSION['current_section'] = "elmt";
	require_once("admin_head.php");
	if ( is_auth() ){
		
		// ELEMENT ADMINISTRATION
		/////////////////////////
		
		// Modify element : general infos
		if ( $_POST["modif_elmt"] ){
			$elmt_nom = htmlentities ( $_POST['nom'], ENT_QUOTES );
			$elmt_desc = htmlentities ( $_POST['description'], ENT_QUOTES );
			$elmt_etat = $_POST['active'];
			$sql = "update elements set nom='".$elmt_nom."', description='$elmt_desc', actif='$elmt_etat' where id=".$_SESSION['current_element_id'];
			SQLquery ( $db, $sql );
		}
		// Modify element's parmson : description of fields
		if ( $_POST["modif_elmt_parmson"] ){
			$i = 1; $json = "{";
			while ( $nom = $_POST["parm".$i] ){
				$json .= "\"".$_POST["parm".$i]."\":\"".$_POST["type".$i]."\",";
				$i++;
			}
			$json = substr($json,0,strlen($json)-1)."}";
			$sql = "update elements set parmson_conf='".$json."' where id=".$_SESSION['current_element_id'];
			SQLquery ( $db, $sql );
		}
		
		
		
		
		// Erase previously chosen file ID
		$_SESSION['current_elmtfile_id'] = 0;
		$_SESSION['current_elmtfile_path'] = "";
		
		// Save chosen element ID
		/////////////////////////
		$elmt_id = $_GET['id'];
		if ( strlen($elmt_id) > 0 ) {
			$_SESSION['current_element_id'] = $elmt_id;
			$_SESSION['current_element_name'] = $_GET['n'];
		}
		
		// Display navigation submenu
		echo "
			<div id='submenu'>
				<a href='admin.php'>Admin</a> &gt; 
				<a href='admin_elements.php'>Elements</a> &gt; 
				<a href='admin_element.php'>".$_SESSION['current_element_name']."</a>
			</div>";
			
		
		// Collect info from page to edit
		$sql = "select * from elements where id=".$_SESSION['current_element_id'];
		$res = SQLquery ( $db, $sql );
		$row_elmt = getLine ( $res );
		
		// Display element edition form
?>		<div class="content_group elmt">
			<form id="elmt_edit" action="admin_element.php" method="post">
				<table><tr><td>
					<input type='hidden' name='modif_elmt' value='1' />
					<b>Element name</b><br>
					<input type='text' name='nom' value='<? echo $row_elmt['nom']; ?>' /><br>
					<span class="caption">name identifying the element</span><br><br>
					
					<b>Element description</b><br>
					<input type='text' name='description' value='<? echo $row_elmt['description']; ?>' /><br>
					<span class="caption">explain what this element is for</span><br><br>
				</td><td>
					<b>Element activation status</b><br>
					<input type="radio" name="active" value="0" <? if (!$row_elmt['actif']) echo " checked=checked"; ?>> <span style="color:#600;">Inactive</span> &nbsp; &nbsp; &nbsp; 
					<input type="radio" name="active" value="1" <? if ($row_elmt['actif']) echo " checked=checked"; ?>> <span style="color:#060;">Active</span><br>
					<span class="caption">indicate wether the element will be loaded in pages or not</span><br><br>
					
					<input type='submit' value='Update element info' style='width:140px;height:60px;align:right;' />
				</td></tr></table>
			</form>
		</div>
		
		<!-- Fields of information used by this element -->
		<div class="content_group elmt">
			<p>This table lists the parameters this element need for running.<br>
			A parameter is defined by its name and the type of field you need in a form to fill it.<br>
			You can add, edit and delete parameters. When you're set, click the update button to save your parameters in a json string.</p><br>
			<form id="elmt_parm_edit" action='admin_element.php' method='POST'>
				<input type='hidden' name='modif_elmt_parmson' value='1' />
				<table><tr><td>
				
					<table id='table_parmconf' cellspacing=0><tr>
						<td style='background-color:#eee;color:#999;'>#</td><td style='background-color:#eee;color:#999;'>parameter's name</td><td style='background-color:#eee;color:#999;'>parameter's type</td><td style='background-color:#eee;color:#999;'>&nbsp;</td>
					</tr>
<?			
					// Read element's parmson (parmson = json of parameters)
					$json_conf = json_decode ( $row_elmt['parmson_conf'], true );
					
					// For each parameter
					$i = 1;
					if ( sizeof($json_conf) ){
						foreach ( $json_conf as $k=>$v ){
							
							// Display field in edition mode
							echo "
					<tr id='row$i'><td>$i.</td>
					<td><input type='text' name='parm".$i."' value='".$k."' /></td>
					<td><select name='type".$i."'>
						<option id='text'";
							if ($v=="text") echo " selected";
							echo ">text</option>
						<option id='textarea'";
							if ($v=="textarea") echo " selected";
							echo ">textarea</option>
						<option id='list'";
							if ($v=="list") echo " selected";
							echo ">list</option>
					</select></td>
					<td><img src='img/delete.gif' onclick='delete_element_parmconf(".$i.")' title='DELETE this parameter' style='cursor:pointer;' /></td></tr>";
							$i++;
						}
					}
					// Create new parameter
					echo "<tr><td colspan=4><img src='img/new.png' width=16 title='ADD new parameter' onclick='add_element_parmconf($i)' style='cursor:pointer;' /></td></table>";
?>				
				</td><td>
					<input type='submit' value='Update parameters' style='width:140px;height:60px;align:right;' />
				</td></tr></table>
			</form>
		</div>
		
		<!-- Links to files running the element -->
		<div class="content_group elmt">
			<p>Edit the code running this element in this 3 files :</p>
			<table cellspacing=10 id="element_files"><tr>
				<td onclick="window.location.assign('admin_element_file.php?f=php');"><a href="admin_element_file.php?f=php"><img src='img/file-php.png'/> server-side code</a><br><br>
				When a segment of this element is displayed,<br>
				the _start() function is called with a json<br>
				containing the segment's content as parameters.<br>
				In this function, code the html generation you<br>
				want for this element.</td>
				<td onclick="window.location.assign('admin_element_file.php?f=css');"><a href="admin_element_file.php?f=css"><img src='img/file-css.png'/> style sheet</a><br><br>
				Write the css rules to display the content<br>
				for this element. You can also write<br>
				rules describing how the field will be<br>
				displayed in edition form.</td>
				<td onclick="window.location.assign('admin_element_file.php?f=js');"><a href="admin_element_file.php?f=js"><img src='img/file-js.png'/> client-side code</a><br><br>
				This javascript file is called at the<br>
				end of every pages containing a segment<br>
				of this element. Write here the client-side<br>
				behavior of the element.
				</td>
			</tr></table>
		</div>
		
<?		require_once("admin_foot.php");
	}
?>