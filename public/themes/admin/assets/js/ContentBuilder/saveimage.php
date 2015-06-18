﻿<?php
header('Cache-Control: no-cache, must-revalidate');

//STEP 1: Specify storage folder
$dir = 'C:/ContentBuilder/assets/'; 

//STEP 2: Specify url path
$path = 'assets/'; 

//Read image
$count = $_REQUEST['count'];
$b64str = $_REQUEST['hidimg-' . $count]; 
$imgname = $_REQUEST['hidname-' . $count]; 
$imgtype = $_REQUEST['hidtype-' . $count]; 

//Generate random file name here
if($imgtype == 'png'){
	$image = $imgname . '-' . base_convert(rand(),10,36) . '.png'; 
} else {
	$image = $imgname . '-' . base_convert(rand(),10,36) . '.jpg'; 
}

//Save image

$success = file_put_contents($dir . $image, base64_decode($b64str)); 
if ($success === FALSE) {

  if (!file_exists($dir)) {
    echo "<html><body onload=\"alert('Saving image to folder failed. Folder ".$dir." not exists.')\"></body></html>";
  } else {
    echo "<html><body onload=\"alert('Saving image to folder failed. Please check write permission on " .$dir. "')\"></body></html>";
  }
    
} else {
  //Replace image src with the new saved file
  echo "<html><body onload=\"parent.document.getElementById('img-" . $count . "').setAttribute('src','" . $path . $image . "');  parent.document.getElementById('img-" . $count . "').removeAttribute('id') \"></body></html>";
}


?>