<?php
// In PHP versions earlier than 4.1.0, $HTTP_POST_FILES should be used instead
// of $_FILES.

echo 'Upload result:<br>'; // At least one symbol should be sent to response!!!

$uploaddir = dirname($_SERVER['SCRIPT_FILENAME'])."/UploadedFiles/";

$target_encoding = "ISO-8859-1";
echo '<pre>';
if(count($_FILES) > 0)
{
	$arrfile = pos($_FILES);
	$uploadfile = $uploaddir . iconv("UTF-8", $target_encoding,basename($arrfile['name']));

	if (move_uploaded_file($arrfile['tmp_name'], $uploadfile))
	   echo "File is valid, and was successfully uploaded.\n";
}
else
	echo 'No files sent. Script is OK!'; //Say to Flash that script exists and can receive files

echo 'Here is some more debugging info:';
print_r($_FILES);

echo "</pre>";

?>