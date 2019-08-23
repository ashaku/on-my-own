<?
	// FOOTER FILE : 
	// PHP code generating HTML code published in the <footer> section
	
	
	function generate_site_footer(){
		$html = "<ul>
					<li><a href='1-home.html'>home</a></li>
					<li><a href='2-about.html'>about</a></li>
					<li><a href='3-contact.html'>contact</a></li>
					<li>*</li>
					<li><a href='http://ashaku.free.fr'>Powered by JMRE</a></li>
				</ul>";
		
		return "<footer>".$html."</footer>";
	}
?>