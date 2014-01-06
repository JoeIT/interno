<?php
require_once('../../scripts/loader.php');
include_once('../../clases/usuarios.php');
require_once('../includes/seguridad.php');

$usuarios=new Usuario;

if (!empty($_POST))
{
  $errors = array();
  if (!isset($_POST['login']) || empty($_POST['login']))
    $errors[] = 'Por favor, ingresa tu login';
  if (!isset($_POST['hash']) || empty($_POST['hash']))
    $errors[] = 'Por favor, ingresa tu password';
  

  if (!empty($errors))
  {
    echo 'false';
    foreach ($errors as $error)
	{
      echo ';' . $error;
    }
    exit;
  }
     //recuperamos todos los datos registrados en el formulario
     $nombre = $_POST['nombre'];
     $pais = $_POST['pais'];
     $ciudad = $_POST['ciudad'];
     $telefono = $_POST['telefono'];
     $email = $_POST['email'];
     //enviamos la informacion a la función de adicionar cliente
     $resultado=$clientes->adicionar_cliente($nombre,$pais,$ciudad,$telefono,$email);
     if($resultado)
  	 {
  	 	echo 'true;El registro se realizo correctamente';
		$consulta= $clientes->consulta_lista_clientes();
        $t->clear_all_assign();
		//$t->assign('messages', array('La informacion de ingreso correctamente'));
	    $t->assign('messages_warning', false);

		/*$t->assign('titulo', 'Lista de clientes');
		$t->assign('clientes',$consulta);
		$t->assign('page_template','lista');
		$t->display('../../../vistas/templates/clientes/page.tpl');*/

        exit;
     }
  	 else
  	 {	echo 'false;No se pudo realizar el registro';
  	 	exit;
	 }
}
$t->assign('title', 'Formulario de registro');
$t->assign('messages', array('Por favor, llena el formulario de nuevo cliente'));
$t->assign('messages_warning', true);
$t->assign('page_template', 'registrar');
$t->display('../../../vistas/templates/clientes/page.tpl');
?>