/* MENU ADMIN CLIENT SIDE CODE */


// Define element menu links for files only after an element is chosen


// Element Edition
//////////////////

// Delete field in parmson_conf
function delete_element_parmconf(i){
	var ligne = document.getElementById("row"+i);
	document.getElementById("table_parmconf").deleteRow(ligne.rowIndex);
}


// Add new field in parmson_conf
function add_element_parmconf(i){
	var tableau = document.getElementById("table_parmconf");
	var ligne = document.getElementById("row"+i);
	var ligne = tableau.insertRow(i);
	
    var colonne1 = ligne.insertCell(0);
    colonne1.innerHTML += i+".";

    var colonne2 = ligne.insertCell(1);
    colonne2.innerHTML += "<input type='text' name='parm"+i+"' value='new parm' />";
	
    var colonne3 = ligne.insertCell(2);
    colonne3.innerHTML += "<select name='type"+i+"'><option id='text'>text</option><option id='textarea'>textarea</option><option id='list'>list</option></select>";
	
    var colonne4 = ligne.insertCell(3);
    colonne4.innerHTML += "<img src='img/delete.gif' onclick='delete_element_parmconf("+i+")' title='DELETE this parameter' style='cursor:pointer;' />";
}