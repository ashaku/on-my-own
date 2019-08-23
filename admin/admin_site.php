<?
	// Admin page settings
	session_start();
	$_SESSION['current_section'] = "site";
	require_once("admin_head.php");
	if ( is_auth() ){
		
		// Erase previously chosen file ID
		$_SESSION['current_sitefile_id'] = 0;
		$_SESSION['current_sitefile_path'] = "";
?>
			<!-- Display navigation submenu -->
			<div id="submenu">
				<a href='admin.php'>Admin</a> &gt; 
				<a href='admin_site.php'>Site</a>
			</div>
			
			<!-- Display site files -->
			<div class='content_group'>
				<p>This section of admin allows you to fully customize your website's look by editing the 6 following files.<br>
				Here, you'll be able to modify the html code displayed in main sections : header, nav &amp; footer.<br>
				You can also edit the functions used client-side &amp; server-side.<p>
				<div class='admin_site_btn' onclick="window.location.assign('admin_site_file.php?f=head');" title='Edit php code generating the <header> section'>
					<a href="admin_site_file.php?f=head"><img src='img/file-php.png'/> <span class="system">/lib/header.php</span></a><br>
					<p>This php file contains a function <span class="system">generate_site_header()</span> called for displaying the &lt;header&gt; html section of website.
					<br>Modify the string variable <span class="system">$html</span> (line 7) to customize the header of your website.</p>
				</div>
				<div class='admin_site_btn' onclick="window.location.assign('admin_site_file.php?f=nav');" title='Edit php code generating the <nav> section'>
					<a href="admin_site_file.php?f=nav"><img src='img/file-php.png'/> <span class="system">/lib/nav.php</span></a><br>
					<p>This php file contains a function <span class="system">generate_site_nav()</span> called for displaying the &lt;nav&gt; html section of website.
					<br>It receives the <span class="system">$db</span> token in order to complete database queries.
					<br>By default, it displays a list of links to online pages, grouped by category and sorted by date of modification.
					<br>Modify the string variable <span class="system">$html</span> to customize the navigation menu of your website.
					<br>By default, the menu is vertical, you can defined it horizontal by modifying ths <span class="system">css</span> file below.</p>
				</div>
				<div class='admin_site_btn' onclick="window.location.assign('admin_site_file.php?f=foot');" title='Edit php code generating the <footer> section'>
					<a href="admin_site_file.php?f=foot"><img src='img/file-php.png'/> <span class="system">/lib/footer.php</span></a><br>
					<p>This php file contains a function <span class="system">generate_site_footer()</span> called for displaying the &lt;footer&gt; html section of website.
					<br>By default, it is an horizontal list of links to common pages : home, about &amp; contact.
					<br>Modify the string variable <span class="system">$html</span> (line 7) to customize the footer of your website.</p>
				</div>
				<div class='admin_site_btn' onclick="window.location.assign('admin_site_file.php?f=php');" title='Edit server-side php code of website'>
					<a href="admin_site_file.php?f=php"><img src='img/file-php.png'/> <span class="system">/lib/toolbox.php</span></a><br>
					<p>This php file contains a set of functions you can call elsewhere in your code.<br>
					It's server-side code you can use in all previous files for instance, but also in elements.</p>
				</div>
				<div class='admin_site_btn' onclick="window.location.assign('admin_site_file.php?f=css');" title='Edit stylesheet of website'>
					<a href="admin_site_file.php?f=css"><img src='img/file-css.png'/> <span class="system">/lib/site.css</span></a><br>
					<p>This css file contains css rules for displaying. Follow the comments to edit the part you need.<br>
					Don't forget to check the technical documentation to get use to the site's basic structure.</p>
				</div>
				<div class='admin_site_btn' onclick="window.location.assign('admin_site_file.php?f=js');" title='Edit client-side javascript code of website'>
					<a href="admin_site_file.php?f=js"><img src='img/file-js.png'/> <span class="system">/lib/site.js</span></a><br>
					<p>This javascript file is included at the end of every pages.<br>
					Add there any client-side behavior you need.</p>
				</div>
			</div>
		
<?		require_once("admin_foot.php");
	}
?>