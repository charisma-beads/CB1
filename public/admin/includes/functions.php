<?php 
/*
 * functions.php
 *
 * Copyright (c) 2010 Shaun Freeman <shaun@shaunfreeman.co.uk>.
 *
 * This file is part of Charisma Beads.
 *
 * Charisma Beads is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Charisma Beads is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Charisma Beads.  If not, see <http ://www.gnu.org/licenses/>.
 */

// Set include paths.
set_include_path(get_include_path().PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/admin/includes/classes'
    .PATH_SEPARATOR.
    $_SERVER['DOCUMENT_ROOT'].'/../php'
);

// Auto load classes.
function __autoload($class_name)
{
	$class_name = explode("_", $class_name);
	$class_path = null;
	foreach ($class_name as $key => $value) {
		$class_path .= '/'.$value;
	}
	$class_path = substr($class_path, 1);
	require ($class_path . '.php');
}

//$errors = ErrorLogging::getInstance();
?>