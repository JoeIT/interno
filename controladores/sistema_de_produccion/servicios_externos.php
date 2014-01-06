<?php
header ("Content-type: image/jpeg");// La imagen que se va a crear es un JPG

$fotografia = $_GET['fotografia'];
$credencial = $_GET['credencial'];

$nombre = $_GET['nombre'];
$codigo = $_GET['codigo'];

$fuente = "./verdana.ttf";
$fuentecodigo = "./c39hrp24dhtt.ttf";

// Se definen las imagenes
$source = imagecreatefromjpeg($fotografia); // La fotografia es la fuente
$destination = imagecreatefromjpeg($credencial); // La credencial es el destino

// Las funciones imagesx e imagesy capturan el ancho y el largo de una imagen
$largeur_source = imagesx($source);
$hauteur_source = imagesy($source);

$largeur_destination = imagesx($destination);
$hauteur_destination = imagesy($destination);

//-------------------------------------------------------------
$new_x = 241;
$new_y = 238;

$img1 = imagecreatetruecolor($new_x, $new_y); //nuevas coordenadas
$img2 = $source; //imagecreatefromjpeg("imagen.jpg");
imagecopyresized($img1,$img2,0,0,0,0,$new_x, $new_y,$largeur_source,$hauteur_source);
//colocandole un borde
$color = imagecolorallocate($img1, 0, 0, 0);
imagerectangle($img1, 0, 0, $new_x-1, $new_y-1, $color);

$source = $img1;
$largeur_source = imagesx($source);
$hauteur_source = imagesy($source);
//-------------------------------------------------------------

//*********************************
$largeur_inicio = 0;
$hauteur_inicio = 0;
$largeur_source = $largeur_source - 0;
//*********************************

// On veut placer le logo en bas � droite, on calcule les coordonn�es o� on doit placer le logo sur la photo
//$destination_x = $largeur_destination - $largeur_source;
//$destination_y =  $hauteur_destination - $hauteur_source;
$destination_x = ceil($largeur_destination / 2) + 43;
$destination_y = ceil($hauteur_destination / 2) - 197;

// On met le logo (source) dans l'image de destination (la photo)
//imagecopymerge($destination, $source, $destination_x, $destination_y, 0, 0, $largeur_source, $hauteur_source, 50);
imagecopymerge($destination, $source, $destination_x, $destination_y, $largeur_inicio, $hauteur_inicio, $largeur_source, $hauteur_source, 100);
//colocandole un borde
$color = imagecolorallocate($destination, 0, 0, 0);
imagerectangle($destination, 0, 0, $largeur_destination-1, $hauteur_destination-1, $color);

//----------------------------TEXTO Nombre
$arr_cen = split("/",$nombre);
foreach($arr_cen as $key => $palabra){
    $bbox = imagettfbbox(10,0,$fuente,$palabra);
    $x = 1120-(($bbox[2]-$bbox[0])/2);
    $y = (25*$key)+230;
    imagettftext($destination,15,0,$x,$y,$color,$fuente,$palabra);
}
//----------------------------TEXTO codigo barras
imagettftext($destination,85,0,830,397,$color,$fuentecodigo,$codigo);

// On affiche l'image de destination qui a �t� fusionn�e avec le logo
imagejpeg($destination);

imagedestroy($destination);
?>