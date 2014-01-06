<?php
session_start();
include_once('includes/dbmanejador.php');

class Permiso {
	function Permiso(){
	}

	function listado($url,$grupo){
		$con = new DBmanejador;
		if($con->conectar()==true){
			$consulta= "SELECT * FROM `tgrupousuario` As g, `tpermisos` AS pe, `tpaginas` AS pa WHERE g.grupousuario_id = pe.grupousuario_id AND pe.paginas_id = pa.paginas_id AND pa.url = '".$url."' AND g.grupousuario_id = ".$grupo;
			$resultado= mysql_query($consulta) or die('La consulta fall&oacute;:' . mysql_error());
			$row = mysql_fetch_array($resultado);
			
			if ($row) {
				return true;
			} else {
				return false;
			}
		}
	}
	
	function menu()
	{
		$con = new DBmanejador;
		if($con->conectar()==true)
		{
			
			$grupo=$_SESSION['grupo_id'];
	
			$consulta= "
				SELECT pa.nombre,pa.URL, 
					pa.pestana, 
					pa.icono, 
					pa.orden 
				FROM tgrupousuario As g, 
					tpermisos As pe, 
					tpaginas AS pa 
				WHERE g.grupousuario_id = pe.grupousuario_id 
					AND pe.paginas_id=pa.paginas_id 
					AND pa.menu=1 
					AND g.grupousuario_id = ".$grupo." 
				ORDER BY orden,orden_pestana";
			
			$resultado= mysql_query($consulta) or die('La consulta fall&oacute;:' . mysql_error());
	
			
			if (!$resultado) return false;
		 	else
		 	{      $contador=0;
					
					while($row = mysql_fetch_array($resultado))
					{
						
  				  		$respuesta[$contador]["url"]= $row['URL'];
						$respuesta[$contador]["icono"]= $row['icono'];
						$respuesta[$contador]["pestañas"]= $row['pestana'];
						$respuesta[$contador]["nombre"]= $row['nombre'];
						$respuesta[$contador]["orden"]= $row['orden'];
						$respuesta[$contador]["cont"]= $contador;
                  		$contador=$contador+1;
  					}
				//print_r($respuesta);
		     	return $respuesta;
		  	}			
		}
	}
	
	function menu_agrupado()
	{
		$con = new DBmanejador;
		if($con->conectar()==true)
		{
			
			$grupo=$_SESSION['grupo_id'];
	
			$consulta= "SELECT distinct pa.pestana FROM tgrupousuario As g, tpermisos As pe, tpaginas AS pa WHERE g.grupousuario_id = pe.grupousuario_id AND pe.paginas_id=pa.paginas_id AND pa.menu=1 AND g.grupousuario_id = ".$grupo." ORDER BY orden";
			//echo "MENU AGRUPADO:".$consulta;
			$resultado= mysql_query($consulta) or die('La consulta fall&oacute;:' . mysql_error());

			
			if (!$resultado) return false;
		 	else
		 	{      $contador=1;

			/*		$row = mysql_fetch_array($resultado);
					$pestana=$pestana.'\''.$row['pestana'].'\'';
					$paneles=$paneles.'\''.'pane'.$contador.'\'';
					$mostrar=$mostrar.'false';*/
					
					while($row = mysql_fetch_array($resultado))
					{
						if ($contador ==1){
							$pestana=$pestana.'\''.$row['pestana'].'\'';
							$paneles=$paneles.'\''.'pane'.$contador.'\'';
							$mostrar=$mostrar.'false';
						} else {
							//$contador=$contador+1;
							$pestana=$pestana.',\''.$row['pestana'].'\'';
							$paneles=$paneles.',\''.'pane'.$contador.'\'';
							$mostrar=$mostrar.',false';
						
						}
						
						$contador++;
							
					}
					
					$respuesta["nivel"]= $pestana;
					$respuesta["paneles"]= $paneles;
					$respuesta["mostrar"]= $mostrar;
				
				//print_r($respuesta);
		     	return $respuesta;
		  	}			
		}
	}
	
}
?>