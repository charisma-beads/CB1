<?php // view_order.php Tuesday, 3 May 2005
// This is the Tax page for admin.

// Set the page title.
$page_title = "Tax";

// Include configuration file for error management and such.
include_once ($_SERVER['DOCUMENT_ROOT'] . '/admin/includes/admin_includes.php');

// Print a message based on authentication.
if (!$authorized) {
    echo '<p>Please enter a valid username and password! Click <a href="./index.php">here</a> to try again!<p>';
} else {
	print "<table><tr><td valign=\"top\">";
	print "<p><a href=\"../index.php\">Back to Overview</a></p>";
	
	require_once ('menu_links.php');
	
	print "<table class=\"todo_menu\">";
	print "<tr><td height=\"25\" class=\"Link\" onMouseOver=\"this.className='bodyLink'\" onMouseOut=\"this.className='Link'\"><a href=\"add_tax_code.php\" class=\"Link\">Add New Tax Code</a></td></tr>";
	
	print "</table>";	
		
	print "</td><td style=\"padding-left:100px;padding-right:100px;\" valign=\"top\">";
    
    // Number of records to show.
    $display = 50;
    
    // Determine how many pages there are.
    if (isset($_GET['np'])) {
        $num_pages = $_GET['np'];
    } else { // Need to determine.
        $query = "SELECT tax_code_id FROM tax_codes ";
		
        $query_result = mysql_query ($query);
        $num_records = mysql_num_rows ($query_result);
        
        if ($num_records > $display) { // More than 1 page.
            $num_pages = ceil ($num_records/$display);
        } else {
            $num_pages = 1;
        }
    }
    
    // Determine where in the database to start returning results.
    if (isset($_GET['s'])) { // Already been determined.
        $start = $_GET['s'];
    } else {
        $start = 0;
    }
	
	
    
    // Make the query.
    $query = "
		SELECT tax_code_id, tax_rate, tax_code, description
		FROM tax_codes, tax_rates
		WHERE tax_codes.tax_rate_id=tax_rates.tax_rate_id
		LIMIT $start, $display";
    $result = mysql_query ($query); // Run the query.
    $num = mysql_num_rows ($result); // How many users are there?
    
    if ($num > 0) { // If it ran OK, display the records.

        echo "<br />";
		
		print "<div class=\"box\">";

        // Make the links to other pages, if necessary.
        if ($num_pages > 1) {
		
            echo '<p align="center">';
            // Determine what page the script is on.
            $current_page = ($start/$display) + 1;
            
            // If it's not the first page, make a previous button.
            if ($current_page != 1){
                echo '<a href="index.php?s=' . ($start - $display) . '&np=' . $num_pages . '">Previous</a> ';
            }
            
            // Make all the numbered pages.
            for ($i = 1; $i <= $num_pages; $i++) {
                if ($i != $current_page) {
                    echo '<a href="index.php?s=' . (($display * ($i - 1))) . '&np=' . $num_pages . '">' . $i . '</a> ';
                } else {
                    echo $i . ' ';
                }
            }

        // If it's not the last page, make a Next button.
        if ($current_page != $num_pages) {
            echo '<a href="index.php?s=' . ($start + $display) . '&np=' . $num_pages . '">Next</a>';
            
        }

        echo '</p>';

        } // End of links section.
	   	
	   		
	   	
        // Table header.
        echo '<table align="center" cellspacing="2" cellpadding="2">
        <tr bgcolor="#EEEEEE"><td align="center"><b>Tax Code</b></td><td align="center"><b>Tax Rate</b></td><td align="center"><b>Description</b></td><td><b> </b></td><td><b> </b></td></tr>';

        // Fetch and print all the records.
        $bg = '#EEEEEE'; // Set the background colour.
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) { 
			// Format tax rate.
			$tax_rate = substr ($row['tax_rate'], 2);
			$tax_rate = substr ($tax_rate ,0, -1) . "." . substr ($row['tax_rate'], -1);
			$row['tax_rate'] = number_format ($tax_rate, 2); 
			
			
            $bg = ($bg=='#EEEEEE' ? '#FFFFFF' : '#EEEEEE'); // Switch the background colour.
            print "<tr bgcolor=\"$bg\">";
			print "<td align=\"left\">{$row['tax_code']}</td>";
			print "<td align=\"left\">{$row['tax_rate']}%</td>";
			print "<td align=\"left\">{$row['description']}</td>";
			print "<td align=\"center\"><a href=\"edit_tax_code.php?tcid={$row['tax_code_id']}\" /><img src=\"/admin/images/edit.png\" alt=\"Edit Tax Code\"/></a></td>";
			print "<td align=\"center\"><a href=\"delete_tax_code.php?tcid={$row['tax_code_id']}\" /><img src=\"/admin/images/delete.png\" alt=\"delete Tax Code\"/></a></td>";
			print "</tr>";
        }

        echo '</table>'; // Close the table.

        // Make the links to other pages, if necessary.
        if ($num_pages > 1) {
            echo '<p align="center">';
            // Determine what page the script is on.
            $current_page = ($start/$display) + 1;
            
            // If it's not the first page, make a previous button.
            if ($current_page != 1){
                echo '<a href="index.php?s=' . ($start - $display) . '&np=' . $num_pages . '">Previous</a> ';
            }
            
            // Make all the numbered pages.
            for ($i = 1; $i <= $num_pages; $i++) {
                if ($i != $current_page) {
                    echo '<a href="index.php?s=' . (($display * ($i - 1))) . '&np=' . $num_pages . '">' . $i . '</a> ';
                } else {
                    echo $i . ' ';
                }
            }

        // If it's not the last page, make a Next button.
        if ($current_page != $num_pages) {
            echo '<a href="index.php?s=' . ($start + $display) . '&np=' . $num_pages . '">Next</a>';
            
        }

        echo '</p></div>';

        } // End of links section.
		
		print "</td></tr></table>";
        mysql_free_result ($result); // Free up the resoures.

    } else { // If there are no registered users.
		
        echo '<h3>There are currently no post zones.</h3>';
		print "</td></tr></table>";
    }
	

	mysql_close(); // Close the database connection.

}

// Include the HTML footer.
include_once ($_SERVER['DOCUMENT_ROOT'] . '/admin/includes/html_bottom.php');

?>