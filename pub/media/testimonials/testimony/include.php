<?php

$infoFile = '/web/magicmurals/prod/images/customer_gallery/info.txt';
$lines = file($infoFile);   
$meta_data = array();
$files = array();
for($i=1;$i<count($lines);$i++){
    list($filename,$name,$headline,$description,$type,$mural) = split("\t",$lines[$i]);

    $thumb = str_replace(".", "thumb.", $filename);
   
    if ($filename == "" || $filename == '.' || $filename == '..') { continue; }
    
    $meta_data[$filename."-name"]= $name;
    $meta_data[$filename."-headline"]= $headline;
    $meta_data[$filename."-description"]= $description;
    $meta_data[$filename."-mural"]= $mural;
    $type_d = "";
    if ($type == 'B') {
	$typd_d = 'business';
    } else if ($type_d == 'R') {
	$typd_d = 'residential';
    }
	
print "<li class='$typd_d'><a href='#'><img class='gallery-thumb' src='/images/customer_gallery/$thumb' alt='$filename' data-large='/images/customer_gallery/$filename' data-headline='$headline' data-author='$name' data-description='$description' $data-mural='$mural' /></a></li>";

}


?>

