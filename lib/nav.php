<?
	// NAV FILE : 
	// PHP code generating HTML code published in the <nav> section
	
	
	// Display navigation menu
	function generate_site_nav ( $db ){
		
		// Language choice will be here
		
		// Site home
		$html .= "<a href='1-home.html'>Site name</a>";
		
		// Search Engine will be here
		
		// Query all pages, grouped by category, sorted by date
		$sql = "	SELECT c.id cat,c.nom,p.id,p.titre
						FROM categories c
						left join pages p on p.categorie=c.id
						where p.publie=1
						and c.id>2
						order by c.id,p.date_modification desc";
		$res_nav = SQLquery ( $db, $sql );
		$old_cat = "";
		
		// Display pages by category
		$html .= "<ul><li><ul><li>";
		while ( $row = getLine($res_nav) ){
			
			// check for new category
			if ( $row['cat'] != $old_cat ){
				$html .= "</li></ul><li>".$row['nom']."<ul>";
				$old_cat = $row['cat'];
			}
			
			// Display page
			$html .= "<li><a href='".$row['id']."-".url_name($row['titre']).".html'>".$row['titre']."</a></li>";
			
		}
		$html .= "</ul></li></ul>";
		
		return "<nav>".$html."</nav>";
	}
?>