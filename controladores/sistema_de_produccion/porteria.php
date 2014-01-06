<?php
session_start();
define('CLAS', "../../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once('../includes/seguridad.php');
require_once(SMARTY_DIR . 'Smarty.class.php');

include_once('../includes/fecha.php');
//
include('../../clases/class.phpMysqlConnection.php');
//
//include_once('../../clases/validador.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';

//Variables para cabecera
$smarty->assign('fecha', $fecha);
$smarty->assign('nombres', $_SESSION["nombres"]);
$smarty->assign('apellidos', $_SESSION["apellidos"]);

//
//$validar = new Validador();
function conexion (){
	$user="sistemas";
	$password="sistema135";
	$host="localhost";
	$database="macaws_bd";
/*
	$user="root";
	$password="";
	$host="localhost";
	$database="macaws_bd";
*/
	$sql = new phpMysqlConnection($user, $password, $host);
	$sql->SelectDB($database);
	return ($sql);
}
$sql = conexion();
$ncompleto = "";
$accion = "";
$observaciones = "";
$encontrado = 1;
$salida = 0;

$d = date("F d, Y H:i:s");
$hoy = date("Y-m-d");
//***echo $hoy;
$dia_textual = date("w");
//***echo "<br>Muestra >>".$dia_textual."<< <br>";
//

if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {
	if (isset($_GET['opcion']))
		$opcion = $_GET['opcion'];
	else
		$opcion = 'por_defecto';
		
	switch ($opcion) {
		case 'vehiculos' : {
//			$sql = conexion();
			$query = "select * from tautos" ;
			$sql->Query($query);
			
			for ($i=0; $i<$sql->rows; $i++) {
				$sql->GetRow($i);
				$smarty->append('resultado', array('idauto'=>$sql->data['idauto']
												 , 'autoplaca'=>$sql->data['autoplaca']
												 , 'autodesc'=>$sql->data['autodesc']));
			}
		
			$smarty->display('sistema_de_produccion/porteria/porteria_lista_autos.html');
			break;
		}
		case 'vehiculos_aceite' : {
//			$sql=conexion();
			
			$accion = $_GET['accion'];
			$vehiculo = $_GET['vehiculo'];
			$kilometraje = $_GET['kilometraje'];
			$cambio = 0;
			if ($accion == "aceite") {
				$query_aceite = "update tautos set aceite=".$kilometraje." where idauto=$vehiculo";
				$sql->Query($query_aceite);
				$cambio = 1;
			}
			
			$query = "select * from tautos";
			$sql->Query($query);
			
			for ($i = 0; $i < $sql->rows; $i++) {
				$sql->GetRow($i);
				$resta = (3000-($sql->data['kilometraje'] - $sql->data['aceite']));
				//echo "voy a restar 3000 - ". $sql->data['kilometraje'] ." con ". $sql->data['aceite']."<br>";
				$smarty->append('resultado', array('idauto'=>$sql->data['idauto']
												 , 'autoplaca'=>$sql->data['autoplaca']
												 , 'autodesc'=>$sql->data['autodesc']
												 , 'kilometraje'=>$sql->data['kilometraje']
												 , 'aceite'=>$sql->data['aceite']
												 , 'resta'=>$resta));
			}
			
			if ($cambio == 0) {
				//echo "lista de autos";
				$smarty->display('sistema_de_produccion/porteria/planta_lista_autos.html');
			}
			
			if ($cambio == 1) {
				header ("location: porteria.php?opcion=vehiculos_aceite");
			}
			
			break;
		}
		case 'planta_auto' : {
//			$sql=conexion();
			
			$idauto= $_GET['idauto'];
			$smarty->assign_by_ref('idauto',$idauto);
			$fecha1= $_POST['fecha1'];
			$fecha2= $_POST['fecha2'];
			
			if (!$fecha1 OR !$fecha2) {
				$fecha1= date("Y-m-d");
				$fecha2= date("Y-m-d");
			}
			
			$query = "select * from tautos, tautomov, personal where tautos.idauto=$idauto AND tautomov.idauto= $idauto AND fechaout >= '$fecha1' AND fechaout <= '$fecha2' AND personal.personal_id=tautomov.responsable" ;
			//echo "Planta :".$query;
			
			$smarty->assign_by_ref('fecha1',$fecha1);
			$smarty->assign_by_ref('fecha2',$fecha2);
			$sql->Query($query);
			
			for ($i=0; $i<$sql->rows; $i++) {
				$sql->GetRow($i);        
				$observaciones = $sql->data['observaciones'];
				
				if ($observaciones == "") {
					$observaciones = "-";
				}
				
				$nombre_completo = $sql->data['nombres'];
				$nombre_completo .= " ";
				$nombre_completo .= $sql->data['apellidos'];
				
				$smarty->assign_by_ref('idauto',$sql->data['idauto']);
				$smarty->assign_by_ref('autoplaca',$sql->data['autoplaca']);
				$smarty->assign_by_ref('kilometraje',$sql->data['kilometraje']);
				$smarty->assign_by_ref('autodesc',$sql->data['autodesc']);
				
				$smarty->append('resultado', array('observaciones' => $observaciones
												 , 'fechaout'=>$sql->data['fechaout']
												 , 'horaout'=>$sql->data['horaout']
												 , 'responsable'=>$sql->data['responsable']
												 , 'destino'=>$sql->data['destino']
												 , 'kmout'=>$sql->data['kmout']
												 , 'kmin'=>$sql->data['kmin']
												 , 'horain'=>$sql->data['horain']
												 , 'kmin'=>$sql->data['kmin'] 
												 , 'fechain'=>$sql->data['fechain']
												 , 'nombre_completo'=>$nombre_completo));
			}
			
			if ($sql->rows == 0) {
				$smarty->display('sistema_de_produccion/porteria/planta_ver_auto_error.html');
			} else {
				$smarty->display('sistema_de_produccion/porteria/planta_ver_auto.html');
			}
			
			break;
		}
		case 'porteria_movauto' : {
//			$sql = conexion();
			$accion = $_POST['accion'];
			//echo "<br>Accion: ".$accion;
					
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// En el caso de GUARDAR la ENTRADA de un VEHICULO
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

			if ($accion == "guardar_entrada") {
				if ($_POST['kmin'] == "" OR $_POST['kmin'] < $_POST['kmout'] OR is_numeric($_POST['kmin']) == 0 ) {
					$mensaje_error = "Imposible que llegue con menor Kilometraje con el que salio.<br> Verificar el <strong>KILOMETRAJE DE ENTRADA</strong>";
					$smarty->assign_by_ref('mensaje_error',$mensaje_error);
					$smarty->assign_by_ref('autoplaca',$_POST['autoplaca']);
					$smarty->assign_by_ref('autodesc',$_POST['autodesc']);
					$smarty->assign_by_ref('destino',$_POST['destino']);
					$smarty->assign_by_ref('kmout',$_POST['kmout']);
					$smarty->assign_by_ref('idauto',$_POST['idauto']);
					$smarty->assign_by_ref('observaciones',$_POST['observaciones']);
					$smarty->assign_by_ref('observaciones_ant',$_POST['observaciones_ant']);
					$smarty->assign_by_ref('responsable',$_POST['responsable']);
					$smarty->assign_by_ref('idmov',$_POST['idmov']);
		
					$smarty->display('sistema_de_produccion/porteria/porteria_movauto_in.html');
				} else {
					$fechain = date("Y-m-d");
					$horain = date("His");
					
					$obs = $_POST['observaciones_ant'];
					$obs.= "*********";
					$obs.= $_POST['observaciones'];
					
					$query_act = "UPDATE tautomov SET fechain='$fechain', horain=$horain, kmin=".$_POST['kmin']. ",observaciones= '". $obs."' where idmov=".$_POST['idmov'];
					//echo "Se ha guardado correctamente <br> $query_act <br> ";
					$sql->Query($query_act);
					
					$query_act_k = "UPDATE tautos SET kilometraje=".$_POST['kmin']." where idauto=".$_POST['idauto'];
					//echo "Se ha guardado correctamente <br> $query_act <br> ";
					$sql->Query($query_act_k);
					
					$correctamente = "ENTRADA DE VEHICULO CORRECTO";
					//header ("location:porteria.php?opcion=vehiculos");
				}
			}
			
			//////////////////////////////////////////////////////////////////////////////////////
			//  GUARDANDO 
			////////////////////////////////////////////////////////////////////////////////////
			if ($accion == "guardar_salida") {
				$mensaje_error = "";
				$responsable = $_POST['responsable'];
				$destino = $_POST['destino'];
				
				if ($responsable == 0 OR $destino == "") {
					$mensaje_error.= "Tiene que ingresar RESPONSABLE <br>";
					if ($destino == "") {
						$mensaje_error.= "Revise el DESTINO";
					}
					
					$smarty->assign_by_ref('mensaje_error',$mensaje_error);
					$smarty->assign_by_ref('autoplaca',$_POST['autoplaca']);
					$smarty->assign_by_ref('autodesc',$_POST['autodesc']);
					$smarty->assign_by_ref('destino',$_POST['destino']);
					$smarty->assign_by_ref('kmout',$_POST['kmout']);
					$smarty->assign_by_ref('idauto',$_POST['idauto']);
					$smarty->assign_by_ref('observaciones',$_POST['observaciones']);
					$query2 = "select * from personal where auto_permitido='1'";
					$sql->Query($query2);
					
					for ($j=0; $j<$sql->rows; $j++) {
						$sql->GetRow($j);
						$nombre_completo = $sql->data['nombres'] ." ". $sql->data['apellidos'];
						$smarty->append('resultado', array('nombre_completo' => $nombre_completo,'idpersona'=>$sql->data['personal_id']));
					}
					
					$smarty->display('sistema_de_produccion/porteria/porteria_movauto.html');
				} else {
					$idauto = $_POST['idauto'];
					$responsable = $_POST['responsable'];
					$destino = $_POST['destino'];
					$kmout = $_POST['kmout'];
					$observaciones = $_POST['observaciones'];
					
					$fechaout = date("Y-m-d");
					$horaout = date("His");
					$query_guardar = "insert into tautomov (idauto,responsable,destino,fechaout,horaout,kmout,observaciones) values ($idauto,$responsable,'$destino','$fechaout',$horaout,$kmout,'$observaciones')";
					//echo "Se ha guardado correctamente $query_guardar ";
					$sql->Query($query_guardar);
					
					$correctamente="SALIDA DE VEHICULO CORRECTO";
					header ("location:porteria.php?opcion=vehiculos");
				}
			}
		
			if (!$accion){
				///////////////////////////////////////////////////////////////////////
				//// VERIFICO SI ESTA ENTRANDO O SALIENDO UN VEHICULO
				///////////////////////////////////////////////////////////////////////
				$idauto = $_GET['idauto'];
				$query_2 = "select * from tautomov where tautomov.idauto=$idauto AND tautomov.fechain='0000-00-00' AND tautomov.horain='00:00:00' ";
				//echo "El query: <br> $query_2 <br>";
				
				$sql->Query($query_2);
				$registros = $sql->rows;
				
				////////////////////////////////////////////////////////////////////
				/// AQUI ES CUANDO ENTRA UN VEHICULO
				//////////////////////////////////////////////////////////////////// 
				if ($registros > 0 ) {
					$idauto = $_GET['idauto'];
					$query = "select * from tautos where idauto=$idauto";					
					$sql->Query($query);	
					$sql->GetRow(0);
					$smarty->assign_by_ref('idauto',$sql->data['idauto']);
					$smarty->assign_by_ref('autoplaca',$sql->data['autoplaca']);
					$smarty->assign_by_ref('autodesc',$sql->data['autodesc']);
					$observaciones_ant=$_POST['observaciones_ant'];
					
					$cambio = $sql->data['aceite'];
					$rec = $sql->data['kilometraje'] - $cambio;
					$por_rec = 3000 - $rec;
					if ($por_rec <= 500 )
						$mensaje = "!!!!!!!! Avisar para el cambio de Aceite !!!!!!";
					else
						$mensaje = "Este vehiculo tiene por recorrer <b>". $por_rec. "</b> Km. antes del cambio de aceite";
					
					$query_mov = "select * from tautomov, personal where idauto=$idauto AND fechain='0000-00-00' AND horain='00:00:00' AND tautomov.responsable=personal.personal_id";
					$sql->Query($query_mov);	
					$sql->GetRow(0);
					$nombre_completo=$sql->data['nombres']." ".$sql->data['apellidos'];
					$smarty->assign_by_ref('responsable',$nombre_completo);
					$smarty->assign_by_ref('destino',$sql->data['destino']);
					$smarty->assign_by_ref('observaciones_ant',$sql->data['observaciones']);
					$smarty->assign_by_ref('kmout',$sql->data['kmout']);
					$smarty->assign_by_ref('idmov',$sql->data['idmov']);
					
					$smarty->assign('mensaje', $mensaje);
					$smarty->display('sistema_de_produccion/porteria/porteria_movauto_in.html');
				} else {
					//-------------------
					$idauto = $_GET['idauto'];
					$query = "select * from tautos where idauto=$idauto";
					$sql->Query($query);	
					$sql->GetRow(0);
					$smarty->assign_by_ref('idauto',$sql->data['idauto']);
					$smarty->assign_by_ref('autoplaca',$sql->data['autoplaca']);
					$smarty->assign_by_ref('autodesc',$sql->data['autodesc']);
					$smarty->assign_by_ref('kmout',$sql->data['kilometraje']);
					
					$cambio = $sql->data['aceite'];
					$rec = $sql->data['kilometraje'] - $cambio;
					$por_rec = 3000 - $rec;
					
					if ($por_rec <= 500 )
						$mensaje = "!!!!!!!! Avisar para el cambio de Aceite !!!!!!";
					else
						$mensaje = "Este vehiculo tiene por recorrer <b>". $por_rec. "</b> Km. antes del cambio de aceite";
					
					//-------------------- 
					$query2 = "select * from personal where auto_permitido='1'";
					
					$sql->Query($query2);	
					for($j=0; $j<$sql->rows; $j++) {
						$sql->GetRow($j);
						$nombre_completo =  $sql->data['nombres'] ." ". $sql->data['apellidos'];
						$smarty->append('resultado', array('nombre_completo' => $nombre_completo, 'idpersona'=>$sql->data['personal_id']));
					}
					$smarty->assign('mensaje', $mensaje);
					$smarty->display('sistema_de_produccion/porteria/porteria_movauto.html');
				}
			}
			break;
		}
		case 'por_defecto' : {
			//******************************todo el contenido
			//Aqui descubro cuando fue ayer, si es lunes entonces mi ayer es sábado, cc es un dia menos
			if ($dia_textual == "1") {
				$ayer = date("Y-m-d", mktime(0, 0, 0, date("m") , date("d")-2, date("Y")));
			} else {
				$ayer = date("Y-m-d", mktime(0, 0, 0, date("m") , date("d")-1, date("Y")));
			}
			
			//echo "<br>--<br>";
			//***echo "Ayer me sale: ".$ayer. " y dia textual: ". $dia_textual;
			
			$ahora = date("H:i:s");
			$ahora_a = explode (":", $ahora);
			$hora = $ahora_a[0];
			$minuto = $ahora_a[1];
			
			$barcode = $_POST['barcode'];
			$magico = $barcode. date (i);
			$dia = date (A);
			
			$accion = "";
			
			if ($magico == $_SESSION['escondido']) {
				$accion = "ERROR - MISMO USUARIO";
			} else {
				$_SESSION['escondido'] = $magico;
			
				if ($barcode) {
					////////////////////////// CODIGO DE PERSONA /////////////////////////////
					$idpersona = substr($barcode, 2, -2);
					
					//////////////////////ENCUENTRO EL NOMBRE DE LA PERSONA //////////////////
					$busca_per = "SELECT nombres, apellidos FROM personal WHERE personal_id = ".$idpersona;
					//echo $busca_per;
					$sql->QueryRow ($busca_per);
					$rows = $sql->rows;
					//echo "SALE: ".$rows;
					
					if ($rows > 0) {
						$ncompleto = $sql->data['nombres'];
						$ncompleto.= " ".$sql->data['apellidos'];
					} else {
						$encontrado = 0;
					}
					////////////////////////////////////////////////////////////////////////////
					
					if ($encontrado) {
						$busca = "SELECT fechain from inoutpersonas where idpersona=".$idpersona . " AND fechaout='0000-00-00'";
						//echo "$busca";
						$sql->QueryRow ($busca);
						$rows = $sql->rows;
						
						//Encuentro que ya existe una entrada
						if ($rows > 0) {
							////////////////////////
							//En el caso de que la fecha de entrada no sea el mismo dia. es decir no tickeron la salida del dia anterior.
							////////////////////////
							
							$fechain = $sql->data['fechain'];
							
							if ($fechain != $hoy) {
								//Primero actualiza el de ayer.
								$obs = $obs_ante."Salida anterior modificada";
								$actualiza = "update inoutpersonas set fechaout = '$hoy', horaout = '$ahora', observaciones = '.$obs.' where idpersona = ".$idpersona ." AND fechaout = '0000-00-00'";
								$sql->Query($actualiza);
								////////////////////////
							} else {
								$actualiza = "update inoutpersonas set fechaout = '$hoy', horaout = '$ahora' where idpersona = ".$idpersona ." AND fechaout = '0000-00-00'";
								$sql->Query($actualiza);
								$accion = "SALIDA";
								$salida = 1;
							}
						}
						
						if ($salida == 0) {
							//Aqui es cuando hago solamente Entradas ////////
							//Primero me fijo si es primera entrada del dia//
							/////////////////////////////////////////////////
							$busca_hoy = "SELECT horain from inoutpersonas where idpersona = ".$idpersona . " AND fechain = '".$hoy."' ORDER BY codinout DESC";
							//echo "<br> $busca_hoy <br>";
							$sql->QueryRow ($busca_hoy);
							$rows = $sql->rows;
							$horain = $sql->data['horain'];
							$resultado = "";
							//En el caso de que sea la primera entrada
							if ($rows == 0) {
								//echo "<br>Es primera vez que entra el dia de hoy <br>";
								//Revisaré si vino el día anterior, En caso de Lunes mi dia anterior es Sábado
								$busca_ayer = "SELECT horain from inoutpersonas where idpersona = ".$idpersona . " AND fechain = '".$ayer."' ORDER BY codinout DESC";
								//echo $busca_ayer."<br>";
								$sql->QueryRow ($busca_ayer);
								$rows = $sql->rows;
								$horain = $sql->data['horain'];
								
								if (!$rows) {
									//echo "Aparentemnet no vino ayer".$ayer;
									$verificar = "Verificar fecha ".$ayer;
									//Como no vino ayer, aumentaré un registro con fecha de ayer
									$ins = "INSERT into inoutpersonas (idpersona, fechain, horain, fechaout, horaout, observaciones) VALUES ($idpersona, '$ayer', '03:33:33', '$ayer', '03:33:33', '$verificar')";
									//echo $ins;
									$sql->Query ($ins);
								}
								
								if ($dia == 'AM') {
									if ($hora > '8')
										$resultado = "Retraso";
									if ($hora == '8' && $minuto > '00')
										$resultado ="Retraso";
								}
								
								if ($dia == 'PM') {
									if ($hora > '13')
										$resultado = "Retraso";
									if ($hora == '13' && $minuto > '00')
										$resultado = "Retraso";
								}
							//Termina si es la primera vez
							} else {
								//echo "<br>No es la primera vez que entras ya entro a las $horain <br>";
								list($horau, $minu, $segu) = split('[/.-]', $horain);
								
								if ($dia == 'PM' && $horau < '12') {
									if ($hora > '13')
										$resultado = "Retraso";
									if ($hora == '13' && $minuto > '00')
										$resultado ="Retraso";
									if ($hora == '13' && $minuto > '00')
										$resultado ="Retraso";
								}
							}
							
							//echo "Su estado es :".$resultado."<br>";
							//////////////////////////////////////////
							
							$ins = "INSERT into inoutpersonas (idpersona, fechain, horain, observaciones) VALUES ($idpersona, '$hoy', '$ahora', '$resultado')";
							//echo $ins;
							$sql->Query ($ins);
							
							if ($resultado == "") {
								$accion = "ENTRADA";
							} else
								$accion = "ENTRADA FUERA DE HORA";
						}
					} else {
						//En caso de que usario no haya sido encontrado en el sistema
						$ncompleto = "ERROR DE USUARIO";
						$accion = "ERROR DE USUARIO";
					}
				} else
					$accion = "ERROR DE ENTRADA";
			}
			$smarty->assign_by_ref('f',$d);
			$smarty->assign('ncompleto',$ncompleto);
			$smarty->assign('accion',$accion);
			$smarty->assign('ahora',$ahora);
			$smarty->assign('barcode',$barcode);
			$smarty->display('sistema_de_produccion/porteria/entrada_salida.html');
			//******************************fin todo el contenido
			break;
		}
		
		case 'materiales' : {
			echo "materiales";
			
			break;
		}
	}
}

?>