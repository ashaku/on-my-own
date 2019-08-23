<?
	// Centralization of common queries
	
	
	function get_nav_elements(){
		$sql = "SELECT c.id cat,c.nom,p.id,p.titre
				FROM categories c
				left join pages p on p.categorie=c.id
				where p.publie=1
				order by c.id,p.date_modification desc";
	}
	
?>