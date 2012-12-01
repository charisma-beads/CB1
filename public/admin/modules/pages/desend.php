<?php // page_content_overview.php (administration side) Friday, 8 April 2005
// This is the page content overview page for the admin side of the site.

// Set the page title.
$page_title = "Page Content";

// Include configuration file for error management and such.
include_once ($_SERVER['DOCUMENT_ROOT'] . '/admin/includes/admin_includes.php');

// Print a message based on authentication.
if (!$authorized) {
    echo '<p>Please enter a valid username and password! Click <a href="./index.php">here</a> to try again!<p>';
} else { 
	if (isset ($_GET['lid'])) {
	
		$query = "
			SELECT links_id, sort_order, parent_id
			FROM menu_links
			WHERE links_id={$_GET['lid']}
			";
		
		$result = mysql_query ($query);
		$place = mysql_result ($result, 0, "sort_order");
		$id = mysql_result ($result, 0, "links_id");
		$p_id = mysql_result ($result, 0, "parent_id");
		
		$query="
			SELECT MAX(sort_order) 
			FROM menu_links 
			WHERE parent_id=$p_id
			";
		$result = mysql_query($query);
		$max_place = mysql_result($result,0,"max(sort_order)");
		
		if ($place != $max_place) {
			$place_m = $place + 1;
		
			$query = "
				UPDATE menu_links
				SET sort_order='$place'
				WHERE sort_order='$place_m'
				AND parent_id=$p_id
				";
			$result = mysql_query ($query);
			print $query;
			
			$query = "
				UPDATE menu_links
				SET sort_order='$place_m'
				WHERE links_id=$id
				";
			$result = mysql_query ($query);
			print $query;
		}
		ob_end_clean ();
		header ("Location: $https/admin/modules/pages/index.php");
		exit ();
		
	} else {
	 	ob_end_clean ();
		header ("Location: $https/admin/modules/pages/index.php");
		exit ();
	}

}

// Include the HTML footer.
include_once ($_SERVER['DOCUMENT_ROOT'] . '/admin/includes/html_bottom.php');

?>