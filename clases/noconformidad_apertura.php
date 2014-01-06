<?php

include_once('../clases/includes/dbmanejador.php');  

class NoConformidad_apertura
{
	function ingresar_apertura($revision,$tipo,$accion,$area_observada,$area_informada,$cierre,$motivo,$descripcion,$responsable_observada,$responsable_informada)
	{
	   $nocf = new NoConformidad_apertura();
	   $fec_plan=$nocf->fechaCierre($cierre);
       $revision=7;//para cambiar el numero de revision solo poner aqui otro valor
		$con = new DBmanejador();
        if($con->conectar() == true){
			$consulta = "
			INSERT INTO `no_conformidad`
			(`nro_revision`, `tipo`,`accion`, `area_observada`, `area_informada`,`cierre`,`fec_plan_cierre`,`motivo`,`descripcion`,`responsable_observada`,
			`responsable_informada`,`fec_apertura`,`estado`)
			VALUES
			('".$revision."', '".$tipo."', '".$accion."','".$area_observada."','".$area_informada."', '".$cierre."','".$fec_plan."','".$motivo."','".$descripcion."','".$responsable_observada."',
			'".$responsable_informada."',CURRENT_DATE,4)
			";
			//echo $consulta;//el estado de ingreso es 4=no aprobado por gerencia
			$resultado = mysql_query($consulta) or die ('La consulta -ingresar_apertura- fall&oacute;: ' . mysql_error());
		}
	}
    function fechaCierre($cierre)
    {
        /*Analisis determinar Fecha Planeada par Cierre
        SELECT DATE_ADD('2010-01-30', INTERVAL 1 DAY);  [Para cuando se declare tiempo de cierre 1 dia]
        SELECT DATE_ADD('2010-01-30', INTERVAL 1 WEEK);  [Para cuando se declare tiempo de cierre 1 semana]
        SELECT DATE_ADD('2010-01-30', INTERVAL 1 MONTH);  [Para cuando se declare tiempo de cierre 1 mes]
        SELECT DATE_ADD('2010-01-30', INTERVAL 6 MONTH);  [Para cuando se declare tiempo de cierre 1/2 anio=6 meses]
        SELECT CURRENT_DATE;*/
        $con = new DBmanejador();
        
        switch($cierre)
        {
            case"1 dia":{//cuando se declara tiempo de cierre 1 dia
            if($con->conectar() == true){
			$consulta = "SELECT DATE_ADD(CURRENT_DATE, INTERVAL 1 DAY) as hoy;
			";
            $resultado = mysql_query($consulta) or die ('La consulta fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
		 	else {
			    $contador=0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta = $row['hoy'];
					$contador++;
                    return $respuesta;
				}
			}
		}
            }break;
            case"1 semana":{
            //cuando se declara tiempo de cierre 1 semana
            if($con->conectar() == true){
			$consulta = "SELECT DATE_ADD(CURRENT_DATE, INTERVAL 1 WEEK) as hoy;
			";
            $resultado = mysql_query($consulta) or die ('La consulta fall&oacute;: ' . mysql_error());
			if (!$resultado)
				return false;
		 	else {
			    $contador=0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta = $row['hoy'];
					$contador++;
                    return $respuesta;
				}
			}
		}
            }break;
            case"1 mes":{
                //cuando se declara tiempo de cierre 1 mes
            if($con->conectar() == true){
			$consulta = "SELECT DATE_ADD(CURRENT_DATE, INTERVAL 1 MONTH) as hoy;
			";
            $resultado = mysql_query($consulta) or die ('La consulta fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
		 	else {
			    $contador=0;
				while($row = mysql_fetch_array($resultado)){
				    $respuesta = $row['hoy'];
					$contador++;
                    return $respuesta;
				}
			}
		}
            }break;
            case"1/2 año":{
                //cuando se declara tiempo de cierre 1/2 año
            if($con->conectar() == true){
			$consulta = "SELECT DATE_ADD(CURRENT_DATE, INTERVAL 6 MONTH) as hoy;
			";
            $resultado = mysql_query($consulta) or die ('La consulta fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
		 	else {
			    $contador=0;
				while($row = mysql_fetch_array($resultado)){
				    $respuesta = $row['hoy'];
					$contador++;
                    return $respuesta;
				}
			}
		    }
            }break;
        }
    }
        
	/*Ingresar el analisis de una No Conformidad existente
    Parametros:
    $registro= nro de registro RG-40 del q se registrara el analisis y accion correspondiente
    $disposicion= disposicion tomada para la no conformidad
    $analisis_causa= se realiza una descripcion de la no conformidad
    $accion_inmediata= accion inmediata tomada para este RG-40 especifico
    $accion_otras= otro tipo de accion tomada para este RG-40 especifico
    */
	function ingresar_analisis($registro,$disposicion,$analisis_causa,$accion_inmediata,$accion_otras)
	{
		$con = new DBmanejador();
        if($con->conectar() == true){
        
			$consulta = "
			UPDATE	no_conformidad
			SET		
                    disposicion='".$disposicion."',
					analisis_causa='".$analisis_causa."',
					accion_inmediata='".$accion_inmediata."',
					accion_otras='".$accion_otras."',
					fec_analisis=CURRENT_DATE,
                    estado='2'
			WHERE	nro_registro = '".$registro."'
			";
			//echo $consulta;
			$resultado = mysql_query($consulta) or die ('La consulta -ingresar_apertura- fall&oacute;: ' . mysql_error());
		}
	}
	/*Ingresar los datos de la revision de una No Conformidad existente
    Parametros:
    $registro= nro de registro RG-40 del q se registrara la revision correspondiente
    $responsable_cumple= responsable encargado del cumplimiento de las acciones de mejora o correctivas
    $fec_cumplimiento= fecha de cuando se llego a la meta
    $fec_ver_cumplimiento= fecha de verificacion en fecha de cumplimiento del proceso RG-40
    $fec_ver_extension= fecha de verificacion en fecha de extension del proceso RG-40
    $fec_extension= fecha de extension de cumplimiento de la meta
    $efectividad= se ingresa si fueron efectivas o no las medidas llevadas a cabo
    $causa_extension= motivo por el cual se modifico la fecha del cumplimiento
    */
	function ingresar_revision($registro,$responsable_cumple,$fec_cumplimiento,$fec_ver_cumplimiento,$fec_extension,$fec_ver_extension,$efectividad,$efectividad_ext,$causa_extension)
	{
		if($causa_extension=='')
       $causa_extension='No hubo extención del plazo.';

	   if($efectividad=="Efectivo")
       {$efectividad_ext="--";}
        $con = new DBmanejador();
        if($con->conectar() == true){
			$consulta = "
			UPDATE	no_conformidad
			SET		responsable_cumplimiento='".$responsable_cumple."',
					fec_cumplimiento='".$fec_cumplimiento."',
                    fec_ver_cumplimiento='".$fec_ver_cumplimiento."',
					fec_extension='".$fec_ver_extension."',
                    fec_ver_extension='".$fec_ver_extension."',
					efectividad='".$efectividad."',
                    efectividad_ext='".$efectividad_ext."',
                    causa_extension='".$causa_extension."',
                    fec_revision=CURRENT_DATE,
                    estado='3'
			WHERE	nro_registro = '".$registro."'
			";
			//echo $consulta;
			$resultado = mysql_query($consulta) or die ('La consulta -ingresar_apertura- fall&oacute;: ' . mysql_error());
		}
	}
    /*Ingresar los datos del cierre de una No Conformidad existente
    Parametros:
    $nro_registro= nro de registro RG-40 del q se registrara el cierre correspondiente
    $efectividad= se determina la efectividad o resultado aplicado el RG-40
    $aplica_comunicacion= determina si se necesito comunicacion con el cliente para este RG-40
    $comunicacion_cliente= un resumen de lo q se hablo con el cliente el acuerdo al q se llego o conclusion
    $responsable_contacto= persona responsable de hablar con el cliente
    $fec_contacto= fecha en q se realizo el contacto con el cliente
    */
    function ingresar_cierre($nro_registro,$efectividad,$aplica_comunicacion,$comunicacion_cliente,$responsable_contacto,$fec_contacto){
		$con = new DBmanejador();
        if($con->conectar() == true){
			 $consulta = "
			UPDATE	no_conformidad
			SET	accion_resultado='".$efectividad."',
				comunicacion_cliente='".$comunicacion_cliente."',
                aplica_comunicacion='".$aplica_comunicacion."',
                responsable_contacto='".$responsable_contacto."',
				fec_contacto='".$fec_contacto."',	
                fec_cierre=CURRENT_DATE,
                estado=5
			WHERE	nro_registro = ".$nro_registro;
			$resultado = mysql_query($consulta) or die ('La consulta -ingresar_aperturav- fall&oacute;: ' . mysql_error());//antes era -modificar_estado_impresion_re-
		}//estado=0
	}
    /*Permite obtener el nombre correspondiente a cada area segun el id de area correspondiente en el RG-40 */
    function arreglo_areas($observa)
    {		
        $con = new DBmanejador();
		if($con->conectar() == true)
        { 
           $observa=explode(',', $observa);
           foreach($observa as $indice => $valor)
		   {
			$consulta = "
			SELECT		nombre_area 
			FROM 		area as a 
            WHERE       id_area='".$valor."' 
			ORDER BY	nombre_area
			";
            $resultado = mysql_query($consulta) or die ('La consulta busqueda area fall&oacute;: ' . mysql_error());//echo $resultado; 
			if (!$resultado)
				return false;
		 	else 
             {
            if ($row = mysql_fetch_array($resultado)) {
                if($indice==0)
				   $respuesta['nombre_area'] = $row['nombre_area']; 
                 else
                   $respuesta['nombre_area'] = $respuesta['nombre_area'].",".$row['nombre_area'];
			}	
		  	}
		   }return $respuesta;
        }
    }
    
    /*Permite obtener el nombre del responsable correspondiente a cada area segun el id de area correspondiente en el RG-40 */
    function arreglo_responsables($observa)
    {
        $con = new DBmanejador();
		if($con->conectar() == true)
        { 
           $observa=explode(',', $observa);
           foreach($observa as $indice => $valor)
		   {
			$consulta = "    
            SELECT		concat(nombres,' ', apellidos) as completo
            FROM 		personal 
            WHERE       personal_id='".$valor."' 
            ORDER BY	apellidos
			";
            $resultado = mysql_query($consulta) or die ('La consulta busqueda area fall&oacute;: ' . mysql_error());//echo $resultado; 
			if (!$resultado)
				return false;
		 	else 
             {
            if ($row = mysql_fetch_array($resultado)) {
                if($indice==0)
				   $respuesta['completo'] = $row['completo']; 
                 else
                   $respuesta['completo'] = $respuesta['completo'].",".$row['completo'];
			}	
		  	}
		   }return $respuesta;
        }
    }
    
    /*reporte_RG40 permite sacar reporte de acuerdo al estado=1 vigentes estado=0 cerradas
    Parametros:
    $estado= estado del registro RG-40 
    */
    function reporte_RG40($estado)
	{
    $nocf = new NoConformidad_apertura();
	$con = new DBmanejador();
        if($estado==1)
        {
            if($con->conectar() == true){
			$consulta = "SELECT nro_registro,tipo,accion,area_observada,area_informada,motivo,cierre,fec_apertura,imprimir_ap,estado
            FROM  no_conformidad as nc
            WHERE nc.estado=1
            ORDER BY nro_registro";
            $resultado = mysql_query($consulta) or die ('La consulta -reporte registros RG-40 fallo- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
		 	else {
			    $contador=0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]['nro_registro'] = $row['nro_registro'];
					$respuesta[$contador]['tipo'] = $row['tipo'];
					$respuesta[$contador]['accion'] = $row['accion'];
					$areaobservadas =$row['area_observada'];
                    $area_observada=$nocf->arreglo_areas($areaobservadas);
                   foreach($area_observada as $indice => $valor) 
				  {
                  if ($indice == 0)
                  {
                     $observada=$valor;
                     $observa=explode('-', $observada);
                     $observadas=$observa[0];
                  }
                  else
                  {
                     $observada=$valor;
                     $observa=explode('-', $observada); 
                     $observadas=$observadas.",".$observa[0];
                  }
				 } 
				    $respuesta[$contador]['area_observada']=$observadas;
                    $areainformadas =$row['area_informada'];
                            $area_informada=$nocf->arreglo_areas($areainformadas);
                            foreach($area_informada as $indice => $valor) 
				            {
                            if ($indice == 0)
                            {
                            $informada=$valor;
                            $informa=explode('-', $informada);
                            $informadas=$informa[0];
                            }
                            else
                            {
                            $informada=$valor;
                            $informa=explode('-', $informada); 
                            $informadas=$informadas.",".$informa[0];
                            }
				            }
                    $respuesta[$contador]['area_informada']=$informadas;
					$respuesta[$contador]['motivo'] = $row['motivo'];
					$respuesta[$contador]['cierre'] = $row['cierre'];
					$respuesta[$contador]['fec_apertura'] = $row['fec_apertura'];
                    $respuesta[$contador]['imprimir_ap'] = $row['imprimir_ap'];
                    $respuesta[$contador]['estado'] = $row['estado'];
					$contador++;
				}
				return $respuesta;
			}
		}
        }
            if($estado==2)
        {
            if($con->conectar() == true){
			$consulta = "
            SELECT nro_registro,disposicion,analisis_causa,accion_inmediata,accion_otras,fec_analisis,imprimir_an,estado
			FROM  no_conformidad as nc
			WHERE nc.estado=2
			
			ORDER BY nro_registro
			";
            $resultado = mysql_query($consulta) or die ('La consulta -reporte registros RG-40 fallo- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
		 	else {
			    $contador=0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]['nro_registro'] = $row['nro_registro'];
					$respuesta[$contador]['disposicion'] = $row['disposicion'];
					$respuesta[$contador]['analisis_causa'] = $row['analisis_causa'];
					$respuesta[$contador]['accion_inmediata'] = $row['accion_inmediata'];
					$respuesta[$contador]['accion_otras'] = $row['accion_otras'];
					$respuesta[$contador]['fec_analisis'] = $row['fec_analisis'];
                    $respuesta[$contador]['imprimir_an'] = $row['imprimir_an'];
                    $respuesta[$contador]['estado'] = $row['estado'];
					$contador++;
				}
				return $respuesta;
			}
		}
        }
        if($estado==3)
        {
            if($con->conectar() == true){
			$consulta = "
            SELECT nro_registro,concat(nombres,' ',apellidos) as responsable_cumplimiento,fec_cumplimiento,fec_ver_cumplimiento,fec_extension,fec_ver_extension,efectividad,efectividad_ext,causa_extension,nc.estado,fec_plan_cierre,imprimir_re
			FROM  personal as p,no_conformidad as nc
			WHERE nc.responsable_cumplimiento=p.personal_id and nc.estado=3
			ORDER BY nro_registro
			";
            $resultado = mysql_query($consulta) or die ('La consulta -reporte registros RG-40 fallo- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
		 	else {
			    $contador=0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]['nro_registro'] = $row['nro_registro'];
					$respuesta[$contador]['responsable_cumplimiento'] = $row['responsable_cumplimiento'];
					$respuesta[$contador]['fec_cumplimiento'] = $row['fec_cumplimiento'];
					$respuesta[$contador]['fec_ver_cumplimiento'] = $row['fec_ver_cumplimiento'];
					$respuesta[$contador]['fec_extension'] = $row['fec_extension'];
					$respuesta[$contador]['fec_ver_extension'] = $row['fec_ver_extension'];
                    $respuesta[$contador]['efectividad'] = $row['efectividad'];
                    $respuesta[$contador]['efectividad_ext'] = $row['efectividad_ext'];
					$respuesta[$contador]['causa_extension'] = $row['causa_extension'];
					$respuesta[$contador]['estado'] = $row['estado'];
                    $respuesta[$contador]['fec_plan_cierre'] = $row['fec_plan_cierre'];
                    $respuesta[$contador]['imprimir_re'] = $row['imprimir_re'];
                    
					$contador++;
				}
				return $respuesta;
			}
		}
        }
        if($estado==0)//estado=0 CIERRE
        {
            if($con->conectar() == true){
			$consulta= "select nro_registro,accion_resultado,comunicacion_cliente,aplica_comunicacion,responsable_contacto,fec_contacto,fec_cierre,estado,imprimir_ci
                        from no_conformidad
                        where estado=0 
                        order by nro_registro";
                        
          //  echo $consulta;
            $resultado = mysql_query($consulta) or die ('La consulta -reporte registros RG-40- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
		 	else {
			    $contador=0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]['nro_registro'] = $row['nro_registro'];
					$respuesta[$contador]['accion_resultado'] = $row['accion_resultado'];
					$respuesta[$contador]['comunicacion_cliente'] = $row['comunicacion_cliente'];
                    $respuesta[$contador]['aplica_comunicacion'] = $row['aplica_comunicacion'];
					$respuesta[$contador]['responsable_contacto'] = $row['responsable_contacto'];
					$respuesta[$contador]['fec_contacto'] = $row['fec_contacto'];
					$respuesta[$contador]['fec_cierre'] = $row['fec_cierre'];
                    $respuesta[$contador]['estado'] = $row['estado'];
                    $respuesta[$contador]['imprimir_ci'] = $row['imprimir_ci'];
					$contador++;
				}
				return $respuesta;
			}
		}
        }

	}
    
/*Imprimir apertura*/
function imprimir_apertura($id_doc)
{
        $nocf = new NoConformidad_apertura();
		$con = new DBmanejador();
		if($con->conectar()==true)
		{
			$consulta= "select nro_registro,nro_revision,tipo,accion,area_observada,area_informada,responsable_observada,fec_apertura,cierre,motivo,descripcion,imprimir_ap,estado
                        from no_conformidad
                        where nro_registro='".$id_doc."'
			";
			//echo $consulta; 
			$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());	
				if ($row = mysql_fetch_array($resultado))
			{
					$lista['nro_registro'] = $row['nro_registro'];
					$lista['nro_revision'] = $row['nro_revision'];
					$lista['tipo'] = $row['tipo'];
					$lista['accion'] = $row['accion'];
                            $areaobservadas =$row['area_observada'];
                            $area_observada=$nocf->arreglo_areas($areaobservadas);
                            foreach($area_observada as $indice => $valor) 
				            {
                            if ($indice == 0)
                            {
                            $observada=$valor;
                            $observa=explode('-', $observada);
                            $observadas=$observa[0];
                            }
                            else
                            {
                            $observada=$valor;
                            $observa=explode('-', $observada); 
                            $observadas=$observadas.",".$observa[0];
                            }
				            } 
                    $lista['area_observada'] = $observadas;
                    $respobservadas =$row['responsable_observada'];
                            $resp_observada=$nocf->arreglo_responsables($respobservadas);
                            foreach($resp_observada as $indice => $valor) 
				            {
                            if ($indice == 0)
                            {
                            $responsable=$valor;
                            $respon=explode('-', $responsable);
                            $responsables=$respon[0];
                            }
                            else
                            {
                            $responsable=$valor;
                            $respon=explode('-', $responsable); 
                            $responsables=$responsables.",".$respon[0];
                            }
				            } 
                    $lista['responsable_observada'] = $responsables;
					$lista['fec_apertura'] = $row['fec_apertura'];
					$lista['cierre'] = $row['cierre'];
					$lista['motivo'] = $row['motivo'];
					$lista['descripcion'] = $row['descripcion'];
                    $areainformadas =$row['area_informada'];
                            $area_informada=$nocf->arreglo_areas($areainformadas);
                            foreach($area_informada as $indice => $valor) 
				            {
                            if ($indice == 0)
                            {
                            $informada=$valor;
                            $informa=explode('-', $informada);
                            $informadas=$informa[0];
                            }
                            else
                            {
                            $informada=$valor;
                            $informa=explode('-', $informada); 
                            $informadas=$informadas.",".$informa[0];
                            }
				            } 
                    $lista['area_informada'] = $informadas;
					$lista['imprimir_ap'] = $row['imprimir_ap'];
					$lista['estado'] = $row['estado'];
				return $lista;
			} 
			else {
				return false;
			     }
	}
}
/*Imprimir analisis y accion*/
function imprimir_accion($id_doc)
{       
    $nocf = new NoConformidad_apertura();
		$con = new DBmanejador;
		if($con->conectar()==true)
		{
			$consulta= "SELECT nro_registro,nro_revision,tipo,accion,area_observada,area_informada,responsable_observada,fec_apertura,cierre,motivo,descripcion,disposicion,analisis_causa,accion_inmediata,accion_otras,fec_analisis
			FROM  no_conformidad
			WHERE nro_registro='".$id_doc."'
			";
			//echo $consulta; 
			$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());	
				if ($row = mysql_fetch_array($resultado))
			{
			        $lista['nro_registro'] = $row['nro_registro'];
                    //se realizo el cambio de abajo porq en interfaz no podia realizar la comparacion directa de lo q recuperaba con lo de smarty en html
                   if(strcmp($row['disposicion'],"Usar con autorización")==0)
                    {  $cambio="Usar";
                       $lista['disposicion']=$cambio;
                    }
                   else
                   //apertura
					$lista['nro_revision'] = $row['nro_revision'];
					$lista['tipo'] = $row['tipo'];
					$lista['accion'] = $row['accion'];
                            $areaobservadas =$row['area_observada'];
                            $area_observada=$nocf->arreglo_areas($areaobservadas);
                            foreach($area_observada as $indice => $valor) 
				            {
                            if ($indice == 0)
                            {
                            $observada=$valor;
                            $observa=explode('-', $observada);
                            $observadas=$observa[0];
                            }
                            else
                            {
                            $observada=$valor;
                            $observa=explode('-', $observada); 
                            $observadas=$observadas.",".$observa[0];
                            }
				            } 
                    $lista['area_observada'] = $observadas;
                    $respobservadas =$row['responsable_observada'];
                            $resp_observada=$nocf->arreglo_responsables($respobservadas);
                            foreach($resp_observada as $indice => $valor) 
				            {
                            if ($indice == 0)
                            {
                            $responsable=$valor;
                            $respon=explode('-', $responsable);
                            $responsables=$respon[0];
                            }
                            else
                            {
                            $responsable=$valor;
                            $respon=explode('-', $responsable); 
                            $responsables=$responsables.",".$respon[0];
                            }
				            } 
                    $lista['responsable_observada'] = $responsables;
					$lista['fec_apertura'] = $row['fec_apertura'];
					$lista['cierre'] = $row['cierre'];
					$lista['motivo'] = $row['motivo'];
					$lista['descripcion'] = $row['descripcion'];
                    $areainformadas =$row['area_informada'];
                            $area_informada=$nocf->arreglo_areas($areainformadas);
                            foreach($area_informada as $indice => $valor) 
				            {
                            if ($indice == 0)
                            {
                            $informada=$valor;
                            $informa=explode('-', $informada);
                            $informadas=$informa[0];
                            }
                            else
                            {
                            $informada=$valor;
                            $informa=explode('-', $informada); 
                            $informadas=$informadas.",".$informa[0];
                            }
				            } 
                    $lista['area_informada'] = $informadas;
                   //fin apertura
                    $lista['disposicion'] = $row['disposicion'];
					$lista['analisis_causa'] = $row['analisis_causa'];
					$lista['accion_inmediata'] = $row['accion_inmediata'];
					$lista['accion_otras'] = $row['accion_otras'];
					$lista['fec_analisis'] = $row['fec_analisis'];
				return $lista;
			} 
			else {
				
				return false;
			}				
	}
}
/*Imprimir revision */
function imprimir_revision($id_doc)
{
	   $nocf = new NoConformidad_apertura();
		$con = new DBmanejador;
		if($con->conectar()==true)
		{
			$consulta= "
            SELECT nro_registro,nro_revision,tipo,accion,area_observada,area_informada,responsable_observada,fec_apertura,cierre,motivo,descripcion,disposicion,analisis_causa,accion_inmediata,accion_otras,fec_analisis,concat(p.nombres,' ',p.apellidos) as responsable_cumplimiento,fec_cumplimiento,fec_ver_cumplimiento,fec_extension,fec_ver_extension,efectividad,efectividad_ext,causa_extension
			FROM  no_conformidad as nc, personal as p
			WHERE p.personal_id=responsable_cumplimiento and nro_registro='".$id_doc."' 
			";
			//echo $consulta; 
			$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());	
			if ($row = mysql_fetch_array($resultado))
			{        
                    $lista['nro_registro'] = $row['nro_registro'];
                    //se realizo el cambio de abajo porq en interfaz no podia realizar la comparacion directa de lo q recuperaba con lo de smarty en html
                   if(strcmp($row['disposicion'],"Usar con autorización")==0)
                    {  $cambio="Usar";
                       $lista['disposicion']=$cambio;
                    }
                   else
                   //apertura
					$lista['nro_revision'] = $row['nro_revision'];
					$lista['tipo'] = $row['tipo'];
					$lista['accion'] = $row['accion'];
                            $areaobservadas =$row['area_observada'];
                            $area_observada=$nocf->arreglo_areas($areaobservadas);
                            foreach($area_observada as $indice => $valor) 
				            {
                            if ($indice == 0)
                            {
                            $observada=$valor;//echo $observada;
                            $observa=explode('-', $observada);
                            $observadas=$observa[0];
                            }
                            else
                            {
                            $observada=$valor;
                            $observa=explode('-', $observada); 
                            $observadas=$observadas.",".$observa[0];
                            }
				            } 
                    $lista['area_observada'] = $observadas;
                    $respobservadas =$row['responsable_observada'];
                            $resp_observada=$nocf->arreglo_responsables($respobservadas);
                            foreach($resp_observada as $indice => $valor) 
				            {
                            if ($indice == 0)
                            {
                            $responsable=$valor;
                            $respon=explode('-', $responsable);
                            $responsables=$respon[0];
                            }
                            else
                            {
                            $responsable=$valor;
                            $respon=explode('-', $responsable); 
                            $responsables=$responsables.",".$respon[0];
                            }
				            } 
                    $lista['responsable_observada'] = $responsables;
					$lista['fec_apertura'] = $row['fec_apertura'];
					$lista['cierre'] = $row['cierre'];
					$lista['motivo'] = $row['motivo'];
					$lista['descripcion'] = $row['descripcion'];
                    $areainformadas =$row['area_informada'];
                            $area_informada=$nocf->arreglo_areas($areainformadas);
                            foreach($area_informada as $indice => $valor) 
				            {
                            if ($indice == 0)
                            {
                            $informada=$valor;
                            $informa=explode('-', $informada);
                            $informadas=$informa[0];
                            }
                            else
                            {
                            $informada=$valor;
                            $informa=explode('-', $informada); 
                            $informadas=$informadas.",".$informa[0];
                            }
				            } 
                    $lista['area_informada'] = $informadas;
                   //fin apertura
                    $lista['disposicion'] = $row['disposicion'];
					$lista['analisis_causa'] = $row['analisis_causa'];
					$lista['accion_inmediata'] = $row['accion_inmediata'];
					$lista['accion_otras'] = $row['accion_otras'];
					$lista['fec_analisis'] = $row['fec_analisis'];
					//fin analisis y accion
					$lista['responsable_cumplimiento'] = $row['responsable_cumplimiento'];
					$lista['fec_cumplimiento'] = $row['fec_cumplimiento'];
					$lista['fec_ver_cumplimiento'] = $row['fec_ver_cumplimiento'];
					$lista['fec_extension'] = $row['fec_extension'];
					$lista['fec_ver_extension'] = $row['fec_ver_extension'];
					$lista['efectividad'] = $row['efectividad'];
                    $lista['efectividad_ext'] = $row['efectividad_ext'];
					$lista['causa_extension'] = $row['causa_extension'];
				return $lista;
			} 
			else 
            {
				return false;
			}			
	}
}
    
/*Imprimir cierre */
function imprimir_cierre($id_doc)
{
	   $nocf = new NoConformidad_apertura();
		$con = new DBmanejador();
		if($con->conectar()==true)
		{
			$consulta= "select nro_registro,nro_revision,tipo,accion,area_observada,area_informada,responsable_observada,fec_apertura,cierre,motivo,descripcion,disposicion,analisis_causa,accion_inmediata,accion_otras,fec_analisis,concat(p.nombres,' ',p.apellidos) as responsable_cumplimiento,fec_cumplimiento,fec_ver_cumplimiento,fec_extension,fec_ver_extension,efectividad,efectividad_ext,causa_extension,accion_resultado,comunicacion_cliente,aplica_comunicacion,responsable_contacto,fec_contacto,no_conformidad.estado
			from no_conformidad,personal as p
			where p.personal_id=responsable_cumplimiento and nro_registro='".$id_doc."'
			";
			//echo $consulta; 
			$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());	
			if ($row = mysql_fetch_array($resultado))
			{
			        $lista['nro_registro'] = $row['nro_registro'];
                    //se realizo el cambio de abajo porq en interfaz no podia realizar la comparacion directa de lo q recuperaba con lo de smarty en html
                   if(strcmp($row['disposicion'],"Usar con autorización")==0)
                    {  $cambio="Usar";
                       $lista['disposicion']=$cambio;
                    }
                   else
                   //apertura
					$lista['nro_revision'] = $row['nro_revision'];
					$lista['tipo'] = $row['tipo'];
					$lista['accion'] = $row['accion'];
                            $areaobservadas =$row['area_observada'];
                            $area_observada=$nocf->arreglo_areas($areaobservadas);
                            foreach($area_observada as $indice => $valor) 
				            {
                            if ($indice == 0)
                            {
                            $observada=$valor;
                            $observa=explode('-', $observada);
                            $observadas=$observa[0];
                            }
                            else
                            {
                            $observada=$valor;
                            $observa=explode('-', $observada); 
                            $observadas=$observadas.",".$observa[0];
                            }
				            } 
                    $lista['area_observada'] = $observadas;
                    $respobservadas =$row['responsable_observada'];
                            $resp_observada=$nocf->arreglo_responsables($respobservadas);
                            foreach($resp_observada as $indice => $valor) 
				            {
                            if ($indice == 0)
                            {
                            $responsable=$valor;
                            $respon=explode('-', $responsable);
                            $responsables=$respon[0];
                            }
                            else
                            {
                            $responsable=$valor;
                            $respon=explode('-', $responsable); 
                            $responsables=$responsables.",".$respon[0];
                            }
				            } 
                    $lista['responsable_observada'] = $responsables;
					$lista['fec_apertura'] = $row['fec_apertura'];
					$lista['cierre'] = $row['cierre'];
					$lista['motivo'] = $row['motivo'];
					$lista['descripcion'] = $row['descripcion'];
                    $areainformadas =$row['area_informada'];
                            $area_informada=$nocf->arreglo_areas($areainformadas);
                            foreach($area_informada as $indice => $valor) 
				            {
                            if ($indice == 0)
                            {
                            $informada=$valor;
                            $informa=explode('-', $informada);
                            $informadas=$informa[0];
                            }
                            else
                            {
                            $informada=$valor;
                            $informa=explode('-', $informada); 
                            $informadas=$informadas.",".$informa[0];
                            }
				            } 
                    $lista['area_informada'] = $informadas;
                   //fin apertura
                    $lista['disposicion'] = $row['disposicion'];
					$lista['analisis_causa'] = $row['analisis_causa'];
					$lista['accion_inmediata'] = $row['accion_inmediata'];
					$lista['accion_otras'] = $row['accion_otras'];
					$lista['fec_analisis'] = $row['fec_analisis'];
					//fin analisis y accion
					$lista['responsable_cumplimiento'] = $row['responsable_cumplimiento'];
					$lista['fec_cumplimiento'] = $row['fec_cumplimiento'];
					$lista['fec_ver_cumplimiento'] = $row['fec_ver_cumplimiento'];
					$lista['fec_extension'] = $row['fec_extension'];
					$lista['fec_ver_extension'] = $row['fec_ver_extension'];
					$lista['efectividad'] = $row['efectividad'];
                    $lista['efectividad_ext'] = $row['efectividad_ext'];
					$lista['causa_extension'] = $row['causa_extension'];
                    //fin de revision
					$lista['accion_resultado'] = $row['accion_resultado'];
					$lista['comunicacion_cliente'] = $row['comunicacion_cliente'];
					$lista['aplica_comunicacion'] = $row['aplica_comunicacion'];
					$lista['responsable_contacto'] = $row['responsable_contacto'];
					$lista['fec_contacto'] = $row['fec_contacto'];
                    $lista['estado'] = $row['estado'];			
				return $lista;
			} 
			else 
            {
				return false;
			}			
	   }
}
    
/*Es el Buscador que aparce en la impresion de apertura*/
	function consultar_busqueda($cadena,$opcion) {
       $nocf = new NoConformidad_apertura();
		$con = new DBmanejador;
		if ($con->conectar() == true) 
        {
			if ($opcion == "num_doc") 
            {
				$consulta= "select nro_registro,tipo,area_observada,fec_plan_cierre,accion,motivo,fec_apertura,cierre,imprimir_ap,estado
                from no_conformidad as nc
                where estado=1 and nro_registro like '%" .$cadena. "%'
                order by nro_registro
			";
				//echo $consulta;
			} else 
            {	
				$consulta= "select nro_registro,tipo,area_observada,fec_plan_cierre,accion,motivo,fec_apertura,cierre,imprimir_ap,estado
                from no_conformidad as nc
                where estado=1 and tipo like '%" .$cadena. "%'
                order by nro_registro
				";
			}
			//echo $consulta;
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			if (!$resultado)
				return false;
			else {
				$cont = 0;
				while ($row = mysql_fetch_array($resultado)) {
				    $lista[$cont]['nro_registro'] = $row['nro_registro'];
					$lista[$cont]['tipo'] = $row['tipo'];
                    $areaobservadas =$row['area_observada'];
                            $area_observada=$nocf->arreglo_areas($areaobservadas);
                            foreach($area_observada as $indice => $valor) 
				            {
                            if ($indice == 0)
                            {
                            $observada=$valor;
                            $observa=explode('-', $observada);
                            $observadas=$observa[0];
                            }
                            else
                            {
                            $observada=$valor;
                            $observa=explode('-', $observada); 
                            $observadas=$observadas.",".$observa[0];
                            }
				            } 
                    $lista[$cont]['area_observada'] = $observadas;
                    $lista[$cont]['fec_plan_cierre'] = $row['fec_plan_cierre'];
					$lista[$cont]['accion'] = $row['accion'];
					$lista[$cont]['motivo'] = $row['motivo'];
					$lista[$cont]['fec_apertura'] = $row['fec_apertura'];
                    $lista[$cont]['cierre'] = $row['cierre'];
					$lista[$cont]['imprimir_ap'] = $row['imprimir_ap'];	
                    $lista[$cont]['estado'] = $row['estado'];
                    $cont++;
				}	
				return $lista;
			}
		}
	}
/*Es el buscador que aparece cuando queremos ingresar la accion*/
	function consultar_busqueda_analisis($cadena,$opcion) {
	    $nocf = new NoConformidad_apertura();
		$con = new DBmanejador();
		if ($con->conectar() == true) {
			if ($opcion == "num_doc") {
				$consulta= "
                select nro_registro,tipo,area_observada,fec_plan_cierre,disposicion,analisis_causa,accion_inmediata,accion_otras,fec_analisis,imprimir_an,estado
                from no_conformidad
                where estado=2 and nro_registro like '%" .$cadena. "%'
                order by nro_registro
			";
				//echo $consulta;
			} else {
				
					$consulta= "select nro_registro,tipo,area_observada,fec_plan_cierre,disposicion,analisis_causa,accion_inmediata,accion_otras,fec_analisis,imprimir_an,estado
                from no_conformidad
                where estado=2 and tipo like '%" .$cadena. "%'
                order by nro_registro
					";
						}
			//echo $consulta;
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				$cont = 0;
				while ($row = mysql_fetch_array($resultado)) {
					$lista[$cont]['nro_registro'] = $row['nro_registro'];
                    $lista[$cont]['tipo'] = $row['tipo'];
                    $areaobservadas =$row['area_observada'];
                            $area_observada=$nocf->arreglo_areas($areaobservadas);
                            foreach($area_observada as $indice => $valor) 
				            {
                            if ($indice == 0)
                            {
                            $observada=$valor;
                            $observa=explode('-', $observada);
                            $observadas=$observa[0];
                            }
                            else
                            {
                            $observada=$valor;
                            $observa=explode('-', $observada); 
                            $observadas=$observadas.",".$observa[0];
                            }
				            } 
                    $lista[$cont]['area_observada']= $observadas;
                    $lista[$cont]['fec_plan_cierre'] = $row['fec_plan_cierre'];
					$lista[$cont]['disposicion'] = $row['disposicion'];
					$lista[$cont]['analisis_causa'] = $row['analisis_causa'];
					$lista[$cont]['accion_inmediata'] = $row['accion_inmediata'];
					$lista[$cont]['accion_otras'] = $row['accion_otras'];
					$lista[$cont]['fec_analisis'] = $row['fec_analisis'];
					$lista[$cont]['imprimir_an'] = $row['imprimir_an'];		
                    $lista[$cont]['estado'] = $row['estado'];			
                    $cont++;
				}
				return $lista;
			}
		}
	}
	/*Busqueda de registrar revision*/
	function consultar_busqueda_revision($cadena,$opcion) {
	   $nocf = new NoConformidad_apertura();
		$con = new DBmanejador();
		if ($con->conectar() == true) {
			if ($opcion == "num_doc") {
				$consulta= "
                select nro_registro,tipo,area_observada,fec_plan_cierre,concat(p.nombres,' ',p.apellidos) as responsable_cumplimiento,fec_cumplimiento,fec_ver_cumplimiento,fec_extension,fec_ver_extension,efectividad,imprimir_re,nc.estado,DATEDIFF(fec_plan_cierre,CURRENT_DATE()) as dias
                from no_conformidad as nc, personal as p
                where p.personal_id=responsable_cumplimiento and nc.estado=3  and nro_registro like '%" .$cadena. "%'
                order by nro_registro
			";
				//echo $consulta;
			} else {
				
					$consulta= "select nro_registro,tipo,area_observada,fec_plan_cierre,concat(p.nombres,' ',p.apellidos) as responsable_cumplimiento,fec_cumplimiento,fec_ver_cumplimiento,fec_extension,fec_ver_extension,efectividad,imprimir_re,nc.estado,DATEDIFF(fec_plan_cierre,CURRENT_DATE()) as dias
                from no_conformidad as nc, personal as p
                where p.personal_id=responsable_cumplimiento and nc.estado=3  and tipo like '%" .$cadena. "%'
                order by nro_registro
					";
						}
			//echo $consulta;
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			if (!$resultado)
				return false;
			else {
				$cont = 0;
				while ($row = mysql_fetch_array($resultado)) {
					$lista[$cont]['nro_registro'] = $row['nro_registro'];
                    $lista[$cont]['tipo'] = $row['tipo'];
                            $areaobservadas =$row['area_observada'];
                            $area_observada=$nocf->arreglo_areas($areaobservadas);
                            foreach($area_observada as $indice => $valor) 
				            {
                            if ($indice == 0)
                            {
                            $observada=$valor;
                            $observa=explode('-', $observada);
                            $observadas=$observa[0];
                            }
                            else
                            {
                            $observada=$valor;
                            $observa=explode('-', $observada); 
                            $observadas=$observadas.",".$observa[0];
                            }
				            } 
                    $lista[$cont]['area_observada'] = $observadas;
                    $lista[$cont]['fec_plan_cierre'] = $row['fec_plan_cierre'];
					$lista[$cont]['responsable_cumplimiento'] = $row['responsable_cumplimiento'];
					$lista[$cont]['fec_cumplimiento'] = $row['fec_cumplimiento'];
                    $lista[$cont]['fec_ver_cumplimiento'] = $row['fec_ver_cumplimiento'];
					$lista[$cont]['fec_extension'] = $row['fec_extension'];
                    $lista[$cont]['fec_ver_extension'] = $row['fec_ver_extension'];
					$lista[$cont]['efectividad'] = $row['efectividad'];
					$lista[$cont]['imprimir_re'] = $row['imprimir_re'];
                    $lista[$cont]['estado'] = $row['estado']; 
                    $lista[$cont]['dias'] = $row['dias']; 		
					$cont++;
				}
				return $lista;
			}
		}
	}

    /*Busqueda de registrar cierre*/
	function consultar_busqueda_cerradas($cadena,$opcion) {
	   $nocf = new NoConformidad_apertura();
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			if ($opcion == "num_doc") {
				$consulta= "
                select nro_registro,tipo,area_observada,fec_plan_cierre,accion_resultado,comunicacion_cliente,responsable_contacto,fec_contacto,fec_cierre,imprimir_ci,estado
                from no_conformidad
                where estado=0 and nro_registro like '%" .$cadena. "%'
                order by nro_registro
			";
				//echo $consulta;
			} else {
				
					$consulta= "select nro_registro,tipo,area_observada,fec_plan_cierre,accion_resultado,comunicacion_cliente,responsable_contacto,fec_contacto,fec_cierre,imprimir_ci,estado
                from no_conformidad
                where estado=0 and tipo like '%" .$cadena. "%'
                order by nro_registro
					";
						}
			//echo $consulta;
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				$cont = 0;
				while ($row = mysql_fetch_array($resultado)) {
				$lista[$cont]['nro_registro'] = $row['nro_registro'];
                    $lista[$cont]['tipo'] = $row['tipo'];
                    $areaobservadas =$row['area_observada'];
                            $area_observada=$nocf->arreglo_areas($areaobservadas);
                            foreach($area_observada as $indice => $valor) 
				            {
                            if ($indice == 0)
                            {
                            $observada=$valor;
                            $observa=explode('-', $observada);
                            $observadas=$observa[0];
                            }
                            else
                            {
                            $observada=$valor;
                            $observa=explode('-', $observada); 
                            $observadas=$observadas.",".$observa[0];
                            }
				            } 
                    $lista[$cont]['area_observada'] = $observadas;
                    $lista[$cont]['fec_plan_cierre'] = $row['fec_plan_cierre'];
					$lista[$cont]['accion_resultado'] = $row['accion_resultado'];
					$lista[$cont]['comunicacion_cliente'] = $row['comunicacion_cliente'];
					$lista[$cont]['responsable_contacto'] = $row['responsable_contacto'];
					$lista[$cont]['fec_contacto'] = $row['fec_contacto'];
					$lista[$cont]['fec_cierre'] = $row['fec_cierre'];
					$lista[$cont]['imprimir_ci'] = $row['imprimir_ci'];
                    $lista[$cont]['estado'] = $row['estado'];		
					$cont++;
				}
				return $lista;
			}
		}
	}
    
    /*Modifica el estado de RG40 cuando gerencia aprueba*/
	function modificar_estado_rg40($nro_registro,$band){
		$con = new DBmanejador();
        if($band==4)//pendiente de apertura
        {
            if($con->conectar() == true){
			$consulta = "
			UPDATE no_conformidad
            SET estado= '1'
            WHERE nro_registro =".$nro_registro;
			$resultado = mysql_query($consulta) or die ('La consulta -modificar_observaciones- fall&oacute;: ' . mysql_error());
		    }
        }
        if($band==5)//pendiente de cierre
        {
            if($con->conectar() == true){
			$consulta = "
			UPDATE no_conformidad
            SET estado= '0'
            WHERE nro_registro =".$nro_registro;
			$resultado = mysql_query($consulta) or die ('La consulta -modificar_observaciones- fall&oacute;: ' . mysql_error());
		      }
        }
	}
	/*Modifica el estado de impresion cuando apertura-RG40 se imprime*/
	function modificar_print_apertura($nro_registro){
		$con = new DBmanejador();
        if($con->conectar() == true){
			$consulta = "
			UPDATE no_conformidad
            SET imprimir_ap = '1'
            WHERE nro_registro =".$nro_registro;
			$resultado = mysql_query($consulta) or die ('La consulta -modificar_observaciones- fall&oacute;: ' . mysql_error());
		}
	}

	/*Modifica el estado de impresion cuando analisis-RG40 se imprime*/
	function modificar_print_analisis($nro_registro){
		$con = new DBmanejador();
        if($con->conectar() == true){
			$consulta = "
			UPDATE	no_conformidad
			SET		imprimir_an = '1'
			WHERE	nro_registro = ".$nro_registro;
			$resultado = mysql_query($consulta) or die ('La consulta -modificar_estado_impresion_acc- fall&oacute;: ' . mysql_error());
		}
	}
	/*Modifica el estado de impresion cuando revision-RG40 se imprime*/
	function modificar_print_revision($nro_registro){
		$con = new DBmanejador();
        if($con->conectar() == true){
			$consulta = "
			UPDATE	no_conformidad
			SET		imprimir_re = '1'
			WHERE	nro_registro = ".$nro_registro;
			$resultado = mysql_query($consulta) or die ('La consulta -modificar_llenado_revs- fall&oacute;: ' . mysql_error());
		}
	}
	/*Modifica el estado de impresion cuando cierre-RG40 se imprime*/
	function modificar_print_cierre($nro_registro){
		$con = new DBmanejador();
        if($con->conectar() == true){
			 $consulta = "
			UPDATE	no_conformidad
			SET		imprimir_ci = '1'
			WHERE	nro_registro = ".$nro_registro;
			$resultado = mysql_query($consulta) or die ('La consulta -modificar_estado_impresion_rev- fall&oacute;: ' . mysql_error());
		}
	}
    
    /*Modifica los datos de la apertura del RG-40*/
	function modificar_apertura($nro_registro,$tipo,$accion,$area_observada,$area_informada,$cierre,$motivo,$descripcion,$responsable_observada,$responsable_informada){
    $nocf = new NoConformidad_apertura();
	$fec_plan=$nocf->fechaCierre($cierre);
		$con = new DBmanejador();
        if($con->conectar() == true){
			$consulta = "
			UPDATE no_conformidad
            SET tipo= '".$tipo."',
            accion='".$accion."',
            area_observada='".$area_observada."',
            area_informada='".$area_informada."',
            cierre='".$cierre."',
            fec_plan_cierre='".$fec_plan."',
            motivo='".$motivo."',
            descripcion='".$descripcion."',
            responsable_observada='".$responsable_observada."',
            responsable_informada='".$responsable_informada."'
            WHERE nro_registro =".$nro_registro;
			$resultado = mysql_query($consulta) or die ('La consulta -modificar_observaciones- fall&oacute;: ' . mysql_error());
	//	echo $consulta;
        }
	}
    /*Modifica los datos del analisis del RG-40*/
    function modificar_analisis($nro_registro,$disposicion,$analisis_causa,$accion_inmediata,$accion_otras){
		$con = new DBmanejador;
        if($con->conectar() == true){
			$consulta = "
			UPDATE no_conformidad
            SET disposicion= '".$disposicion."',
            analisis_causa='".$analisis_causa."',
            accion_inmediata='".$accion_inmediata."',
            accion_otras='".$accion_otras."'
            WHERE nro_registro =".$nro_registro;
			$resultado = mysql_query($consulta) or die ('La consulta -modificar_observaciones- fall&oacute;: ' . mysql_error());
		//echo $consulta;
        }
	}
    /*Modifica los datos del revision del RG-40*/
    function modificar_revision($nro_registro,$responsable_cumplimiento,$fec_cumplimiento,$fec_ver_cumplimiento,$fec_extension,$fec_ver_extension,$efectividad,$efectividad_ext,$causa_extension){        
         if($efectividad=="Efectivo")
       {
        $efectividad_ext="--";}
		$con = new DBmanejador();
        if($con->conectar() == true){
			$consulta = "
			UPDATE no_conformidad
            SET responsable_cumplimiento= '".$responsable_cumplimiento."',
            fec_cumplimiento='".$fec_cumplimiento."',
            fec_ver_cumplimiento='".$fec_ver_cumplimiento."',
            fec_extension='".$fec_extension."',
            fec_ver_extension='".$fec_ver_extension."',
            efectividad='".$efectividad."',
            efectividad_ext='".$efectividad_ext."',
            causa_extension='".$causa_extension."'
            WHERE nro_registro =".$nro_registro;
			$resultado = mysql_query($consulta) or die ('La consulta -modificar_observaciones- fall&oacute;: ' . mysql_error());
		//echo $consulta;
        }
	}
	
       /*Modifica los datos del analisis del RG-40*/
    function modificar_revisionS($nro_registro,$fec_cumplimiento,$fec_ver_cumplimiento,$fec_extension,$fec_ver_extension,$efectividad,$efectividad_ext,$causa_extension){        
         if($efectividad=="Efectivo")
       {$efectividad_ext="--";}
       
		$con = new DBmanejador;
        if($con->conectar() == true){
			$consulta = "
			UPDATE no_conformidad
            SET 
            fec_cumplimiento='".$fec_cumplimiento."',
            fec_ver_cumplimiento='".$fec_ver_cumplimiento."',
            fec_extension='".$fec_extension."',
            fec_ver_extension='".$fec_ver_extension."',
            efectividad='".$efectividad."',
            efectividad_ext='".$efectividad_ext."',
            causa_extension='".$causa_extension."'
            WHERE nro_registro =".$nro_registro;
			$resultado = mysql_query($consulta) or die ('La consulta -modificar_observaciones- fall&oacute;: ' . mysql_error());
		//echo $consulta;
        }
	}
	/*Ingresar datos de analisis y accion en un registro RG-40*/
	function listar_reg_aperturadas($estado)//puede ser 1=aperturada 4=pendiente de aprobacion de gerencia
	{
	    $nocf = new NoConformidad_apertura();
		$con = new DBmanejador();
		if($con->conectar()==true)
		{
			$consulta= "select nro_registro,tipo,area_observada,area_informada,fec_plan_cierre,accion,motivo,fec_apertura,cierre,imprimir_ap,estado
                        from no_conformidad as nc
                        where estado='" .$estado. "' 
                        order by nro_registro";
			$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());	
			if (!$resultado) return false;
			else
			{      $cont=0;
					while($row = mysql_fetch_array($resultado))
					{
					$lista[$cont]['nro_registro'] = $row['nro_registro'];
					$lista[$cont]['tipo'] = $row['tipo'];
                    $areaobservadas =$row['area_observada'];
                            $area_observada=$nocf->arreglo_areas($areaobservadas);
                            foreach($area_observada as $indice => $valor) 
				            {
                            if ($indice == 0)
                            {
                            $observada=$valor;
                            $observa=explode('-', $observada);
                            $observadas=$observa[0];
                            }
                            else
                            {
                            $observada=$valor;
                            $observa=explode('-', $observada); 
                            $observadas=$observadas.",".$observa[0];
                            }
				            } 
                    $lista[$cont]['area_observada'] = $observadas;
                    $areainformadas =$row['area_informada'];
                            $area_informada=$nocf->arreglo_areas($areainformadas);
                            foreach($area_informada as $indice => $valor) 
				            {
                            if ($indice == 0)
                            {
                            $informada=$valor;
                            $informa=explode('-', $informada);
                            $informadas=$informa[0];
                            }
                            else
                            {
                            $informada=$valor;
                            $informa=explode('-', $informada); 
                            $informadas=$informadas.",".$informa[0];
                            }
				            } 
                    $lista[$cont]['area_informada'] = $informadas;
                    $lista[$cont]['fec_plan_cierre'] = $row['fec_plan_cierre'];
					$lista[$cont]['accion'] = $row['accion'];
					$lista[$cont]['motivo'] = $row['motivo'];
					$lista[$cont]['fec_apertura'] = $row['fec_apertura'];
                    $lista[$cont]['cierre'] = $row['cierre'];
					$lista[$cont]['imprimir_ap'] = $row['imprimir_ap'];	
                    $lista[$cont]['estado'] = $row['estado'];			
					$cont++;
					}
				return $lista;
			}
	}
}
    
    /*Ingresar datos de revision en un registro RG-40    */
	function listar_reg_analisis()
	{
	   $nocf = new NoConformidad_apertura();
		$con = new DBmanejador();
		if($con->conectar()==true)
		{
			$consulta= "select nro_registro,tipo,area_observada,fec_plan_cierre,disposicion,analisis_causa,accion_inmediata,accion_otras,fec_analisis,imprimir_an,estado
                        from no_conformidad
                        where estado=2 
                        order by nro_registro";
			//echo $consulta; 
			$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());	
			if (!$resultado) return false;
			else
			{      $cont=0;
		
					while($row = mysql_fetch_array($resultado))
					{
					$lista[$cont]['nro_registro'] = $row['nro_registro'];
                    $lista[$cont]['tipo'] = $row['tipo'];
                    $areaobservadas =$row['area_observada'];
                            $area_observada=$nocf->arreglo_areas($areaobservadas);
                            foreach($area_observada as $indice => $valor) 
				            {
                            if ($indice == 0)
                            {
                            $observada=$valor;
                            $observa=explode('-', $observada);
                            $observadas=$observa[0];
                            }
                            else
                            {
                            $observada=$valor;
                            $observa=explode('-', $observada); 
                            $observadas=$observadas.",".$observa[0];
                            }
				            } 
                    $lista[$cont]['area_observada']= $observadas;
                    $lista[$cont]['fec_plan_cierre'] = $row['fec_plan_cierre'];
					$lista[$cont]['disposicion'] = $row['disposicion'];
					$lista[$cont]['analisis_causa'] = $row['analisis_causa'];
					$lista[$cont]['accion_inmediata'] = $row['accion_inmediata'];
					$lista[$cont]['accion_otras'] = $row['accion_otras'];
					$lista[$cont]['fec_analisis'] = $row['fec_analisis'];
					$lista[$cont]['imprimir_an'] = $row['imprimir_an'];		
                    $lista[$cont]['estado'] = $row['estado'];			
					$cont++;
					}
				return $lista;
			}
	}
    }
    
    
    /*Ingresar datos de cierre en un registro RG-40    */
	function listar_reg_revisiones()
	{
	    $nocf = new NoConformidad_apertura;
		$con = new DBmanejador;
		if($con->conectar()==true)
		{
            $consulta= "select nro_registro,tipo,area_observada,fec_plan_cierre,concat(p.nombres,' ',p.apellidos) as responsable_cumplimiento,fec_cumplimiento,fec_ver_cumplimiento,fec_extension,fec_ver_extension,efectividad,efectividad_ext,imprimir_re,nc.estado,DATEDIFF(fec_plan_cierre,CURRENT_DATE()) as dias
                        from no_conformidad as nc, personal as p
                        where p.personal_id=responsable_cumplimiento and nc.estado=3 
                        order by nro_registro";
			//echo $consulta; 
			$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());	
			if (!$resultado) return false;
			else
			{      $cont=0;
					while($row = mysql_fetch_array($resultado))
					{
                    $lista[$cont]['nro_registro'] = $row['nro_registro'];
                    $lista[$cont]['tipo'] = $row['tipo'];
                            $areaobservadas =$row['area_observada'];
                            $area_observada=$nocf->arreglo_areas($areaobservadas);
                            foreach($area_observada as $indice => $valor) 
				            {
                            if ($indice == 0)
                            {
                            $observada=$valor;
                            $observa=explode('-', $observada);
                            $observadas=$observa[0];
                            }
                            else
                            {
                            $observada=$valor;
                            $observa=explode('-', $observada); 
                            $observadas=$observadas.",".$observa[0];
                            }
				            } 
                    $lista[$cont]['area_observada'] = $observadas;
                    $lista[$cont]['fec_plan_cierre'] = $row['fec_plan_cierre'];
					$lista[$cont]['responsable_cumplimiento'] = $row['responsable_cumplimiento'];
					$lista[$cont]['fec_cumplimiento'] = $row['fec_cumplimiento'];
                    $lista[$cont]['fec_ver_cumplimiento'] = $row['fec_ver_cumplimiento'];
					$lista[$cont]['fec_extension'] = $row['fec_extension'];
                    $lista[$cont]['fec_ver_extension'] = $row['fec_ver_extension'];
					$lista[$cont]['efectividad'] = $row['efectividad'];
                    $lista[$cont]['efectividad_ext'] = $row['efectividad_ext'];
					$lista[$cont]['imprimir_re'] = $row['imprimir_re'];
                    $lista[$cont]['estado'] = $row['estado']; 
                    $lista[$cont]['dias'] = $row['dias'];		
					$cont++;
					}
				return $lista;
			}
	}
    }
    
    /*Cierre del registro RG-40    */
	function listar_reg_cerradas($estado)//$estado=0 cierre aprobado por gerencia,$estado=5 cierre no aprobado por gerencia
	{
	   $nocf = new NoConformidad_apertura();
	   $con = new DBmanejador();
		if($con->conectar()==true)
		{
			$consulta= "select nro_registro,tipo,area_observada,fec_plan_cierre,accion_resultado,comunicacion_cliente,responsable_contacto,fec_contacto,fec_cierre,imprimir_ci,estado
                        from no_conformidad
                        where estado='" .$estado. "' 
                        order by nro_registro";
			//echo $consulta; 
			$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());	
			if (!$resultado) return false;
			else
			{      $cont=0;
		
					while($row = mysql_fetch_array($resultado))
					{
					$lista[$cont]['nro_registro'] = $row['nro_registro'];
                    $lista[$cont]['tipo'] = $row['tipo'];
                    $areaobservadas =$row['area_observada'];
                            $area_observada=$nocf->arreglo_areas($areaobservadas);
                            foreach($area_observada as $indice => $valor) 
				            {
                            if ($indice == 0)
                            {
                            $observada=$valor;
                            $observa=explode('-', $observada);
                            $observadas=$observa[0];
                            }
                            else
                            {
                            $observada=$valor;
                            $observa=explode('-', $observada); 
                            $observadas=$observadas.",".$observa[0];
                            }
				            } 
                    $lista[$cont]['area_observada'] = $observadas;
                    $lista[$cont]['fec_plan_cierre'] = $row['fec_plan_cierre'];
					$lista[$cont]['accion_resultado'] = $row['accion_resultado'];
					$lista[$cont]['comunicacion_cliente'] = $row['comunicacion_cliente'];
					$lista[$cont]['responsable_contacto'] = $row['responsable_contacto'];
					$lista[$cont]['fec_contacto'] = $row['fec_contacto'];
					$lista[$cont]['fec_cierre'] = $row['fec_cierre'];
					$lista[$cont]['imprimir_ci'] = $row['imprimir_ci'];
                    $lista[$cont]['estado'] = $row['estado'];		
					$cont++;
					}
				return $lista;
			}	
	}
}
    
/*Este buscador permite recuperar el registro de aperturado para ingresar el analisis y accion del RG-40*/
	function recuperar_apertura($nro_registro) {
		$nocf = new NoConformidad_apertura;
        $con = new DBmanejador;
		if ($con->conectar() == true) {
			
				$consulta= "select nro_registro,nro_revision,tipo,accion,area_observada,area_informada,cierre,motivo,descripcion,fec_apertura
                            from no_conformidad
                            where estado=1 and nro_registro ='".$nro_registro."'
			";
				//echo $consulta;
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				$cont = 0;
				while ($row = mysql_fetch_array($resultado)) {
				    $lista['nro_registro'] = $row['nro_registro'];
					$lista['nro_revision'] = $row['nro_revision'];
					$lista['tipo'] = $row['tipo'];
					$lista['accion'] = $row['accion'];
                    $areaobservadas =$row['area_observada'];
                    $area_observada=$nocf->arreglo_areas($areaobservadas);
                   foreach($area_observada as $indice => $valor) 
				  {
                  if ($indice == 0)
                  {
                     $observada=$valor;
                     $observa=explode('-', $observada);
                     $observadas=$observa[0];
                     
                  }
                  else
                  {
                     $observada=$valor;
                     $observa=explode('-', $observada); 
                     $observadas=$observadas.",".$observa[0];
                  }
				 } 
                    $lista['area_observada'] = $observadas;
                    $areainformadas =$row['area_informada'];
                            $area_informada=$nocf->arreglo_areas($areainformadas);
                            foreach($area_informada as $indice => $valor) 
				            {
                            if ($indice == 0)
                            {
                            $informada=$valor;
                            $informa=explode('-', $informada);
                            $informadas=$informa[0];
                            }
                            else
                            {
                            $informada=$valor;
                            $informa=explode('-', $informada); 
                            $informadas=$informadas.",".$informa[0];
                            }
				            } 
                    $lista['area_informada'] = $informadas;
					$lista['cierre'] = $row['cierre'];
					$lista['motivo'] = $row['motivo'];
                    $lista['descripcion'] = $row['descripcion'];
                    $lista['fec_apertura'] = $row['fec_apertura'];
                    
				}
				return $lista;
			}
		}
	}
    
/*Este buscador permite recuperar el registro de aperturado para ingresar el analisis y accion del RG-40*/
	function recuperar_analisis($nro_registro) {
	   $nocf = new NoConformidad_apertura;
		$con = new DBmanejador;
		if ($con->conectar() == true) {
			
			$consulta= "select nro_registro,nro_revision,tipo,accion,area_observada,area_informada,cierre,fec_apertura,motivo,descripcion,disposicion,analisis_causa,accion_inmediata,accion_otras,fec_plan_cierre,DATEDIFF(fec_plan_cierre,CURRENT_DATE()) as dias
                            from no_conformidad
                            where estado=2 and nro_registro ='".$nro_registro."'
			";
				//echo $consulta;
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			if (!$resultado)
				return false;
			else {
				$cont = 0;
				while ($row = mysql_fetch_array($resultado)) {
				    $lista['nro_registro'] = $row['nro_registro'];
					$lista['nro_revision'] = $row['nro_revision'];
					$lista['tipo'] = $row['tipo'];
					$lista['accion'] = $row['accion'];
					$areaobservadas =$row['area_observada'];
                    $area_observada=$nocf->arreglo_areas($areaobservadas);
                   foreach($area_observada as $indice => $valor) 
				  {
                  if ($indice == 0)
                  {
                     $observada=$valor;
                     $observa=explode('-', $observada);
                     $observadas=$observa[0];
                     
                  }
                  else
                  {
                     $observada=$valor;
                     $observa=explode('-', $observada); 
                     $observadas=$observadas.",".$observa[0];
                  }
				 } 
                    $lista['area_observada'] = $observadas;
                    $areainformadas =$row['area_informada'];
                            $area_informada=$nocf->arreglo_areas($areainformadas);
                            foreach($area_informada as $indice => $valor) 
				            {
                            if ($indice == 0)
                            {
                            $informada=$valor;
                            $informa=explode('-', $informada);
                            $informadas=$informa[0];
                            }
                            else
                            {
                            $informada=$valor;
                            $informa=explode('-', $informada); 
                            $informadas=$informadas.",".$informa[0];
                            }
				            } 
                    $lista['area_informada'] = $informadas;
					$lista['cierre'] = $row['cierre'];
					$lista['fec_apertura'] = $row['fec_apertura'];
					$lista['motivo'] = $row['motivo'];
                    $lista['descripcion'] = $row['descripcion'];
                    $lista['disposicion'] = $row['disposicion'];
					$lista['analisis_causa'] = $row['analisis_causa'];
					$lista['accion_inmediata'] = $row['accion_inmediata'];
					$lista['accion_otras'] = $row['accion_otras'];
                    $lista['fec_analisis'] = $row['fec_analisis'];
                    
                    $lista['dias'] = $row['dias'];
				}
				return $lista;
			}
		}
	}
    
    /*Este buscador permite recuperar el registro de revision para ingresar el cierre del RG-40*/
	function recuperar_revision($nro_registro){
	   $nocf = new NoConformidad_apertura();
		$con = new DBmanejador();
		if ($con->conectar() == true) {
        $consulta= "select nro_registro,nro_revision,tipo,accion,area_observada,area_informada,cierre,fec_apertura,motivo,descripcion,disposicion,analisis_causa,accion_inmediata,accion_otras,concat(nombres,' ',apellidos) as responsable_cumplimiento,fec_cumplimiento,fec_ver_cumplimiento,fec_extension,fec_ver_extension,efectividad,causa_extension,efectividad,fec_revision
                    from personal as p,no_conformidad as nc
                    where responsable_cumplimiento=p.personal_id and nc.estado=3 and nro_registro ='".$nro_registro."'
			";
			//	echo $consulta;
			$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				$cont = 0;
				while ($row = mysql_fetch_array($resultado)) {
				    $lista['nro_registro'] = $row['nro_registro'];
                    
                    $lista['nro_revision'] = $row['nro_revision'];
					$lista['tipo'] = $row['tipo'];
					$lista['accion'] = $row['accion'];
					$areaobservadas =$row['area_observada'];
                    $area_observada=$nocf->arreglo_areas($areaobservadas);
                   foreach($area_observada as $indice => $valor) 
				  {
                  if ($indice == 0)
                  {
                     $observada=$valor;
                     $observa=explode('-', $observada);
                     $observadas=$observa[0];
                  }
                  else
                  {
                     $observada=$valor;
                     $observa=explode('-', $observada); 
                     $observadas=$observadas.",".$observa[0];
                  }
				 } 
                    $lista['area_observada'] = $observadas;
                    $areainformadas =$row['area_informada'];
                            $area_informada=$nocf->arreglo_areas($areainformadas);
                            foreach($area_informada as $indice => $valor) 
				            {
                            if ($indice == 0)
                            {
                            $informada=$valor;
                            $informa=explode('-', $informada);
                            $informadas=$informa[0];
                            }
                            else
                            {
                            $informada=$valor;
                            $informa=explode('-', $informada); 
                            $informadas=$informadas.",".$informa[0];
                            }
				            } 
                    $lista['area_informada'] = $informadas;
					$lista['cierre'] = $row['cierre'];
					$lista['fec_apertura'] = $row['fec_apertura'];
					$lista['motivo'] = $row['motivo'];
                    $lista['descripcion'] = $row['descripcion'];
                    $lista['disposicion'] = $row['disposicion'];
					$lista['analisis_causa'] = $row['analisis_causa'];
					$lista['accion_inmediata'] = $row['accion_inmediata'];
					$lista['accion_otras'] = $row['accion_otras'];
                    $lista['fec_analisis'] = $row['fec_analisis'];
					$lista['responsable_cumplimiento'] = $row['responsable_cumplimiento'];
					$lista['fec_cumplimiento'] = $row['fec_cumplimiento'];
					$lista['fec_ver_cumplimiento'] = $row['fec_ver_cumplimiento'];
					$lista['fec_extension'] = $row['fec_extension'];
					$lista['fec_ver_extension'] = $row['fec_ver_extension'];
                    $lista['causa_extension'] = $row['causa_extension'];
                    $lista['efectividad'] = $row['efectividad'];
					$lista['fec_revision'] = $row['fec_revision'];
				}
				return $lista;
			}
		}
	}
    
    /*Devuelve el id del ultimo de registro de conformidades */
	function ultima_aperturada()
	{
		$con = new DBmanejador;
		if($con->conectar()==true)
		{
			$consulta= "SELECT MAX(nro_registro) as nro_registro from no_conformidad";
		//	echo $consulta; 	 
			$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());	
			if (!$resultado) return false;
			else
			{    while($row = mysql_fetch_array($resultado))
					{
					$lista['nro_registro'] = $row['nro_registro'];	
					}
				return $lista;
			}
        }
    }
    
    function totalAperturas($estado)
    {
        $con = new DBmanejador;
		if($con->conectar()==true)
		{
			$sql= "SELECT COUNT(nro_registro) as total from no_conformidad where estado='".$estado."'";
			//echo $sql;
            $resultado=mysql_query($sql) or die('La consulta fall&oacute;: ' . mysql_error());	
			if (!$resultado) return false;
			else
			{    while($row = mysql_fetch_array($resultado))
				 {
					$lista['total'] = $row['total'];	
				 }
				return $lista;
			}      
        }
    }

  /*PERMITE OBTENER EL REGISTRO A MODIFICAR APERTURA*/
	function consulta_aperturada($nro_registro)
	{
    $con = new DBmanejador();
			 if($con->conectar()==true)
			 {
				$consulta= 'SELECT * FROM no_conformidad where nro_registro='.$nro_registro;
				$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
	
	
				if (!$resultado) return false;
				else
				{     
                         if ($row = mysql_fetch_array($resultado)) {
							$respuesta["nro_registro"]= $row['nro_registro'];
                            $respuesta["nro_revision"]= $row['nro_revision'];
							$respuesta["tipo"]= $row['tipo'];
                            $respuesta["accion"]= $row['accion'];
                            $respuesta["area_observada"]= $row['area_observada'];  
                            $respuesta["area_informada"]= $row['area_informada'];
                            $respuesta["cierre"]= $row['cierre'];
                            $respuesta["fec_plan_cierre"]= $row['fec_plan_cierre'];
                            $respuesta["motivo"]= $row['motivo'];
                            $respuesta["descripcion"]= $row['descripcion'];
                            $respuesta["responsable_observada"]= $row['responsable_observada'];
                            $respuesta["responsable_informada"]= $row['responsable_informada'];
                            $respuesta["fec_apertura"]= $row['fec_apertura'];
						}
					return $respuesta;
				}
			 }
    }
  
    /*PERMITE OBTENER EL REGISTRO A MODIFICAR  ANALISIS  */
  	function consulta_analisis($nro_registro)
	{
    $con = new DBmanejador();
			 if($con->conectar()==true)
			 {
				$consulta= 'SELECT * FROM no_conformidad where nro_registro='.$nro_registro;
				$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
	
				if (!$resultado) return false;
				else
				{     
                         if ($row = mysql_fetch_array($resultado)) {
							$respuesta["nro_registro"]= $row['nro_registro'];
                            $respuesta["disposicion"]= $row['disposicion'];
							$respuesta["analisis_causa"]= $row['analisis_causa'];
                            $respuesta["accion_inmediata"]= $row['accion_inmediata'];
                            $respuesta["accion_otras"]= $row['accion_otras'];
                            $respuesta["fec_analisis"]= $row['fec_analisis'];
						}
					return $respuesta;
				}
			 }
    }
    
    /*PERMITE OBTENER EL REGISTRO A MODIFICAR  REVISION  */
  	function consulta_revision($nro_registro)
	{
    $con = new DBmanejador();
			 if($con->conectar()==true)
			 {
				$consulta= 'SELECT nro_registro,CONCAT(p.nombres," ",p.apellidos) as responsable_cumplimiento,fec_cumplimiento,fec_ver_cumplimiento,fec_extension,fec_ver_extension,efectividad,efectividad_ext,causa_extension FROM no_conformidad as nc,personal as p where personal_id=responsable_cumplimiento and nro_registro='.$nro_registro;
				$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
	
				if (!$resultado) return false;
				else
				{     
                         if ($row = mysql_fetch_array($resultado)) 
                         {
							$respuesta["nro_registro"]= $row['nro_registro'];
                            $respuesta["responsable_cumplimiento"]= $row['responsable_cumplimiento'];
							$respuesta["fec_cumplimiento"]= $row['fec_cumplimiento'];
                            $respuesta["fec_ver_cumplimiento"]= $row['fec_ver_cumplimiento'];
                            $respuesta["fec_extension"]= $row['fec_extension'];
                            $respuesta["fec_ver_extension"]= $row['fec_ver_extension'];
                            $respuesta["efectividad"]= $row['efectividad'];
                            $respuesta["efectividad_ext"]= $row['efectividad_ext'];
                            $respuesta["causa_extension"]= $row['causa_extension'];
						}
					return $respuesta;
				}
			 }
    }
    //eliminamos NO CONFORMIDADES
	function eliminar_apertura($nro_registro){ 
	   $nocf = new NoConformidad_apertura();
		$con = new DBmanejador();
		if($con->conectar() == true){
			$consulta = "
			DELETE
			FROM	no_conformidad
			WHERE	nro_registro = ".$nro_registro;
            $resultado = mysql_query($consulta) or die ('La consulta -eliminar asignar detalle- fall&oacute;: ' . mysql_error());
            
            if (!$resultado)
                 return false;//si no es vacio
			else 
                return true;//si es vacio
		}
         
	}
    
     //eliminamos NO CONFORMIDADES pero se debe actualizar autoincrement
	function actualizando_auto(){ 
	   $nocf = new NoConformidad_apertura();
       $con = new DBmanejador();
	   $valorultimo=$nocf->ultima_aperturada();
       $valor=$valorultimo[nro_registro]+1;
		if($con->conectar() == true){
			$consulta = "
            ALTER TABLE no_conformidad AUTO_INCREMENT= ".$valor;
            $resultado = mysql_query($consulta) or die ('La consulta -eliminar no conformidad- fall&oacute;: ' . mysql_error());
            
            if (!$resultado) return false;
				 else return true;
		}
	}
    
    function buscar_noconformidad($fecha_inicio,$fecha_fin,$estado,$accion,$tiporeporte,$tipo,$cierre){
        $nocf = new NoConformidad_apertura();
		$con = new DBmanejador();
		if($con->conectar() == true){
			if ($tiporeporte == 1)
            {
                if($estado==1)//apertura
                {
                $consulta = "
				SELECT nro_registro,tipo,area_observada,area_informada,fec_plan_cierre,accion,motivo,fec_apertura,cierre,imprimir_ap,estado FROM `no_conformidad` WHERE fec_apertura>='".$fecha_inicio."'
                AND fec_apertura<='".$fecha_fin."'
                AND estado='".$estado."'
                AND accion='".$accion."'
                ORDER BY fec_apertura ASC
				";
                //echo $consulta; 
                }
                if($estado==2)//analisis
                {
                  $consulta = "
				SELECT * FROM `no_conformidad` WHERE	fec_analisis>='".$fecha_inicio."'
                AND fec_analisis<='".$fecha_fin."'
                AND estado='".$estado."'
                AND accion='".$accion."'
                ORDER BY fec_analisis ASC
				";  
                //echo $consulta;
                }
                if($estado==3)//revision
                {
                  $consulta = "
				SELECT nro_registro,tipo,area_observada,fec_plan_cierre,concat(p.nombres,' ',p.apellidos) as responsable_cumplimiento,fec_cumplimiento,fec_ver_cumplimiento,fec_extension,fec_ver_extension,efectividad,efectividad_ext,imprimir_re,nc.estado,DATEDIFF(fec_plan_cierre,CURRENT_DATE()) as dias 
                FROM no_conformidad as nc, personal as p WHERE p.personal_id=responsable_cumplimiento and fec_revision>='".$fecha_inicio."'
                AND fec_revision<='".$fecha_fin."'
                AND nc.estado='".$estado."'
                AND accion='".$accion."'
                ORDER BY fec_revision ASC
				";  
                //echo $consulta;
                }
                if($estado==0)//cierre
                {
                  $consulta = "
				SELECT * FROM `no_conformidad` WHERE	fec_cierre>='".$fecha_inicio."'
                AND fec_cierre<='".$fecha_fin."'
                AND estado='".$estado."'
                AND accion='".$accion."'
                ORDER BY fec_cierre ASC
				";  
                //echo $consulta;
                }
            }
			else
            {
                if($cierre=="Todos")
                {if($estado==1)//apertura
                {
                $consulta = "
				SELECT * FROM `no_conformidad` WHERE	fec_apertura>='".$fecha_inicio."'
                AND fec_apertura<='".$fecha_fin."'
                AND estado='".$estado."'
                AND accion='".$accion."'
                AND tipo='".$tipo."'
                ORDER BY tipo ASC
				";
                //echo $consulta;
                }
                if($estado==2)//analisis
                {
                  $consulta = "
				SELECT * FROM `no_conformidad` WHERE	fec_analisis>='".$fecha_inicio."'
                AND fec_analisis<='".$fecha_fin."'
                AND estado='".$estado."'
                AND accion='".$accion."'
                AND tipo='".$tipo."'
                ORDER BY fec_analisis ASC
				";  
                //echo $consulta;
                }
                if($estado==3)//revision
                {
                  $consulta = "
                SELECT nro_registro,tipo,area_observada,fec_plan_cierre,concat(p.nombres,' ',p.apellidos) as responsable_cumplimiento,fec_cumplimiento,fec_ver_cumplimiento,fec_extension,fec_ver_extension,efectividad,efectividad_ext,imprimir_re,nc.estado,DATEDIFF(fec_plan_cierre,CURRENT_DATE()) as dias 
                FROM no_conformidad as nc, personal as p WHERE p.personal_id=responsable_cumplimiento and	fec_revision>='".$fecha_inicio."'
                AND fec_revision<='".$fecha_fin."'
                AND estado='".$estado."'
                AND accion='".$accion."'
                AND tipo='".$tipo."'
                ORDER BY fec_revision ASC
				";  
                //echo $consulta;
                }
                if($estado==0)//cierre
                {
                  $consulta = "
				SELECT * FROM `no_conformidad` WHERE	fec_cierre>='".$fecha_inicio."'
                AND fec_cierre<='".$fecha_fin."'
                AND estado='".$estado."'
                AND accion='".$accion."'
                AND tipo='".$tipo."'
                ORDER BY fec_cierre ASC
				"; 
                //echo $consulta; 
                }
                }
                else
                {
                 if($estado==1)//apertura
                {
                $consulta = "
				SELECT * FROM `no_conformidad` WHERE	fec_apertura>='".$fecha_inicio."'
                AND fec_apertura<='".$fecha_fin."'
                 AND estado='".$estado."'
                AND accion='".$accion."'
                AND tipo='".$tipo."'
                AND cierre='".$cierre."'
                ORDER BY fec_apertura ASC
				";
                //echo $consulta;
                }
                if($estado==2)//analisis
                {
                $consulta = "
				SELECT * FROM `no_conformidad` WHERE	fec_analisis>='".$fecha_inicio."'
                AND fec_analisis<='".$fecha_fin."'
                 AND estado='".$estado."'
                AND accion='".$accion."'
                AND tipo='".$tipo."'
                AND cierre='".$cierre."'
                ORDER BY fec_analisis ASC
				";  
                //echo $consulta;
                }
                if($estado==3)//revision
                {
                $consulta = "
                SELECT nro_registro,tipo,area_observada,fec_plan_cierre,concat(p.nombres,' ',p.apellidos) as responsable_cumplimiento,fec_cumplimiento,fec_ver_cumplimiento,fec_extension,fec_ver_extension,efectividad,efectividad_ext,imprimir_re,nc.estado,DATEDIFF(fec_plan_cierre,CURRENT_DATE()) as dias 
                FROM no_conformidad as nc, personal as p WHERE p.personal_id=responsable_cumplimiento and	fec_revision>='".$fecha_inicio."'
                AND fec_revision<='".$fecha_fin."'
                AND estado='".$estado."'
                AND accion='".$accion."'
                AND tipo='".$tipo."'
                AND cierre='".$cierre."'
                ORDER BY fec_revision ASC
				"; 
                //echo $consulta; 
                }
                if($estado==0)//cierre
                {
                $consulta = "
				SELECT * FROM `no_conformidad` WHERE	fec_cierre>='".$fecha_inicio."'
                AND fec_cierre<='".$fecha_fin."'
                AND estado='".$estado."'
                AND accion='".$accion."'
                AND tipo='".$tipo."'
                AND cierre='".$cierre."'
                ORDER BY fec_cierre ASC
				";  
                //echo $consulta;
                }
                }
            }
			$resultado = mysql_query($consulta) or die ('La consulta -estado productos- fall&oacute;: ' . mysql_error());
			if (!$resultado)
				return false;
			else{
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
                    $respuesta[$contador]['nro_registro'] = $row['nro_registro'];
					$respuesta[$contador]['tipo'] = $row['tipo'];
                    $areaobservadas =$row['area_observada'];
                            $area_observada=$nocf->arreglo_areas($areaobservadas);
                            foreach($area_observada as $indice => $valor) 
				            {
                            if ($indice == 0)
                            {
                            $observada=$valor;
                            $observa=explode('-', $observada);
                            $observadas=$observa[0];
                            }
                            else
                            {
                            $observada=$valor;
                            $observa=explode('-', $observada); 
                            $observadas=$observadas.",".$observa[0];
                            }
				            } 
                    $respuesta[$contador]['area_observada'] = $observadas;
                    $areainformadas =$row['area_informada'];
                            $area_informada=$nocf->arreglo_areas($areainformadas);
                            foreach($area_informada as $indice => $valor) 
				            {
                            if ($indice == 0)
                            {
                            $informada=$valor;
                            $informa=explode('-', $informada);
                            $informadas=$informa[0];
                            }
                            else
                            {
                            $informada=$valor;
                            $informa=explode('-', $informada); 
                            $informadas=$informadas.",".$informa[0];
                            }
				            } 
                    $respuesta[$contador]['area_informada'] = $informadas;
                    $respuesta[$contador]['fec_plan_cierre'] = $row['fec_plan_cierre'];
					$respuesta[$contador]['accion'] = $row['accion'];
					$respuesta[$contador]['motivo'] = $row['motivo'];
					$respuesta[$contador]['fec_apertura'] = $row['fec_apertura'];
                    $respuesta[$contador]['cierre'] = $row['cierre'];	
                    //estado=2
					$respuesta[$contador]['disposicion'] = $row['disposicion'];
					$respuesta[$contador]['analisis_causa'] = $row['analisis_causa'];
					$respuesta[$contador]['accion_inmediata'] = $row['accion_inmediata'];
					$respuesta[$contador]['accion_otras'] = $row['accion_otras'];
					$respuesta[$contador]['fec_analisis'] = $row['fec_analisis'];
                    //estado=3
                    $respuesta[$contador]['responsable_cumplimiento'] = $row['responsable_cumplimiento'];
					$respuesta[$contador]['fec_cumplimiento'] = $row['fec_cumplimiento'];
                    $respuesta[$contador]['fec_ver_cumplimiento'] = $row['fec_ver_cumplimiento'];
					$respuesta[$contador]['fec_extension'] = $row['fec_extension'];
                    $respuesta[$contador]['fec_ver_extension'] = $row['fec_ver_extension'];
					$respuesta[$contador]['efectividad'] = $row['efectividad'];
                    $respuesta[$contador]['efectividad_ext'] = $row['efectividad_ext'];
					$respuesta[$contador]['imprimir_re'] = $row['imprimir_re'];
                    $respuesta[$contador]['dias'] = $row['dias'];
                    //estado=0
                    $respuesta[$contador]['accion_resultado'] = $row['accion_resultado'];
					$respuesta[$contador]['comunicacion_cliente'] = $row['comunicacion_cliente'];
					$respuesta[$contador]['responsable_contacto'] = $row['responsable_contacto'];
					$respuesta[$contador]['fec_contacto'] = $row['fec_contacto'];
					$respuesta[$contador]['fec_cierre'] = $row['fec_cierre'];
					$respuesta[$contador]['imprimir_ci'] = $row['imprimir_ci'];

                    $respuesta[$contador]['estado'] = $row['estado'];
					$contador = $contador + 1;
				}
				return $respuesta;
			}
		}
	}

 //Metodo para recuperar las areas con los nombres no id posteriormente comparar el area ingresado
 function consulta_noconformidad($id_perfil,$band)
{
    $nocf = new NoConformidad_apertura();
    $con = new DBmanejador();
		if($con->conectar()==true)
		{
if($band==1)//band=1
{
			$consulta= "
            select nro_registro,tipo,accion,area_observada,area_informada,motivo,fec_plan_cierre,fec_apertura,fec_analisis,fec_revision,fec_cierre,estado,DATEDIFF(fec_plan_cierre,CURRENT_DATE()) as dias
            from no_conformidad as nc
            where estado<>0
            order by fec_plan_cierre,estado
            ";
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());	
			if (!$resultado) return false;
			else
			{      $cont=0;
					while($row = mysql_fetch_array($resultado))
					{
                    $areaobservadas =$row['area_observada'];
                    
                    $area_observada=$nocf->encontrar_arreglo_areas($areaobservadas,$id_perfil);
                    
                    $areainformadas =$row['area_informada'];
                    $area_informada=$nocf->encontrar_arreglo_areas($areainformadas,$id_perfil);
                    if($area_observada==true or $area_informada==true)
                    { 
                       $lista[$cont]['nro_registro'] = $row['nro_registro'];
					   $lista[$cont]['tipo'] = $row['tipo'];
					   $lista[$cont]['accion'] = $row['accion'];
                       $areaobservadas =$row['area_observada'];
                       $area_observada=$nocf->arreglo_areas($areaobservadas);
                        foreach($area_observada as $indice => $valor) 
				            {
                            if ($indice == 0)
                            {
                            $observada=$valor;
                            $observa=explode('-', $observada);
                            $observadas=$observa[0];
                            }
                            else
                            {
                            $observada=$valor;
                            $observa=explode('-', $observada); 
                            $observadas=$observadas.",".$observa[0];
                            }
				            } 
                       $lista[$cont]['area_observada'] = $observadas;
                       $areainformadas =$row['area_informada'];
                            $area_informada=$nocf->arreglo_areas($areainformadas);
                            foreach($area_informada as $indice => $valor) 
				            {
                            if ($indice == 0)
                            {
                            $informada=$valor;
                            $informa=explode('-', $informada);
                            $informadas=$informa[0];
                            }
                            else
                            {
                            $informada=$valor;
                            $informa=explode('-', $informada); 
                            $informadas=$informadas.",".$informa[0];
                            }
				            } 
                       $lista[$cont]['area_informada'] = $informadas;
					   $lista[$cont]['motivo'] = $row['motivo'];	
                       $lista[$cont]['fec_apertura'] = $row['fec_apertura'];
					   $lista[$cont]['fec_analisis'] = $row['fec_analisis'];					   
					   $lista[$cont]['fec_revision'] = $row['fec_revision'];
                       $lista[$cont]['fec_plan_cierre'] = $row['fec_plan_cierre'];
					   $lista[$cont]['fec_cierre'] = $row['fec_cierre'];
                       $lista[$cont]['estado'] = $row['estado'];
                       $lista[$cont]['dias'] = $row['dias'];
                       $cont++;
                    }
					}
				return $lista;
			}
}
else//band=0
{
    $consulta= "
            select nro_registro,tipo,accion,area_observada,area_informada,motivo,fec_plan_cierre,fec_apertura,fec_analisis,fec_revision,fec_cierre,estado,DATEDIFF(fec_plan_cierre,CURRENT_DATE()) as dias
            from no_conformidad as nc
            where estado=0
            order by fec_plan_cierre,estado
            ";
    $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());	
			if (!$resultado) return false;
			else
			{      $cont=0;
					while($row = mysql_fetch_array($resultado))
					{
                    $areaobservadas =$row['area_observada'];
                    $area_observada=$nocf->encontrar_arreglo_areas($areaobservadas,$id_perfil);
      
                    $areainformadas =$row['area_informada'];
                    $area_informada=$nocf->encontrar_arreglo_areas($areainformadas,$id_perfil);
                    if($area_observada==true or $area_informada==true)
                    {  
                       $listas[$cont]['nro_registro'] = $row['nro_registro'];
					   $listas[$cont]['tipo'] = $row['tipo'];
					   $listas[$cont]['accion'] = $row['accion'];
                       $areaobservadas =$row['area_observada'];
                       $area_observada=$nocf->arreglo_areas($areaobservadas);
                        foreach($area_observada as $indice => $valor) 
				            {
                            if ($indice == 0)
                            {
                            $observada=$valor;
                            $observa=explode('-', $observada);
                            $observadas=$observa[0];
                            }
                            else
                            {
                            $observada=$valor;
                            $observa=explode('-', $observada); 
                            $observadas=$observadas.",".$observa[0];
                            }
				            } 
                       $listas[$cont]['area_observada'] = $observadas;
                       $areainformadas =$row['area_informada'];
                            $area_informada=$nocf->arreglo_areas($areainformadas);
                            foreach($area_informada as $indice => $valor) 
				            {
                            if ($indice == 0)
                            {
                            $informada=$valor;
                            $informa=explode('-', $informada);
                            $informadas=$informa[0];
                            }
                            else
                            {
                            $informada=$valor;
                            $informa=explode('-', $informada); 
                            $informadas=$informadas.",".$informa[0];
                            }
				            } 
                       $listas[$cont]['area_informada'] = $informadas;
					   $listas[$cont]['motivo'] = $row['motivo'];	
                       $listas[$cont]['fec_apertura'] = $row['fec_apertura'];
					   $listas[$cont]['fec_analisis'] = $row['fec_analisis'];					   
					   $listas[$cont]['fec_revision'] = $row['fec_revision'];
                       $listas[$cont]['fec_plan_cierre'] = $row['fec_plan_cierre'];
					   $listas[$cont]['fec_cierre'] = $row['fec_cierre'];
                       $listas[$cont]['estado'] = $row['estado'];
                       $listas[$cont]['dias'] = $row['dias'];
                       $cont++;
                    }
					}
				return $listas;
			}
}	
	}
}
 
    /*Permite obtener el nombre correspondiente a cada area segun el id de area correspondiente en el RG-40 */
    function encontrar_arreglo_areas($observa,$id_perfil)//el $id_perfil es para darle valor del area q en esos momentos ingreso a la opcion
    {
        $con = new DBmanejador();
		if($con->conectar() == true)
        { 
           $observa=explode(',', $observa);
           foreach($observa as $indice => $valor)
		   {
			$consulta = "
            select area_id
            from relacion_perfil as rp
            WHERE perfil_id='".$id_perfil."'
			";
            $resultado = mysql_query($consulta) or die ('La consulta busqueda area fall&oacute;: ' . mysql_error());//echo $resultado; 
			if (!$resultado)
				return false;
		 	else 
            {
            if ($row = mysql_fetch_array($resultado)) 
            {
                if($row['area_id']==$valor)
				 return true;     
			}
            }
		   }
           return $respuesta;
        }
    }   
}
?>