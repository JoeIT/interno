<?php
session_start();

include_once('../clases/logeos.php');
include_once('../clases/avisos.php');

define('CLAS', "../clases/");
define('SMARTY_DIR', "../smarty/");
require_once(SMARTY_DIR . 'Smarty.class.php');

$smarty = new Smarty();
$smarty->template_dir = '../templates/';
$smarty->compile_dir = '../templates_c';


$logeo=new Logeo;
$aviso=new Aviso;
	

	
if(isset($_SESSION['logeo'])){
	$smarty->display('contenedor.html');
} else {
	
	$login = $_POST['login'];
	$pass = $_POST['hash'];
	if (!empty($_POST) and $login!= null and $pass!=null){
		$login = $_POST['login'];
		$pass = $_POST['hash'];
		
		$resultado=$logeo->comprobar($login, $pass);
		
		if($resultado==1){
			$id=$_SESSION['usuario_id'];
		
            $consulta=$aviso->obtener_lista_avisos($id);
					
	        $_SESSION['avisos'] = $consulta;
			
			$smarty->display('contenedor.html');
			
		} else {

			$error="Datos incorrectos, intente de nuevo";
			$smarty->assign('errorlogeo', $error);
			$smarty->display('index.html');
		}
	} else {
		
		$error="Introduzca su ID y contraseña";
		$smarty->assign('errorlogeo', $error);
		//$smarty->display('index.html');
		//para que todo el frame se recargue con el formulario del logeo
		?>
			<script language="javascript">
				window.parent.location = 'index.php';
			</script>
		<?php
	}
}
?>