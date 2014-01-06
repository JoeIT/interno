<?php
session_start();
define('CLAS', "../../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once('../includes/seguridad.php');
require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../../clases/sistema_de_produccion/clientes.php');
include_once('../../clases/includes/validador.php');
$clientes=new Cliente;
$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';
  $lista=  array('Afganistán','Albania','Alemania','Andorra','Angola','Anguilla','Antártida','Antigua y Barbuda','Antillas Holandesas','Arabia Saudí','Argelia','Argentina','Armenia','Aruba','Australia','Austria','Azerbaiyán','Bahamas','Bahrein','Bangladesh','Barbados','Bélgica','Belice','Benin','Bermudas','Bielorrusia','Birmania','Bolivia','Bosnia y Herzegovina','Botswana','Brasil','Brunei','Bulgaria','Burkina Faso','Burundi','Bután','Cabo Verde','Camboya','Camerún','Canadá','Chad','Chile','China','Chipre','Ciudad del Vaticano','Colombia','Comores','Congo','Corea','Corea del Norte','Costa de Marfíl','Costa Rica','Croacia','Cuba','Dinamarca','Djibouti','Dominica','Ecuador','Egipto','El Salvador','Emiratos Árabes Unidos','Eritrea','Eslovenia','España','Estados Unidos','Estonia','Etiopía','Fiji','Filipinas','Finlandia','Francia','Gabón','Gambia','Georgia','Ghana','Gibraltar','Granada','Grecia','Groenlandia','Guadalupe','Guam','Guatemala','Guayana','Guayana Francesa','Guinea','Guinea Ecuatorial','Guinea-Bissau','Haití','Honduras','Hungría','India','Indonesia','Irak','Irán','Irlanda<','Isla Bouvet','Isla de Christmas','Islandia','Islas Caimán','Islas Cook','Islas de Cocos o Keeling','Islas Faroe','Islas Heard y McDonald','Islas Malvinas','Islas Marianas del Norte','Islas Marshall','Islas menores de Estados Unidos','Islas Palau','Islas Salomón','Islas Svalbard y Jan Mayen','Islas Tokelau','Islas Turks y Caicos','Islas Vírgenes (EE.UU.)','Islas Vírgenes (Reino Unido)','Islas Wallis y Futuna','Israel','Italia','Jamaica','Japón','Jordania','Kazajistán','Kenia','Kirguizistán','Kiribati','Kuwait','Laos','Lesotho','Letonia','Líbano','Liberia','Libia','Liechtenstein','Lituania','Luxemburgo','Macedonia','Madagascar','Malasia','Malawi','Maldivas','Malí','Malta','Marruecos','Martinica','Mauricio','Mauritania','Mayotte','México','Micronesia','Moldavia','Mónaco','Mongolia','Montserrat','Mozambique','Namibia','Nauru','Nepal','Nicaragua','Níger','Nigeria','Niue','Norfolk','Noruega','Nueva Caledonia','Nueva Zelanda','Omán','Países Bajos','Panamá','Papúa Nueva Guinea','Paquistán','Paraguay','Perú','Pitcairn','Polinesia Francesa','Polonia','Portugal','Puerto Rico','Qatar','Reino Unido','República Centroafricana','República Checa','República de Sudáfrica','República Dominicana','República Eslovaca','Reunión','Ruanda','Rumania','Rusia','Sahara Occidental','Saint Kitts y Nevis','Samoa','Samoa Americana','San Marino','San Vicente y Granadinas','Santa Helena','Santa Lucía','Santo Tomé y Príncipe','Senegal','Seychelles','Sierra Leona','Singapur','Siria','Somalia','Sri Lanka','St. Pierre y Miquelon','Suazilandia','Sudán','Suecia','Suiza','Surinam','Tailandia','Taiwán','Tanzania','Tayikistán','Territorios franceses del Sur','Timor Oriental','Togo','Tonga','Trinidad y Tobago','Túnez','Turkmenistán','Turquía','Tuvalu','Ucrania','Uganda','Uruguay','Uzbekistán','Vanuatu','Venezuela','Vietnam','Yemen','Yugoslavia','Zambia','Zimbabue');



if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {
	if($_POST['funcion']== "validar")
	{    $error="";
		 $validar = new Validador();
		if ($validar->validarTodo($_POST['nombre'], 1, 100))
			$error['err_nombre'] = "Ingresa el nombre del cliente";
		 if ($validar->validarTodo($_POST['pais'], 1, 100))
			$error['err_pais'] = "Ingresa el pais del cliente";
		/* if ($validar->validarTodo($_POST['direccion'], 1, 100))
			$error['err_direccion'] = "Ingresa la direccion del cliente";
		 if ($validar->validarTodo($_POST['ciudad'], 1, 100))
			$error['err_ciudad'] = "Ingresa la ciudad del cliente";
		 if ($validar->validarTodo($_POST['telefono'], 1, 20))
			$error['err_telefono'] = "Ingresa el telefono del cliente";
		 else
		 {
			   if($validar->validarTelefono($_POST['telefono'],1,20))
				  $error['err_telefono'] = "Telefono no valido, vuelva a ingresarlo";
		 }
		if ($validar->validarTodo($_POST['email'],1,100))
			$error['err_email'] = "Ingresa el correo electronico del cliente";
		else
		{
			if($validar->validarEmail($_POST['email']))
			 $error['err_email'] = "Email no valido, vuelva a ingresarlo";
		}
	
	*/
		if($error!="")
		{
			   $nombre = $_POST['nombre'];
			   $pais = $_POST['pais'];
			   $ciudad = $_POST['ciudad'];
			   $telefono = $_POST['telefono'];
			   $email = $_POST['email'];
			   $direccion= $_POST['direccion'];
			 $smarty->assign('nombre',$nombre);
			 $smarty->assign('pais',$pais);
			 $smarty->assign('paises',$lista);
			 $smarty->assign('ciudad',$ciudad);
			 $smarty->assign('telefono',$telefono);
			 $smarty->assign('email',$email);
			 $smarty->assign('direccion',$direccion);
			 $smarty->assign('errores',$error);
		  if ($_POST['popup'] == "true") 
			   $smarty->assign('menu',"false");
				
		 $smarty->display('sistema_de_produccion/clientes/registrar_clientes.html');
		}
		else
		{
		   $nombre = $_POST['nombre'];
		   $pais = $_POST['pais'];
		   $ciudad = $_POST['ciudad'];
		   $telefono = $_POST['telefono'];
		   $email = $_POST['email'];
		   $direccion= $_POST['direccion'];
		   //enviamos la informacion a la función de adicionar cliente
		   $resultado=$clientes->adicionar_cliente($nombre,$pais,$ciudad,$direccion,$telefono,$email);
		   if($resultado)
		   {
			 $error['err_confirm'] = "El registro se realizo correctamente";
			 //$_SESSION['error']= $error;
			 
			 if ($_POST['popup'] == "true")  
			 { 
			   $smarty->assign('menu',"false");
				
			 }
			 $smarty->assign('errores',$error);
			 $smarty->assign('paises',$lista);
			 $smarty->assign('pais',"Estados Unidos");
			 $smarty->display('sistema_de_produccion/clientes/registrar_clientes.html');
		   }
	
		 }
	}
	else
	{ 
	   
		if($_GET['popup']=="true")
		   $smarty->assign('menu',"false");
		else
			  $smarty->assign('menu',"true");
		$smarty->assign('pais',"Estados Unidos");
		$smarty->assign('paises',$lista);
		$smarty->display('sistema_de_produccion/clientes/registrar_clientes.html');
	}

}
?>