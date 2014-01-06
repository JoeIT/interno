<?php

session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../../clases/sistema_de_produccion/recepcion_calidad.php');
include_once('../../clases/sistema_de_produccion/calidad_control.php');
include_once('../../clases/sistema_de_produccion/rechazos.php');
include_once('../../clases/includes/validador.php');
include_once('../../clases/sistema_de_produccion/asignacion.php');
require('../../controladores/includes/fecha.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';

$recepcion=new Recepcion_Calidad;
$rechazo =new Rechazo;
$calidad =new Calidad_control;
$validar = new Validador();
$asignacion = new Asignacion;


if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {


	$funcion = $_GET['funcion'];
	// $lista = $rechazo->busqueda_personal_limpieza($nombre,5);
	//$lista1 = $rechazo->busqueda_personal($nombre,5,'desbaste'); 
	if(isset($_GET["busqueda_ajax"]))
	 { 
		  echo "<ul>";
		  $nombre= trim(utf8_decode($_POST['nombre']));
		  $tipo_busqueda=$_GET['busqueda_ajax'];
		  //echo "<li>".$tipo_busqueda." </li>";
		//  echo "<li>".$_GET['num_asignacion']." </li>";
		  if($tipo_busqueda=="maquinistas_total")
			  $lista = $asignacion->busqueda_personal($nombre,3); 
		  else
		  {
				if($tipo_busqueda=="disenio")
				{ 
						 $lista1 = $asignacion->busqueda_personal($nombre,1); 
						 $lista2 = $asignacion->busqueda_personal($nombre,2);
						 $lista3 = $asignacion->busqueda_personal($nombre,8);
						 $lista = array_merge((array)$lista1,(array) $lista2,(array)$lista3);
				} 
				else
				{
					if($tipo_busqueda=="limpieza")
					  $lista = $rechazo->busqueda_personal_limpieza($nombre,$_GET['num_asignacion']); 
					else
					{
					   if($tipo_busqueda=="corte")
					  {	  $lista1 = $rechazo->busqueda_personal($nombre,$_GET['num_asignacion'],'corte'); 
						  $lista2 = $rechazo->busqueda_personal($nombre,$_GET['num_asignacion'],'desbaste');
						  $lista3 = $rechazo->busqueda_personal($nombre,$_GET['num_asignacion'],'dividido');
						  $lista4 = $rechazo->busqueda_personal($nombre,$_GET['num_asignacion'],'planchado');
						  $lista5 = $rechazo->busqueda_personal($nombre,$_GET['num_asignacion'],'sellado');
						$lista = array_merge((array)$lista1,(array) $lista2,(array)$lista3,(array)$lista4,(array)$lista5);
					}
					   else
					   {
						  if($tipo_busqueda=="maquinista")
							  $lista = $rechazo->busqueda_personal_maquinista($nombre,$_GET['num_asignacion']); 
					   
					   }
					}
				}
		  }
		  if(count($lista) == 0){
				echo "<li>No hay resultados</li>";
			} else {
			
				
				for($contador = 0; $contador < count($lista); $contador ++){
					$detalles = $lista[$contador]["personal_id"]."-".$lista[$contador]["clase"];
					echo '<li id="'.$detalles.'">&nbsp;'.$lista[$contador]["clase"].' - '.$lista[$contador]["completo"].'</li>';
				}
			}
			echo "</ul>";  	 
			 
	 }
	
	else
	{
	
		if($funcion=="buscar")
		{
				$num_asignacion = trim($_GET['num_asignacion']);
				//validar numero de asignacion
				if ($validar->validarTodo($num_asignacion, 1, 100)){
					$error['num_asignacion'] = "Ingresar n&uacute;mero de asignaci&oacute;n";
				} else {
					//validamos que sea solo numero
					if (!ereg("^[0-9]{1,}$", $num_asignacion)){
						$error['num_asignacion'] = "Ingresar solo n&uacute;meros";
					}
				}
				//si existe errores
				if (isset($error)){
					$smarty->assign('errores', $error);
					$smarty->display('sistema_de_produccion/control_calidad/formulario_actualizacion_rechazos.html');
				} else {
					//si no existe errores
					//efectuamos la busqueda
					$resultado_asignacion = $recepcion->buscar_asignacion($num_asignacion);
					$resumen_rechazos = $rechazo->resumen_rechazo_actualizar($num_asignacion);
					$total_rechazado = $rechazo->total_rechazado($num_asignacion);
					
					//verificar si devolvio algun resultado
					if ($resultado_asignacion != null)
					{
						if($resultado_asignacion['usuario_cerrado']!=0)
							$usuario_cerrado= $calidad->nombre_usuario($resultado_asignacion['usuario_cerrado']);
						$fecha_cerrado=split(" ", $resultado_asignacion['fecha_cerrado']);
						$smarty->assign('resultado_asignacion', $resultado_asignacion);
						$smarty->assign('resumen_rechazos', $resumen_rechazos);
						$smarty->assign('usuario_cerrado', $usuario_cerrado);
						$smarty->assign('fecha_cerrado', $fecha_cerrado[0]);
						$smarty->assign('hora_cerrado', $fecha_cerrado[1]);
						$smarty->assign('total_rechazado', $total_rechazado);
						$smarty->assign('num_asignacion', $num_asignacion);
						$smarty->display('sistema_de_produccion/control_calidad/formulario_actualizacion_rechazos.html');
					 
					}
					else
					{
					  $smarty->assign('mensaje', "No se encontr&oacute; la asignaci&oacute;n");
					  $smarty->display('sistema_de_produccion/control_calidad/formulario_actualizacion_rechazos.html');
					}
				}
		}
		else
		{
			if($funcion=="detalle_seleccionado")
			{
				 $rechazo_id= $_GET['rechazo_id'];
				 $num_asignacion=$_GET['num_asignacion'];
				 $resumen_rechazo = $rechazo->resumen_rechazo_actualizar_id($rechazo_id);
				 $categoria_fallos = $rechazo->obtener_categoria_fallos();
				 $resultado_asignacion = $recepcion->buscar_asignacion($num_asignacion);
				 $smarty->assign('rechazo_id',$rechazo_id);
				 $smarty->assign('resultado_asignacion', $resultado_asignacion);
				 $smarty->assign('num_asignacion',$num_asignacion);
				 $smarty->assign('resumen_rechazo',$resumen_rechazo);
				 $smarty->assign('categoria_fallos',$categoria_fallos);
				 $smarty->display('sistema_de_produccion/control_calidad/formulario_actualizacion_rechazos_detalle.html');
				 
				 
			}
			else
			{ 
				if($funcion=="registrar_actualizacion")
				{
						$cantidad = trim($_GET['cantidad']);
						$categoria_id = trim($_GET['categorias']);
						$fallos = trim($_GET['fallos']);
						$num_asignacion = trim($_GET['num_asignacion']);
						$rechazo_id = trim($_GET['rechazo_id']);
						//validar numero de asignacion
						if ($validar->validarTodo($cantidad, 1, 100)){
							$error['cantidad'] = "Ingresar una cantidad";
						} else {
							//validamos que sea solo numero
								if (!ereg("^[0-9]{1,}$", $cantidad)){
							$error['cantidad'] = "Ingresar solo n&uacute;meros";
							}
						}
						 if ($validar->validarTodo($_GET['descripcion_fallo'], 1, 100)) {
						   $error['descripcion_fallo'] = "Debe ingresar una descripción";}
						if($fallos=="Maquinista")
						{
							if ($validar->validarTodo($_GET['responsable_maquinista'], 1, 100)) {
							   $error['responsable_fallo'] = "Debe indicar al responsable del fallo";}
							 else
							 {
							   $responsable=$_GET['responsable_maquinista'];
							   $responsable=split('-',$responsable);
							   $responsable_id=$rechazo->verificar_maquinista_valido(trim($responsable[0]),$num_asignacion);
							   //echo $responsable_id;
							   if($responsable_id==-1)
									  $error['responsable_fallo'] = "Responsable no válido";
							 }
						}
						else
						{
						   if($fallos=="Limpieza")
							{
								if ($validar->validarTodo($_GET['responsable_limpieza'], 1, 100)) {
								   $error['responsable_fallo'] = "Debe indicar al responsable del fallo";}
								else
								{
								   $responsable=$_GET['responsable_limpieza'];
								   $responsable=split('-',$responsable);
								   $responsable_id=$rechazo->verificar_limpiador_valido(trim($responsable[0]),$num_asignacion);
								  // echo $responsable_id;
								   if($responsable_id==-1)
									  $error['responsable_fallo'] = "Responsable no válido";
								 }
							}
							else
							{   
									if($fallos=="Corte")
									{
										if ($validar->validarTodo($_GET['responsable_corte'], 1, 100)) {
										   $error['responsable_fallo'] = "Debe indicar al responsable del fallo";}
										else
										{
											 $responsable=$_GET['responsable_corte'];
											 $responsable=split('-',$responsable);
										   $responsable_id=$rechazo->verificar_empleado_valido(trim($responsable[0]));
										  // echo $responsable_id;
											 if($responsable_id==-1)
												  $error['responsable_fallo'] = "Responsable no válido";
										 
										
										}
									}
									else
									{
										 if ($validar->validarTodo($_GET['responsable_diseño'], 1, 100)) {
										   $error['responsable_fallo'] = "Debe indicar al responsable del fallo";}   
										 else
										 {
											 $responsable=$_GET['responsable_diseño'];
											 $responsable=split('-',$responsable);
											 $responsable_id=$rechazo->verificar_empleado_valido(trim($responsable[0]));
										  // echo $responsable_id;
											 if($responsable_id==-1)
												  $error['responsable_fallo'] = "Responsable no válido";
										 
										 
										 }
									}
							
							
							}
							
						}  
						if ($validar->validarTodo($_GET['responsable_arreglo'], 1, 100)) 
						{
						   $error['responsable_arreglo'] = "Debe indicar al responsable del arreglo";
						}
						else
						{
							 $responsable=$_GET['responsable_arreglo'];
							 $responsable=split('-',$responsable);
							 $responsable_arreglo=$rechazo->verificar_empleado_valido(trim($responsable[0]));
							 // echo $responsable_id;
							 if($responsable_id==-1)
									$error['responsable_arreglo'] = "Responsable de arreglo no válido";
						
						
						
						}
						   
						if (isset($error)){
							 $rechazo_id= $_GET['rechazo_id'];
							 $num_asignacion=$_GET['num_asignacion'];
							 $resumen_rechazo = $rechazo->resumen_rechazo_actualizar_id($rechazo_id);
							 $categoria_fallos = $rechazo->obtener_categoria_fallos();
							 $resultado_asignacion = $recepcion->buscar_asignacion($num_asignacion);
							 $smarty->assign('rechazo_id',$rechazo_id);
							 $smarty->assign('resultado_asignacion', $resultado_asignacion);
							 $smarty->assign('num_asignacion',$num_asignacion);
							 $smarty->assign('descripcion_fallo',trim($_GET['descripcion_fallo']));
							 $smarty->assign('resumen_rechazo',$resumen_rechazo);
							 $smarty->assign('categoria_fallos',$categoria_fallos);
							 $smarty->assign('errores', $error);
							 $smarty->display('sistema_de_produccion/control_calidad/formulario_actualizacion_rechazos_detalle.html');
						} 
						else 
						{
							$rechazo_id= $_GET['rechazo_id'];
							$num_asignacion=$_GET['num_asignacion'];
							$clasificacion_id=$_GET['categorias'];
							//echo "clasificacion:".$clasificacion_id;
						  //echo "se debe registrar";
							$resultado_actualizacion = $rechazo->actualizacion_informacion_rechazos($rechazo_id,$responsable_id,$cantidad,$fallos,$clasificacion_id,$_GET['descripcion_fallo']);
							$resultado_actualizacion = $rechazo->actualizacion_informacion_arreglos($rechazo_id,$responsable_arreglo,$cantidad);
							$resultado_asignacion = $recepcion->buscar_asignacion($num_asignacion);
							$resumen_rechazos = $rechazo->resumen_rechazo_actualizar($num_asignacion);
							$total_rechazado = $rechazo->total_rechazado($num_asignacion);
							$smarty->assign('resultado_asignacion', $resultado_asignacion);
							$smarty->assign('resumen_rechazos', $resumen_rechazos);
							$smarty->assign('total_rechazado', $total_rechazado);
							$smarty->display('sistema_de_produccion/control_calidad/formulario_actualizacion_rechazos.html');
					 }
				}
				else
				{$smarty->display('sistema_de_produccion/control_calidad/formulario_actualizacion_rechazos.html');}
			}
		
		}	
	}
	
}
?>