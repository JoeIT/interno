<?php
require_once('../clases/conexion.php');

class Grupo{
	
	function listar_grupo(){
		$con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
			SELECT	grupo_id,descripcion,grupo
			FROM	grupoactivo
			";
		
			//echo "<br>SQL: ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -listar_grupo- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
			$cont=0;
      			while ($row=mysql_fetch_array($resultado))
      			{
					$respuesta[$cont]['grupo_id'] = $row['grupo_id'];					
					$respuesta[$cont]['descripcion'] = $row['descripcion'];
					$respuesta[$cont]['grupo'] = $row['grupo'];
					$cont++;
					
					
				}
				return $respuesta;
		}
	}

	
	
    }
    
     function listar_gru($grupo_id){
		$con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
			SELECT	grupo_id,descripcion,grupo 
			FROM	grupoactivo
			where grupo_id!='".$grupo_id."'
			";
		
			//echo "<br>SQL: ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -listar gru- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
			$cont=0;
      			while ($row=mysql_fetch_array($resultado))
      			{
					$respuesta[$cont]['grupo_id'] = $row['grupo_id'];
					$respuesta[$cont]['descripcion'] = $row['descripcion'];
					$respuesta[$cont]['grupo'] = $row['grupo'];
					$cont++;
					
					
				}
				return $respuesta;
		}
	}

	
	
    }
 function locgru($grupo_id){
		$con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
			SELECT	grupo_id,descripcion,grupo 
			FROM	grupoactivo
			where grupo_id='".$grupo_id."'
			";
		
			//echo "<br>SQL: ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -locgru- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
		if($row=mysql_fetch_array($resultado))
      			
      			{
					$respuesta['grupo_id'] = $row['grupo_id'];
					$respuesta['descripcion'] = $row['descripcion'];
					$respuesta['grupo'] = $row['grupo'];
					
					return $respuesta;
					
				}
				else {
					return false;
				}	
				
		}
	}

	
	
    }
    
    function contar_numcorr($grupo_id)
    {
    	$con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
			SELECT	MAX(a.num_act)as num_act
			FROM	grupoactivo g,activos a
			where a.grupo_id='".$grupo_id."' and a.grupo_id=g.grupo_id
			group by grupo
			
			";
		
			//echo "<br>SQL: ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -listar gru- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
			  			
      			if($row=mysql_fetch_array($resultado))
      			
      			{
				$respuesta['num_act'] = $row['num_act']+1;
							
							
				return $respuesta;
      			}
      			
      			else {
					return false;
				}	
		}
	}
    	
    }
  
 
      function buscar_fecha_compra($grupo_id){
		$con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
			SELECT	fecha,act_id,vida_util,fecha_baja
			FROM	activos 
			where grupo_id='".$grupo_id."'  
			";
		//echo "<br>SQL: ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -locgru- fall&oacute;: ' . mysql_error());
			
		if (!$resultado)
				return false;
			else {
			$cont=0;
      			while ($row=mysql_fetch_array($resultado))
      			{
      				$respuesta[$cont]['fecha'] = $row['fecha'];
					$respuesta[$cont]['act_id']= $row['act_id'];
					$respuesta[$cont]['vida_util']= $row['vida_util'];
					$respuesta[$cont]['fecha_baja']=$row['fecha_baja'];
					$cont++;
					
				
				}
				return $respuesta;
				}
				
		}
	

	
    }

 function buscar_fecha_compra_todo(){
		$con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
			SELECT	a.fecha,a.act_id,a.grupo_id,g.descripcion,a.vida_util,a.fecha_baja
			FROM	activos a,grupoactivo g
			where a.grupo_id=g.grupo_id
			  
			";
		//echo "<br>SQLfechas : ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -locgru- fall&oacute;: ' . mysql_error());
			
		if (!$resultado)
				return false;
			else {
			$cont=0;
      			while ($row=mysql_fetch_array($resultado))
      			{
      				$respuesta[$cont]['fecha'] = $row['fecha'];
					$respuesta[$cont]['act_id']= $row['act_id'];
					$respuesta[$cont]['grupo_id']=$row['grupo_id'];
					$respuesta[$cont]['descripcion']=$row['descripcion'];
					$respuesta[$cont]['vida_util']= $row['vida_util'];
					$respuesta[$cont]['fecha_baja'] = $row['fecha_baja'];
					$cont++;
					
				
				}
				return $respuesta;
				}
				
		}
	

	
    }
	
	 function listar_gru_nombre($nombre)
	 {
		$con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
			SELECT	descripcion,numero,act_id,fecha,valor_compra
			FROM	activos
			where descripcion  LIKE '%".$nombre."%'
			";
		
			//echo "<br>SQL: ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -listar gru- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
			$cont=0;
      			while ($row=mysql_fetch_array($resultado))
      			{
					
					$respuesta[$cont]['descripcion'] = $row['descripcion'];
					$respuesta[$cont]['numero'] = $row['numero'];
					$respuesta[$cont]['act_id'] = $row['act_id'];
					$respuesta[$cont]['fecha'] = $row['fecha'];
					$respuesta[$cont]['valor_compra'] = $row['valor_compra'];
					$cont++;
					
					
				}
				return $respuesta;
		}
	}

	}
	
	function listar_por_fecha($fecha_ini,$fecha_fin)
	 {
		$con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
			SELECT	descripcion,numero,act_id,fecha,valor_compra
			FROM	activos
			where fecha <='".$fecha_fin."' and fecha >='".$fecha_ini."'
			";
		
			//echo "<br>SQL: ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -listar gru- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
			$cont=0;
      			while ($row=mysql_fetch_array($resultado))
      			{
					
					$respuesta[$cont]['descripcion'] = $row['descripcion'];
					$respuesta[$cont]['numero'] = $row['numero'];
					$respuesta[$cont]['act_id'] = $row['act_id'];
					$respuesta[$cont]['fecha'] = $row['fecha'];
					$respuesta[$cont]['valor_compra'] = $row['valor_compra'];
					$cont++;
					
					
				}
				return $respuesta;
		}
	}

	}
}
?>