<?php
header('Content-type: image/jpeg');
$imageOrig = imagecreatefromjpeg($_REQUEST['imgorig']); 
$orig_width = imagesx($imageOrig);
$orig_height = imagesy($imageOrig);


//echo "origen".$imageOrig;


$max = $_REQUEST['max'];

	if($orig_width > $orig_height){
		$nuevo_width = $max;
		$nuevo_height = intval(($orig_height/$orig_width)*$max);
	} else {
		$nuevo_width = intval(($orig_width/$orig_height)*$max);
		$nuevo_height = $max;
    }

	$nuevaImg = imagecreatetruecolor($nuevo_width,$nuevo_height); 
	imagecopyresampled($nuevaImg,$imageOrig,0,0,0,0,$nuevo_width,$nuevo_height,$orig_width,$orig_height);
	imagegammacorrect($nuevaImg,12.50,15);
	imagejpeg($nuevaImg,'',100);

imageDestroy($nuevaImg);
?>