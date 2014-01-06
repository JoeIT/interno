<?php
require_once('../../clases/includes/dbmanejador.php');

class Area {
	function Area(){
	               }
    /*Verificar si el area a registrar ya existe
    Parametros:
    $nombre_area= Nombre del area
    */
    function verificar_area($nombre_area)
    {
	       $con = new DBmanejador();
		   if($con->conectar()==true)
		   {  	
			  $consulta= "SELECT id_area FROM area WHERE nombre_area='".$nombre_area."'";
			  $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
              
  			  if (!$resultado) 
                return false;//existe algun error
			  else  
			  {
			      $contador=0;
                  while($row = mysql_fetch_array($resultado))
				  {
				      $id_area = $row['id_area'];
					  $contador++;
				  }
				  if($contador==0) 
                    $id_area=-1;
			  }
			  return $id_area;
			}
	   
	}
	/*Ingresar datos de area
    Parametros:
    $responsable_area= Responsable asignado del area
    $nombre_area= Nombre del area
    $observaciones= Descripcion o alguna observacion del area
    */
	function ingresar_area($responsable,$nombre_area, $observaciones){
		$con = new DBmanejador();
        if($con->conectar() == true){
			$consulta = "
			INSERT INTO `area`
			( `id_responsable`, `nombre_area`,`observaciones`, `fec_registro`)
			VALUES
			(".$responsable.", '".$nombre_area."', '".$observaciones."', CURRENT_DATE)
			";
           // echo $consulta;
			$resultado = mysql_query($consulta) or die ('La consulta -ingresar_observacion- fall&oacute;: ' . mysql_error());
		}
	}

    /*Modificar datos area
    Parametros:
    $id_area= ID del registro de area
    $responsable_area= Resaponsable asignado del area
    $nombre_area= Nombre del area
    $observaciones= Descripcion o alguna observacion del area
    */
	function modificar_area($id_area,$responsable,$nombre_area, $observaciones){
	   
		$con = new DBmanejador();
        if($con->conectar() == true){
			$consulta = "
			UPDATE	area
			SET		id_responsable='".$responsable."',nombre_area= '".$nombre_area."',observaciones = '".$observaciones."'
			WHERE	id_area = ".$id_area;
			$resultado = mysql_query($consulta) or die ('La consulta -modificar_observaciones- fall&oacute;: ' . mysql_error());
		//echo $consulta;
        }
	}

    /*Buscar areas existentes
    Parametros:
    $nombre= Nombre primario o secundario del area modifique para el buscador por areas
    */
	function busqueda_area($nombre){
		$con = new DBmanejador();
		if($con->conectar() == true){
			$consulta = "
			SELECT		id_area
			FROM 		area
			WHERE		nombre_area LIKE '%".$nombre."%'  
			ORDER BY	nombre_area
			";
            $resultado = mysql_query($consulta) or die ('La consulta busqueda area fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
		 	else {
				return $respuesta;
		  	}
		}
	}

    /*Buscar areas existentes
    Parametros:
    $nombre= Nombre primario o secundario del area 
    */
	function areas(){
		$con = new DBmanejador();
		if($con->conectar() == true){
			$consulta = "
			SELECT		nombre_area,personal_id,CONCAT(p.apellidos,' ', p.nombres) AS completo,id_area 
			FROM 		area as a,personal as p 
            WHERE       personal_id=id_responsable
			ORDER BY	nombre_area
			";
            $resultado = mysql_query($consulta) or die ('La consulta busqueda area fall&oacute;: ' . mysql_error());
			//id_area, nombre_area
			if (!$resultado)
				return false;
		 	else {
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$respuesta[$contador]['nombre_area'] = $row['nombre_area'];
                    $respuesta[$contador]['personal_id'] = $row['personal_id'];
					$respuesta[$contador]['completo'] = $row['completo'];
					$respuesta[$contador]['id_area'] = $row['id_area'];
					$contador ++;
				}
				return $respuesta;
		  	}
		}
	}
    
    /*listar las areas existentes
    */
	function listar_reg_areas()
	{
	   $area = new Area();
		$con = new DBmanejador();
		if($con->conectar()==true)
		{
			$consulta= "select id_area,concat(nombres,' ',apellidos) as id_responsable,nombre_area
                        from area as a, personal 
                        where personal_id=id_responsable and a.estado=1 
                        order by id_area";
			$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());	
			if (!$resultado) return false;
			else
			{      $cont=0;
					while($row = mysql_fetch_array($resultado))
					{
					$lista[$cont]['id_area'] = $row['id_area'];
					$lista[$cont]['nombre_area'] = $row['nombre_area'];
                    $lista[$cont]['id_responsable'] = $row['id_responsable'];
					$cont++;
					}
				return $lista;
			}		
	   }
    }
    
    /*Permite recuperar el registro completo con el id_area*/
    function consultar_area($id)
  {
			 $con = new DBmanejador();
			 if($con->conectar()==true)
			 {
				$consulta= "SELECT id_area,CONCAT(p.nombres,' ',p.apellidos) as id_responsable,nombre_area,a.observaciones,a.estado FROM area as a,personal as p  where personal_id=id_responsable and id_area=".$id ;
				$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
				if (!$resultado) return false;
				else
				{      $contador=0;
	
						while($row = mysql_fetch_array($resultado))
						{
							$respuesta[$contador]["id_area"]= $row['id_area'];
							$respuesta[$contador]["id_responsable"]= $row['id_responsable'];
                            $respuesta[$contador]["nombre_area"]= $row['nombre_area'];
                            $respuesta[$contador]["observaciones"]= $row['observaciones'];
                            $respuesta[$contador]["estado"]= $row['estado'];
							$contador=$contador+1;
						}
	
					return $respuesta;
				}
			 }
		  }

    //eliminamos el area seleccionado
	function eliminar_area($nro_registro){ 
		$con = new DBmanejador();
		if($con->conectar() == true){
			$consulta = "
			UPDATE	area
			SET		estado='0'
			WHERE	id_area = ".$nro_registro;
            $resultado = mysql_query($consulta) or die ('La consulta -eliminar asignar detalle- fall&oacute;: ' . mysql_error());
            if (!$resultado) return false;
				 else return true;
		}
	}
}
?>