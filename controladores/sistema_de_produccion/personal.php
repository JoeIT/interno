<?php
session_start();
define('CLAS', "../../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once(SMARTY_DIR . 'Smarty.class.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';

require_once('../../clases/sistema_de_produccion/personal.php');
include_once('../../clases/validador.php');
require_once('../includes/seguridad.php');
require("../includes/fecha.php");
include("excelwriter.inc.php");

$personal = new Personal;
$validar = new Validador;

//Variables para cabecera
$smarty->assign('fecha', $fecha);
$smarty->assign('nombres', $_SESSION["nombres"]);
$smarty->assign('apellidos', $_SESSION["apellidos"]);
$smarty->assign('usuario_id', $_SESSION["usuario_id"]);
//
				
if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {

	$fecha_hoy = date("Y-m-d");
	$el_momento = date("Y-m-d H:i:s");
	$puestos = $personal->listar_puestos();
				
	$_SESSION['fecha_hoy'] = $fecha_hoy;
	$_SESSION['el_momento'] = $el_momento;
	$_SESSION['puestos'] = $puestos;
	
	$smarty->assign('fecha_hoy', $fecha_hoy);
	$smarty->assign('el_momento', $el_momento);
	$smarty->assign('puestos', $puestos);
	
	switch ($_GET['opcion']){
		case 1 :{
			//Ver el formulario de registro
			$smarty->display('sistema_de_produccion/personal/busqueda.html');
			//fin de ver el formulario de registro
			break;
		}
		case 2 :{
			//Modificar puestos de trabajo
			$smarty->display('sistema_de_produccion/personal/busqueda_puestos.html');
			$smarty->display('contenido/includes/pie.html');			
			break;
		}
		case 3 :{
			//Buscar por nombre
			$smarty->display('sistema_de_produccion/personal/busqueda_nombre.html');
			$smarty->display('contenido/includes/pie.html');
			break;
		}
		case 4 :{
			//Directo para modificar las opciones
			$ci = $_GET['pid'];
			$datos_personal = $personal->sacar_datos_personal_id($ci);
			$identificador = $personal->encontrar_existe_personal_id($ci);
			$puesto = $personal->sacar_puesto_trabajo_personal($identificador['personal_id']);
			$smarty->assign('nombresp', $datos_personal['nombresp']);
			$smarty->assign('apellidosp', $datos_personal['apellidosp']);
			$smarty->assign('ci', $datos_personal['ci']);
			$smarty->assign('ciexpirar', $datos_personal['ciexpirar']);
			$smarty->assign('fecnac', $datos_personal['fecnac']);
			$smarty->assign('tiposangre', $datos_personal['tiposangre']);
			$smarty->assign('fecinicio', $datos_personal['fecinicio']);
			$smarty->assign('fecfin', $datos_personal['fecfin']);
			$smarty->assign('auto_permitido', $datos_personal['auto_permitido']);
			$smarty->assign('fexpiracionbrevet', $datos_personal['fexpiracionbrevet']);	
			$smarty->assign('puesto', $puesto);
			$smarty->assign('telefono', $datos_personal['telefono']);
			$smarty->assign('habilitado', $datos_personal['habilitado']);
			$smarty->assign('legalizado', $datos_personal['legalizado']);
			$smarty->assign('observaciones', $datos_personal['observaciones']);
			$smarty->assign('nombre_fotografia', $datos_personal['fotografia']);
			$smarty->display('sistema_de_produccion/personal/busqueda.html');
			$smarty->display('sistema_de_produccion/personal/formulario.html');
			$smarty->display('contenido/includes/pie.html');
			break;
		}
		case 'obs' :{
			//Buscar por nombre
			$fecha_inicio = date("Y-m-d", mktime(0, 0, 0, date("m")-1 , date("d")-date("d")+1, date("Y"))); 
			$fecha_fin = date("Y-m-d", mktime(0, 0, 0, date("m") , date("d")-date("d"), date("Y"))); 
			$fecha_obs = date("Y-m-d");
			$fecha_fin_obs = date("Y-m-d");
			
			$fecha_ing_obs = date("Y-m-d");
//
			if ($_POST['generar']){
				$fecha_inicio = trim($_POST["fecha_inicio"]);
				$fecha_fin = trim($_POST["fecha_fin"]);
				$puesto_trabajo = $_POST["puesto"];
				if ($puesto_trabajo == "todos")
					$nombre_puesto_trabajo = "Todos";
				else
					$nombre_puesto_trabajo = $_POST["nombre".$puesto_trabajo];
				
				//validacion
				if ($fecha_fin < $fecha_inicio){
					$error['fecha'] = "La fecha de finalizaci&oacute;n debe ser mayor";
				}
				//fin validacion
				
				if (isset($error)){
					//reenviar los errores
					$smarty->assign('error', $error);
				} else {
					//echo "<br>mostrar reportes de entrada a la porteria";
					$reporte_asistencia = $personal->reporte_asistencia($fecha_inicio, $fecha_fin, $puesto_trabajo);
					if ($reporte_asistencia != null){				
						$nombre_archivo = 'reporte_'.$nombre_puesto_trabajo.'_'.$fecha_inicio.'_'.$fecha_fin;
						$personal->reporte_asistencia_excel($nombre_archivo, $reporte_asistencia);
						$smarty->assign('archivo', $nombre_archivo);
						$smarty->assign('reporte_asistencia', $reporte_asistencia);
					} else {
						$smarty->assign('mensaje_reporte', 'No se tienen resultados');
					}
					
				}// if (isset($error))
			}//if ($_POST['generar'])
//			

			if ($_POST['generar_por_nombre']){
				$fecha_inicio = trim($_POST["fecha_inicio"]);
				$fecha_fin = trim($_POST["fecha_fin"]);
				$nombre_asis = trim($_POST["nombre_asis"]);
				
				$smarty->assign('nombre_asis', $nombre_asis);
				
				if ($fecha_fin < $fecha_inicio){
					$error['fecha'] = "La fecha de finalizaci&oacute;n debe ser mayor";
				}
				
				if ($nombre_asis == "")
					$error['nombre_asis'] = "Ingrese el nombre de una persona";
				else {
					$personal_id = $personal->verificar_nombre($nombre_asis);
					if ($personal_id == 0)
						$error['nombre_asis'] = "Ingrese nombre valido";
				}
				if (isset($error)){
					$smarty->assign('error', $error);
				} else {
					//echo "reporte";
					$reporte_asistencia = $personal->reporte_asistencia_nombres($fecha_inicio, $fecha_fin, $personal_id);
					if ($reporte_asistencia != null){				
						$nombre_archivo = 'reporte_'.$nombre_asis.'_'.$fecha_inicio.'_'.$fecha_fin;
						$personal->reporte_asistencia_excel($nombre_archivo, $reporte_asistencia);
						$smarty->assign('archivo', $nombre_archivo);
						$smarty->assign('reporte_asistencia', $reporte_asistencia);
					} else {
						$smarty->assign('mensaje_reporte', 'No se tienen resultados');
					}
				}
			}


			if ($_POST['mostrar_observaciones']){
				$fecha_obs = trim($_POST["fecha_obs"]);
				$fecha_fin_obs = trim($_POST["fecha_fin_obs"]);
				$nombre = trim($_POST["nombre"]);
				
				if ($fecha_fin_obs < $fecha_obs){
					$error['fecha_obs'] = "La fecha de finalizaci&oacute;n debe ser mayor";
				}
				
				if ($nombre == "")
					$error['nombre'] = "Ingrese el nombre de una persona";
				else {
					$personal_id = $personal->verificar_nombre($nombre);
					if ($personal_id == 0)
						$error['nombre'] = "Ingrese nombre valido";
				}
				if (isset($error)){
					$smarty->assign('error', $error);
				} else {
					$registros_asistencia = $personal->registros_asistencia($personal_id, $fecha_obs, $fecha_fin_obs);
					if ($registros_asistencia != null) {
						$smarty->assign('registros_asistencia', $registros_asistencia);
					} else {
						$smarty->assign('mensaje_asistencia', 'No se tienen resultados');
					}
				}
			}
			
			if ($_POST['ingresar_observaciones']){
				$nombre = trim($_POST["nombre"]);
				if ($nombre == "")
					$error['nombre'] = "Ingrese el nombre de una persona";
				else {
					$personal_id = $personal->verificar_nombre($nombre);
					if ($personal_id == 0)
						$error['nombre'] = "Ingrese nombre valido";
				}
				if (isset($error)){
					$smarty->assign('error', $error);
				} else {
					$smarty->assign('ingresar_observaciones', 'yes');
					$smarty->assign('personal_id', $personal_id);
				}
			}

			if ($_POST['modificar']){
				//
				$fecha_obs = $_POST["fecha_obs"];
				$fecha_fin_obs = $_POST["fecha_fin_obs"];
				$nombre = $_POST["nombre"];
				//
				$codinout = trim($_POST["codinout"]);
				$observaciones = trim($_POST["observaciones"]);
				$personal->modificar_observaciones($codinout, $observaciones);
			}

			if ($_POST['ingresar_registro']){
				$personal_id = trim($_POST["personal_id"]);
				$fecha_ing_obs = trim($_POST["fecha_ing_obs"]);
				$hora_ing_obs = trim($_POST["hora_ing_obs"]);
				$hora_sal_obs = trim($_POST["hora_sal_obs"]);
				$observaciones = trim($_POST["observaciones"]);
								
				if (isset($error)){
					$smarty->assign('error', $error);
				} else {
					//aqui la funcion para ingresar una observacion
					$personal->ingresar_observacion($personal_id, $fecha_ing_obs, $hora_ing_obs, $hora_sal_obs, $observaciones);
				}
			}
			
			$smarty->assign('fecha_inicio', $fecha_inicio);
			$smarty->assign('fecha_fin', $fecha_fin);
			$smarty->assign('fecha_obs', $fecha_obs);
			$smarty->assign('fecha_fin_obs', $fecha_fin_obs);
			$smarty->assign('fecha_ing_obs', $fecha_ing_obs);
			$smarty->assign('nombre', $nombre);
			$smarty->display('sistema_de_produccion/personal/porteria.html');
			break;
		}
		case 'busqueda_ajax' : {
			//recuperando las variables
			$nombre =  utf8_decode(trim($_POST["nombre"]));
			$puesto = trim($_GET["puesto"]);
			
			echo "<ul>";
			$lista = $personal->busqueda_personal($nombre);
		
			if(count($lista) == 0){
				echo "<li>No hay resultados</li>";
			} else {
				for($contador = 0; $contador < count($lista); $contador ++){
					$detalles = $lista[$contador]["personal_id"]."-".$lista[$contador]["clase"];
					echo '<li id="'.$detalles.'">&nbsp;'.$lista[$contador]["clase"].' - '.$lista[$contador]["completo"].'</li>';
				}
			}
			echo "</ul>";
			break;
		}
	}


	if (!empty($_POST)){
		//Buscar a una persona
		if ($_POST['buscar']){
			//buscar informacion
			if ($validar->validarTodo($_POST['ci'], 1, 100)){
				$error['err_ci_bus'] = "Ingresar CI";
			} else {
				$ci = (int)(trim( $_POST['ci']));
				//if ($validar->validarNumeros($ci, 1, 8)){
				if (!ereg("^[0-9]{5,}$", $ci)){
					$error['err_ci_bus'] = "Ingresar n&uacute;meros, mayor a 5 cifras";
				}
			}

			//Verificamos si existe error
			if (isset($error)){
				//reenviar los errores
				$smarty->assign('ci', $_POST['ci']);
				$smarty->assign('errores', $error);
				$smarty->display('sistema_de_produccion/personal/busqueda.html');
			} else {
				//realizamos la busqueda
				$identificador = $personal->encontrar_existe_personal($ci);
				if ($identificador == null)
				{
					$smarty->assign('mensaje', "No existe ci, puede ingresar datos");
					//si no existe borramos todas las variables de sesion
					$smarty->assign('ci', $_POST['ci']);
					//fin de borrar variables de sesion	
					$smarty->display('sistema_de_produccion/personal/busqueda.html');
					$smarty->display('sistema_de_produccion/personal/formulario.html');
					$smarty->display('contenido/includes/pie.html');
				} 
				else 
				{
					$pid=$identificador['personal_id'];
					$datos_personal = $personal->sacar_datos_personal($ci,$pid);
					$puesto = $personal->sacar_puesto_trabajo_personal($identificador['personal_id']);
					$smarty->assign('nombresp', $datos_personal['nombresp']);
					$smarty->assign('apellidosp', $datos_personal['apellidosp']);
					$smarty->assign('ci', $datos_personal['ci']);
					$smarty->assign('ciexpirar', $datos_personal['ciexpirar']);
					$smarty->assign('fecnac', $datos_personal['fecnac']);
					$smarty->assign('tiposangre', $datos_personal['tiposangre']);
					$smarty->assign('fecinicio', $datos_personal['fecinicio']);
					$smarty->assign('fecfin', $datos_personal['fecfin']);
					$smarty->assign('auto_permitido', $datos_personal['auto_permitido']);
					$smarty->assign('fexpiracionbrevet', $datos_personal['fexpiracionbrevet']);	
					$smarty->assign('puesto', $puesto);
					$smarty->assign('telefono', $datos_personal['telefono']);
					$smarty->assign('habilitado', $datos_personal['habilitado']);
					$smarty->assign('legalizado', $datos_personal['legalizado']);
					$smarty->assign('observaciones', $datos_personal['observaciones']);
					$smarty->assign('nombre_fotografia', $datos_personal['fotografia']);
					$smarty->display('sistema_de_produccion/personal/busqueda.html');
					$smarty->display('sistema_de_produccion/personal/formulario.html');
					$smarty->display('contenido/includes/pie.html');
			
				}
			}
		}

		//Almacenar los datos
		if ($_POST['guardar']){
			if ($validar->validarTodo($_POST['nombresp'], 1, 100)){
				$error['err_nombresp'] = "Ingresar nombre(s)";
			}
			if ($validar->validarTodo($_POST['apellidosp'], 1, 100)){
				$error['err_apellidosp'] = "Ingresar apellido(s)";
			}
			if ($validar->validarTodo($_POST['ci'], 1, 100)){
				$error['err_ci'] = "Ingresar CI";
			}
			if ($validar->validarTodo($_POST['ciexpirar'], 1, 100)){
				$error['err_ciexpirar'] = "Ingresar fecha de expiraci&oacute;n del CI";
			} else {
				if (!$validar->validarFecha2($_POST['ciexpirar'])){
					$error['err_ciexpirar'] = "Fecha de expiraci&oacute;n no valida";
				}
			}
			if ($validar->validarTodo($_POST['fecnac'], 1, 100)){
				$error['err_fecnac'] = "Ingresar fecha de nacimiento";
			} else {
				if (!$validar->validarFecha2($_POST['fecnac'])){
					$error['err_fecnac'] = "Fecha de nacimiento no valida";
				}
			}
			/*
			if ($validar->validarTodo($_POST['tiposangre'], 1, 100)){
				$error['err_tiposangre'] = "Ingresar tipo de sangre";
			}
			*/
			if ($validar->validarTodo($_POST['fecinicio'], 1, 100)){
				$error['err_fecinicio'] = "Ingresar fecha de inicio";
			} else {
				if (!$validar->validarFecha2($_POST['fecinicio'])){
					$error['err_fecinicio'] = "Fecha de inicio no valida";
				}
			}
			/*
			if ($validar->validarTodo($_POST['fecfin'], 1, 100)){
				$error['err_fecfin'] = "Ingresar fecha de finalizaci&oacute;n";
			} else {
				if (!$validar->validarFecha2($_POST['fecfin'])){
					$error['err_fecfin'] = "Fecha de finalizaci&oacute;n no valida";
				}
			}
			*/
			
			if ($_POST['auto_permitido'] != ''){
				if ($validar->validarTodo($_POST['fexpiracionbrevet'], 1, 100)){
					$error['err_fexpiracionbrevet'] = "Ingresar fecha de expiraci&oacute;n";
				} else {
					if (!$validar->validarFecha2($_POST['fexpiracionbrevet'])){
						$error['err_fexpiracionbrevet'] = "Fecha de expiraci&oacute;n no valida";
					}
				}
			}
			
			/*
			if ($validar->validarTodo($_POST['legalizado'], 1, 100)){
				$error['err_legalizado'] = "Ingresar fecha de legalizaci&oacute;n";
			} else {
				if (!$validar->validarFecha2($_POST['legalizado'])){
					$error['err_legalizado'] = "Fecha de legalizaci&oacute;n no valida";
				}
			}
			*/
		
			//fotografia
			if($_FILES['fotografia']['name'] != ""){
				$nombre_archivo = $_FILES['fotografia']['name'];
				$tipo_archivo = $_FILES['fotografia']['type'];
				$tamano_archivo = $_FILES['fotografia']['size']; 
				if(!(strpos(strtoupper($tipo_archivo), "JPG") || strpos(strtoupper($tipo_archivo), "JPEG"))){
					$error['err_fotografia'] = "Tipo de Archivo no valido";  
					$_SESSION['err_fotografia'] = $error['err_fotografia'];
					$flag_imagen = false;
				} else {
					$flag_imagen = true;
				}
			}
			
			//Verificamos si existe error
			if (isset($error)){
				//reenviar los errores
				$smarty->assign('nombresp', $_POST['nombresp']);
				$smarty->assign('apellidosp', $_POST['apellidosp']);
				$smarty->assign('ci', $_POST['ci']);
				$smarty->assign('ciexpirar', $_POST['ciexpirar']);
				$smarty->assign('fecnac', $_POST['fecnac']);
				$smarty->assign('tiposangre', $_POST['tiposangre']);
				$smarty->assign('fecinicio', $_POST['fecinicio']);
				$smarty->assign('fecfin', $_POST['fecfin']);
				$smarty->assign('auto_permitido', $_POST['auto_permitido']);
				$smarty->assign('fexpiracionbrevet', $_POST['fexpiracionbrevet']);	
				$smarty->assign('puesto', $_POST['puesto']);
				$smarty->assign('telefono', $_POST['telefono']);
				$smarty->assign('habilitado', $_POST['habilitado']);
				$smarty->assign('legalizado', $_POST['legalizado']);
				$smarty->assign('observaciones', $_POST['observaciones']);
				$smarty->assign('nombre_fotografia', $_POST['nombre_fotografia']);
				$smarty->assign('errores', $error);
				//header("Location: ../../src/sistema_de_produccion/personal.php");
				$smarty->display('sistema_de_produccion/personal/busqueda.html');
				$smarty->display('sistema_de_produccion/personal/formulario.html');
				$smarty->display('contenido/includes/pie.html');
			} else {
				//guardar los cambios y redireccionar
				if ($_POST['habilitado'] != 1){
					$habilitado = 0;
				} else {
					$habilitado = 1;
				}
				if ($_POST['auto_permitido'] != 1){
					$auto_permitido = 0;
					$fexpiracionbrevet = "";
				} else {
					$auto_permitido = 1;
					$fexpiracionbrevet = $_POST['fexpiracionbrevet'];
				}
				//fotografia
				if ($flag_imagen == true){
					$prefijo = substr(md5(uniqid(rand())), 0, 6);
					$nombre_archivo = $prefijo."_".$nombre_archivo;
					move_uploaded_file($_FILES['fotografia']['tmp_name'], "../../fotografia/".$nombre_archivo);
					//******adicionando permisos CHMOD
					chmod("../../fotografia/".$nombre_archivo, 0755);
					//*********************************
				} else {
					$nombre_archivo = false;
				}
				//fin fotografia
				//verificar si el ci existe
				$ci = trim($_POST['ci']);
				$identificador = $personal->encontrar_existe_personal($ci); 
				if ($identificador == null){			
					$personal->ingresar_personal($_POST['nombresp'],$_POST['apellidosp'],$_POST['ci'],$_POST['ciexpirar'],$_POST['fecnac'],$_POST['tiposangre'],$_POST['usuario_id'],$_POST['fecinicio'],$_POST['fecfin'],$_SESSION['fecha_hoy'],$auto_permitido,$fexpiracionbrevet,$_POST['telefono'],$habilitado,$_POST['legalizado'],$_SESSION['el_momento'],$_POST['observaciones'],$nombre_archivo);
					$smarty->assign('mensaje', "Los datos fueron almacenados correctamente");
				} else {			
				$personal->actualizar_personal($identificador[personal_id],$_POST['nombresp'],$_POST['apellidosp'],$_POST['ci'],$_POST['ciexpirar'],$_POST['fecnac'],$_POST['tiposangre'],$_POST['usuario_id'],$_POST['fecinicio'],$_POST['fecfin'],$_SESSION['fecha_hoy'],$auto_permitido,$fexpiracionbrevet,$_POST['telefono'],$habilitado,$_POST['legalizado'],$_SESSION['el_momento'],$_POST['observaciones'],$nombre_archivo);
				$smarty->assign('mensaje', "Los datos fueron actualizados correctamente");
				}

				//guardar tambien para la tabla puesto personal
				$identificador = $personal->encontrar_existe_personal($ci); 
				$personal_id = $identificador['personal_id'];
				//borramos todo de la tabla puesto personal
				$personal->borrar_personal_puesto($personal_id);
				//recorremos todos los campos que ha seleccionado
				if ($_POST['puesto'] != null){
					$lista = $_POST['puesto'];
					foreach( $lista as $indice => $valor){
						$personal->insertar_personal_puesto($personal_id, $valor, $_POST['usuario_id'], $_SESSION['fecha_hoy']);
					}
				}
				$_SESSION['ci'] = $_POST['ci'];
				$smarty->assign('ci', $_POST['ci']);
				//fin de borrar variables de sesion	
				$smarty->display('sistema_de_produccion/personal/busqueda.html');
			}
		}//Fin de guardar

		if ($_POST['buscar_puestos']){
			//Verificamos si el dato es valido
			if ($validar->validarTodo($_POST['ci'], 1, 100)){
				$error['err_ci_bus'] = "Ingresar CI";
			} else {
				$ci = (int)(trim( $_POST['ci']));
				//if ($validar->validarNumeros($ci, 1, 8)){
				if (!ereg("^[0-9]{5,}$", $ci)){
					$error['err_ci_bus'] = "Ingresar n&uacute;meros, mayor a 5 cifras";
				}
			}

			$_SESSION['ci'] = $_POST['ci'];	
			$smarty->assign('ci', $_POST['ci']);
			//Verificamos si existe error
			if (isset($error)){
				//reenviar los errores
				$smarty->assign('errores', $error);
				$smarty->display('sistema_de_produccion/personal/busqueda_puestos.html');
				$smarty->display('contenido/includes/pie.html');
			} else {
				//verificamos si existe el carnet
				$ci = trim($_POST['ci']);
				$identificador = $personal->encontrar_existe_personal($ci);
				if ($identificador == null){
					$smarty->assign('mensaje', "No existe ci");
					$smarty->display('sistema_de_produccion/personal/busqueda_puestos.html');
					$smarty->display('contenido/includes/pie.html');
				} else {
					$personal_id = $identificador['personal_id'];
					$puesto = $personal->sacar_datos_puesto_trabajo($personal_id);
					$smarty->assign('puesto', $puesto);
					$smarty->assign('completo', $identificador['completo']);
					
					//sacar los puestos
					$smarty->display('sistema_de_produccion/personal/busqueda_puestos.html');
					$smarty->display('sistema_de_produccion/personal/formulario_puestos.html');
					$smarty->display('contenido/includes/pie.html');
				}
			}
		}

		if ($_POST['guardar_puestos']){
			//echo "actualizar los puestos";
			$identificador = $personal->encontrar_existe_personal($_SESSION['ci']);
			$personal_id = $identificador['personal_id'];
	
			//borramos todo de la tabla puesto personal
			$personal->borrar_personal_puesto($personal_id);
			
			//recorremos todos los campos que ha seleccionado
			if ($_POST['puesto'] != null){
				$lista = $_POST['puesto'];
				foreach( $lista as $indice => $valor){
					$personal->insertar_personal_puesto($personal_id, $valor, $_POST['usuario_id'], $_POST['fecactual']);
				}
			}
			//$_SESSION['mensaje'] = "Puestos de trabajo actualizado";
			$smarty->assign('ci', $_SESSION['ci']);
			$smarty->assign('mensaje', "Puestos de trabajo actualizado");
			$smarty->display('sistema_de_produccion/personal/busqueda_puestos.html');
			$smarty->display('contenido/includes/pie.html');
		}
		//nombres busqueda
		if ($_POST['buscar_nombre']){
			$nombre_bus = trim($_POST['nombre_bus']);
			//Verificamos si el dato es valido
			if ($validar->validarTodo($nombre_bus, 1, 100)){
				$error['err_nombre_bus'] = "Ingresar Nombre";
			}

			$_SESSION['nombre_bus'] = $nombre_bus;	
			//Verificamos si existe error
			if (isset($error)){
				$smarty->assign('nombre_bus', $nombre_bus);
				$smarty->assign('errores', $error);

				$smarty->display('sistema_de_produccion/personal/busqueda_nombre.html');
				$smarty->display('contenido/includes/pie.html');
			} else {
				//verificamos si existe el carnet
				$smarty->assign('nombre_bus', $nombre_bus);
				$lista_personal = $personal->consulta_lista_personas($nombre_bus);

				if ($lista_personal == null){
					$smarty->assign('mensaje', "No existe la persona indicada");
					$smarty->display('sistema_de_produccion/personal/busqueda_nombre.html');
					$smarty->display('contenido/includes/pie.html');
				} else {
					//Lista de personas existe
					$smarty->assign('lista_personal', $lista_personal);//esta lista es la q se recupera en la inerfaz
					$smarty->display('sistema_de_produccion/personal/busqueda_nombre.html');
					$smarty->display('sistema_de_produccion/personal/detalle_nombres.html');
					$smarty->display('contenido/includes/pie.html');
				}
			}
		}
		

		//credenciales
		if ($_POST['credencial']){
			$ci = trim($_POST['ci']);
			$identificador = $personal->encontrar_existe_personal($ci);
			$personal_id = $identificador['personal_id'];
			$credencial = $personal->datos_credencial($personal_id);
			$smarty->assign('credencial', $credencial);
			$smarty->display('sistema_de_produccion/personal/credenciales.html');
		}// if ($_POST['buscar_nombre'])
		//mostrar credencial
		if ($_POST['mostrar_credencial']){
			$credencial['tipo'] = trim($_POST['tipo']);
			$credencial['pid'] = trim($_POST['pid']);
			$credencial['nombre'] = trim($_POST['nombre']);
			$credencial['cargo'] = trim($_POST['cargo']);
			$credencial['gs'] = trim($_POST['gs']);
			$credencial['fotografia'] = trim($_POST['fotografia']);
			
			switch ($credencial['tipo']) {
				case 't1':{
					$fotografia = "../../fotografia/".$credencial['fotografia'];
					$template = "../../fotografia/"."planilla.jpg";
					$nombre = $credencial['nombre'];
					$cargo = $credencial['cargo'];
					$gs = urlencode($credencial['gs']);
					$codigo = "*00".$credencial['pid']."00*";
					$imagen_credencial = "<img src='planilla.php?fotografia=".$fotografia."&credencial=".$template."&nombre=".$nombre."&cargo=".$cargo."&gs=".$gs."&codigo=".$codigo."' style='height:5.5cm;width:17cm;'/>";
					break;
				}
				case 't2':{
					$fotografia = "../../fotografia/".$credencial['fotografia'];
					$template = "../../fotografia/"."consultores.jpg";
					$nombre = $credencial['nombre'];
					$cargo = $credencial['cargo'];
					$gs = urlencode($credencial['gs']);
					$imagen_credencial = "<img src='consultores.php?fotografia=".$fotografia."&credencial=".$template."&nombre=".$nombre."&cargo=".$cargo."&gs=".$gs."' style='height:5.5cm;width:17cm;'/>";					
					break;
				}
				case 't3':{
					$fotografia = "../../fotografia/".$credencial['fotografia'];
					$template = "../../fotografia/"."servicios_externos.jpg";
					$nombre = $credencial['nombre'];
					$codigo = "*00".$credencial['pid']."00*";
					$imagen_credencial = "<img src='servicios_externos.php?fotografia=".$fotografia."&credencial=".$template."&nombre=".$nombre."&codigo=".$codigo."' style='height:5.5cm;width:17cm;'/>";
					break;
				}
			}
			$smarty->assign('imagen_credencial', $imagen_credencial);
			$smarty->assign('credencial', $credencial);
			$smarty->display('sistema_de_produccion/personal/credenciales.html');
		}// if ($_POST['buscar_nombre'])
		
	}
}
?>