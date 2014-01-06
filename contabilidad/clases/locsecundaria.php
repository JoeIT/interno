<?php
require_once('../clases/conexion.php');

class LocSecundaria{
	
	function listar_locsecundaria(){
		$con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
			SELECT	secundaria_id,descripcion,locsecundaria
			FROM	tsecundaria
			";
		
			//echo "<br>SQL: ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -listar_locsecundaria- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
			$cont=0;
      			while ($row=mysql_fetch_array($resultado))
      			{
					$respuesta[$cont]['secundaria_id'] = $row['secundaria_id'];
					$respuesta[$cont]['descripcion'] = $row['descripcion'];
					$respuesta[$cont]['locsecundaria'] = $row['locsecundaria'];
					$cont++;
					
					
				}
				return $respuesta;
		}
	}

	
	
    }
     function listar_secundarias($secundaria_id,$primaria_id){
		$con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
			SELECT	s.secundaria_id,s.descripcion,s.locsecundaria,l.primaria_id
			FROM	tsecundaria s,localizacion l,tprimaria p 
			where l.secundaria_id!='".$secundaria_id."' and l.primaria_id='".$primaria_id."' 
			and s.secundaria_id=l.secundaria_id and p.primaria_id=l.primaria_id
			
			";
		
		//echo "<br>SQL: ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -listar ssssss secundarias- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
			$cont=0;
      			while ($row=mysql_fetch_array($resultado))
      			{
					$respuesta[$cont]['secundaria_id'] = $row['secundaria_id'];
					$respuesta[$cont]['descripcion'] = $row['descripcion'];
					$respuesta[$cont]['locsecundaria'] = $row['locsecundaria'];
					$respuesta[$cont]['primaria_id'] = $row['primaria_id'];
					
					$cont++;
					
					
				}
				return $respuesta;
		}
	}

	
	
    }


    function localizacionsec($secundaria_id){
		$con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
			SELECT	secundaria_id,descripcion,locsecundaria
			FROM	tsecundaria
			where secundaria_id='".$secundaria_id."'
			";
		
			//echo "<br>SQL: ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -localizacionsec- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
			
      		if($row=mysql_fetch_array($resultado))
      			{
					$respuesta['secundaria_id'] = $row['secundaria_id'];
					$respuesta['descripcion'] = $row['descripcion'];
					$respuesta['locsecundaria'] = $row['locsecundaria'];
				
					return $respuesta;
					
				}
				
				else {
					return false;
				}
		}
	}

	
	
    }
    function consulta_lista_secundaria($primaria_id)
    {
    	$con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
			SELECT	s.secundaria_id,s.descripcion,s.locsecundaria,l.primaria_id
			FROM	tsecundaria s,localizacion l
			where   l.primaria_id='".$primaria_id."' and s.secundaria_id=l.secundaria_id
			";
		
		   // echo "<br>SQL: ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -listar consulta secundarias- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
			$cont=0;
      			while ($row=mysql_fetch_array($resultado))
      			{
					$respuesta[$cont]['secundaria_id'] = $row['secundaria_id'];
					$respuesta[$cont]['descripcion'] = $row['descripcion'];
					$respuesta[$cont]['locsecundaria'] = $row['locsecundaria'];
					$respuesta[$cont]['primaria_id'] = $row['primaria_id'];
					$cont++;
					
					
				}
				return $respuesta;
		}
	}
    }
      function buscar_fecha_compra_sec($secundaria_id,$primaria_id){
		$con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
			SELECT	a.fecha,a.act_id,a.grupo_id,g.descripcion,a.vida_util,a.fecha_baja
			FROM	activos a,tsecundaria s,grupoactivo g,tprimaria p
			where a.locsecundaria=s.locsecundaria AND s.secundaria_id='".$secundaria_id."' and a.grupo_id=g.grupo_id
			and p.primaria_id='".$primaria_id."' and a.localizacion=p.localizacion
			order by a.grupo_id
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
					$respuesta[$cont]['grupo_id']= $row['grupo_id'];
					$respuesta[$cont]['descripcion']=$row['descripcion'];
					$respuesta[$cont]['vida_util']=$row['vida_util'];
					$respuesta[$cont]['fecha_baja'] = $row['fecha_baja'];
				
					$cont++;
					
					
					
				
				}
				return $respuesta;
				}
				
		}
	

	
    }
     function buscar_fecha_compra_gru($secundaria_id,$grupo_id,$primaria_id){
		$con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
			SELECT	a.fecha,a.act_id,a.grupo_id,a.vida_util,a.fecha_baja
			FROM	activos a,tsecundaria s,tprimaria p
			where a.locsecundaria=s.locsecundaria AND s.secundaria_id='".$secundaria_id."'AND a.grupo_id='".$grupo_id."'
			AND p.primaria_id='".$primaria_id."' and a.localizacion=p.localizacion
			order by a.grupo_id
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
					$respuesta[$cont]['grupo_id']= $row['grupo_id'];
					$respuesta[$cont]['vida_util']=$row['vida_util'];
					$respuesta[$cont]['fecha_baja'] = $row['fecha_baja'];	
					$cont++;
					
					
					
				
				}
				return $respuesta;
				}
				
		}
	

	
    }
    
    function grupo_sec($secundaria_id)
    {   $con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
			SELECT	g.grupo_id,g.descripcion
			FROM	activos a,tsecundaria s,grupoactivo g
			where a.locsecundaria=s.locsecundaria AND s.secundaria_id='".$secundaria_id."' AND a.grupo_id=g.grupo_id 
			group by a.grupo_id
			";
		//echo "<br>SQL: ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -locgru- fall&oacute;: ' . mysql_error());
			
		if (!$resultado)
				return false;
			else {
			$cont=0;
      			while ($row=mysql_fetch_array($resultado))
      			{
      				
					$respuesta[$cont]['grupo_id']= $row['grupo_id'];
					$respuesta[$cont]['descripcion']= $row['descripcion'];
					$cont++;
					
					
					
				
				}
				return $respuesta;
				}
				
		}
    	
    	
    	
    }
    
    
}
?>