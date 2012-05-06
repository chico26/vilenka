<?php
/*
 * Created on Nov 18, 2011
 *
 * @author Sergio Morales López
 */
 
function display_child_nodes($nodes){
	$childs = (isset($nodes[0])) ? 'child_archivos' : '';
	echo "\n<ul class=\"tree_list\">\n";
	foreach ($nodes as $node){
		echo "<li>\n".$node->description;
		if (!isset($node[$childs])) display_child_nodes($node->$childs);;
		echo "\n</li>\n";
	}
	echo "</ul>";
	return;
}