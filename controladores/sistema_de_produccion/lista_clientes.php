<?php

session_start();
include_once('../../clases/sistema_de_produccion/clientes.php');
include_once('../../clases/includes/validador.php');
require_once('../includes/seguridad.php');

define('CLAS', "../../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once(SMARTY_DIR . 'Smarty.class.php');

$clientes=new Cliente;
$funcion = $_GET['funcion'];
$url_relativa = "../../src/sistema_de_produccion/lista_clientes.php";
$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';
 $lista=  array('Afganistán','Albania','Alemania','Andorra','Angola','Anguilla','Antártida','Antigua y Barbuda','Antillas Holandesas','Arabia Saudí','Argelia','Argentina','Armenia','Aruba','Australia','Austria','Azerbaiyán','Bahamas','Bahrein','Bangladesh','Barbados','Bélgica','Belice','Benin','Bermudas','Bielorrusia','Birmania','Bolivia','Bosnia y Herzegovina','Botswana','Brasil','Brunei','Bulgaria','Burkina Faso','Burundi','Bután','Cabo Verde','Camboya','Camerún','Canadá','Chad','Chile','China','Chipre','Ciudad del Vaticano','Colombia','Comores','Congo','Corea','Corea del Norte','Costa de Marfíl','Costa Rica','Croacia','Cuba','Dinamarca','Djibouti','Dominica','Ecuador','Egipto','El Salvador','Emiratos Árabes Unidos','Eritrea','Eslovenia','España','Estados Unidos','Estonia','Etiopía','Fiji','Filipinas','Finlandia','Francia','Gabón','Gambia','Georgia','Ghana','Gibraltar','Granada','Grecia','Groenlandia','Guadalupe','Guam','Guatemala','Guayana','Guayana Francesa','Guinea','Guinea Ecuatorial','Guinea-Bissau','Haití','Honduras','Hungría','India','Indonesia','Irak','Irán','Irlanda<','Isla Bouvet','Isla de Christmas','Islandia','Islas Caimán','Islas Cook','Islas de Cocos o Keeling','Islas Faroe','Islas Heard y McDonald','Islas Malvinas','Islas Marianas del Norte','Islas Marshall','Islas menores de Estados Unidos','Islas Palau','Islas Salomón','Islas Svalbard y Jan Mayen','Islas Tokelau','Islas Turks y Caicos','Islas Vírgenes (EE.UU.)','Islas Vírgenes (Reino Unido)','Islas Wallis y Futuna','Israel','Italia','Jamaica','Japón','Jordania','Kazajistán','Kenia','Kirguizistán','Kiribati','Kuwait','Laos','Lesotho','Letonia','Líbano','Liberia','Libia','Liechtenstein','Lituania','Luxemburgo','Macedonia','Madagascar','Malasia','Malawi','Maldivas','Malí','Malta','Marruecos','Martinica','Mauricio','Mauritania','Mayotte','México','Micronesia','Moldavia','Mónaco','Mongolia','Montserrat','Mozambique','Namibia','Nauru','Nepal','Nicaragua','Níger','Nigeria','Niue','Norfolk','Noruega','Nueva Caledonia','Nueva Zelanda','Omán','Países Bajos','Panamá','Papúa Nueva Guinea','Paquistán','Paraguay','Perú','Pitcairn','Polinesia Francesa','Polonia','Portugal','Puerto Rico','Qatar','Reino Unido','República Centroafricana','República Checa','República de Sudáfrica','República Dominicana','República Eslovaca','Reunión','Ruanda','Rumania','Rusia','Sahara Occidental','Saint Kitts y Nevis','Samoa','Samoa Americana','San Marino','San Vicente y Granadinas','Santa Helena','Santa Lucía','Santo Tomé y Príncipe','Senegal','Seychelles','Sierra Leona','Singapur','Siria','Somalia','Sri Lanka','St. Pierre y Miquelon','Suazilandia','Sudán','Suecia','Suiza','Surinam','Tailandia','Taiwán','Tanzania','Tayikistán','Territorios franceses del Sur','Timor Oriental','Togo','Tonga','Trinidad y Tobago','Túnez','Turkmenistán','Turquía','Tuvalu','Ucrania','Uganda','Uruguay','Uzbekistán','Vanuatu','Venezuela','Vietnam','Yemen','Yugoslavia','Zambia','Zimbabue');
  


if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {
	//verificamos si se desea modificar la información de un usuario
    if($funcion=="modificar")
  	{
  	   $id=$_GET['elegido'];
  	   //consultamos los datos del cliente
       $informacion=$clientes->ver_datos_cliente($id);
	   $smarty->assign('id',$informacion["codigo"]);
       $smarty->assign('nombre',$informacion["nombre"]);
       $smarty->assign('pais',$informacion["pais"]);
       $smarty->assign('ciudad',$informacion["ciudad"]);
       $smarty->assign('telefono',$informacion["telefono"]);
       $smarty->assign('email',$informacion["email"]);
	   $smarty->assign('direccion',$informacion["direccion"]);
	   $smarty->assign('errores', $errores);
       $smarty->assign('titulo', 'Modificar Cliente');
	   $smarty->assign('paises', $lista);
       $smarty->display('sistema_de_produccion/clientes/modificar_clientes.html');
  	}
  	else
    {    //verificamos si la operacion a realizar es la de eliminar
	       if($funcion=="eliminar")
  		   {
  		      //recuperamos el id del cliente a eliminar
              $id=$_GET['elegido'];
              //enviamos el id a la funcion de eliminar cliente
              $resultado=$clientes->eliminar_cliente($id);
              //verificamos si la operación se realizo con éxito
              if($resultado)
    		  {
    		    $consulta= $clientes->consulta_lista_clientes();
				
			      $smarty->assign('clientes',$consulta);
				   $smarty->display('sistema_de_produccion/clientes/lista_clientes.html');
     		  }
            }

            else
			{
			   //verificamos si se desea modificar los datos de un cliente
	       		if($funcion=="modificar_datos")
  		   		{

                    $validar = new Validador();
  					if ($validar->validarTodo($_POST['nombre'], 1, 100))
    						$error['err_nombre'] = "Ingresa el nombre del cliente";
  					if ($validar->validarTodo($_POST['pais'], 1, 100))
    						$error['err_pais'] = "Ingresa el pais del cliente";
  					if ($validar->validarTodo($_POST['ciudad'], 1, 100))
    						$error['err_ciudad'] = "Ingresa la ciudad del cliente";
					if ($validar->validarTodo($_POST['direccion'], 1, 20))
    						$error['err_direccion'] = "Ingresa la dirección del cliente";		
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

				    if (isset($error))
					{
					     $informacion["codigo"] = $_POST['elegido'];
						 $informacion["nombre"]=$_POST['nombre'];
					     $informacion["pais"]=$_POST['pais'];
						 $informacion["ciudad"]=$_POST['ciudad'];
		 				 $informacion["telefono"]=$_POST['telefono'];						 
		 				 $informacion["email"]=$_POST['email'];
 		 				 $informacion["direccion"]=$_POST['direccion'];	
						 $smarty->assign('id',$informacion["codigo"]);
      					 $smarty->assign('nombre',$informacion["nombre"]);
					     $smarty->assign('pais',$informacion["pais"]);
					     $smarty->assign('ciudad',$informacion["ciudad"]);
				         $smarty->assign('telefono',$informacion["telefono"]);
				         $smarty->assign('email',$informacion["email"]);
					     $smarty->assign('direccion',$informacion["direccion"]);
					     $smarty->assign('errores', $errores);
				         $smarty->assign('titulo', 'Modificar Cliente');
						 $smarty->assign('paises', $lista);
				         $smarty->display('sistema_de_produccion/clientes/modificar_clientes.html'); 				
						 
                    }
     				else
  	               {

				   //obtenemos toda la información del formulario de modificación
                   $id = $_POST['elegido'];
  		   		   $nombre = $_POST['nombre'];
     			   $pais = $_POST['pais'];
     			   $ciudad = $_POST['ciudad'];
				   $telefono = $_POST['telefono'];
     			   $email = $_POST['email'];
				   $direccion = $_POST['direccion'];
     			   //enviamos la información a la función de modificar cliente
				   $resultado=$clientes->modificar_cliente($id,$nombre,$pais,$ciudad,$direccion,$telefono,$email);

     			   //obtenemos la lista de clientes y la enviamos a la plantilla
				    $consulta= $clientes->consulta_lista_clientes();
				    $smarty->assign('clientes',$consulta);
					$smarty->display('sistema_de_produccion/clientes/lista_clientes.html');
	  		      }
	  		    }
		  		else
		  		{
				   if($funcion=="buscar")
					{
						$cadena=$_GET['parametro'];
						$opcion=$_GET['opcion'];
						$consulta= $clientes->consultar_busqueda($cadena,$opcion);
						$smarty->assign('clientes',$consulta);
						$smarty->display('sistema_de_produccion/clientes/lista_clientes.html');
					}
					else
					{
						$consulta= $clientes->consulta_lista_clientes();
						$smarty->assign('clientes',$consulta);
						$smarty->display('sistema_de_produccion/clientes/lista_clientes.html');
				
					}
				
				}
			}
	}
	
}
?>

