<?php
require_once('../clases/conexion.php');
require_once('../clases/grupo.php');
require_once('../clases/tipocambio.php');
require_once('../clases/locsecundaria.php');
require_once('../clases/locprimaria.php');
class Activo{
	
	function numero(){
		$con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
			SELECT	numero,act_id
			FROM	activos
			";
		
			//echo "<br>SQL: ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -numero- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				$cont=0;
      			while ($row=mysql_fetch_array($resultado))
      			{
					$respuesta[$cont]['numero'] = $row['numero'];
					$respuesta[$cont]['act_id']=$row['act_id'];
					$cont++;
				}
				
				return $respuesta;
			}
			
		}

	
	
}
	function insertar_numero($localizacion,$locsecundaria,$grupo,$num_act,$act_id,$numero)
	{
		
		$con = new Conexion;
		if($con->conectar() == true){
			$consulta= "update activos set localizacion='".$localizacion."', locsecundaria='".$locsecundaria."',grupo_id='".$grupo."',num_act='".$num_act."',numero='".$numero."' where act_id=".$act_id;
			$resultado=mysql_query($consulta) or die('La consulta insertar numero fall&oacute;: ' . mysql_error());
			
			
			//echo "<br>SQL: ".$consulta,"<br>";
		
			
			 if (!$resultado) return false;
			 else return true;
	
		}
		}

	
	function insertar_foto($fotografia,$numero,$fecha,$act_id)
		
	{	$con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
			INSERT INTO `fotografia` (`fotografia`, `numero`,`fecha`,`act`)
			VALUES ('".$fotografia."', '".$numero."','".$fecha."','".$act_id."')";
			
		//echo "<br>SQL: ".$consulta;			
			$resultado = mysql_query($consulta) or die ('La consulta -insertar_foto fall&oacute;: ' . mysql_error());
		}
		
		
		
	}
	
	
	function seleccionar_numfoto($numero)
		
	{	$con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
			SELECT	a.numero,f.fotografia 
			FROM	activos a,fotografia f
			where a.numero='".$numero."' and a.numero=f.numero
			";
		
			//echo "<br>SQL: ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -seleccionar_numfoto- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				$cont=0;
      			while ($row=mysql_fetch_array($resultado))
      			{
					$respuesta[$cont]['numero'] = $row['numero'];
					$respuesta[$cont]['fotografia']=$row['fotografia'];
					$cont++;
				}
				
				return $respuesta;
			}
			
		}
		
		
	}

	function insertar_actidfoto($act_id,$numero)
		
	{	$con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
			UPDATE	fotografia
			SET		act ='".$act_id."'				
					
					
			WHERE	numero ='".$numero."' ";
						
			
			//echo "<br>SQL: ".$consulta,"<br>";		
				
			$resultado = mysql_query($consulta) or die ('La consulta -insertar_actidfoto- fall&oacute;: ' . mysql_error());
		}
		
		
		
	}
	
function insertar_activo($numero,$descripcion,$serie,$fecha,$vida_util,$valor_compra,$tipo_cambio,$ad_id,$localizacion,$locsecundaria,$grupo_id,$resp_id,$num_act,$unidad,$cantidad,$valor_residual,$resp_pri,$ufv,$det_adqui,$usuario_id)
	{
		$con = new Conexion;
		if($con->conectar() == true){
			$consulta = "
			INSERT INTO activos (numero,descripcion,serie,fecha,vida_util,valor_compra,tipo_cambio,ad_id,localizacion,locsecundaria,grupo_id,resp_id,num_act,unidad,estado,cantidad,valor_residual,resp_pri,ufv,det_adqui,usuario_id)
			VALUES ('".$numero."','".$descripcion."','".$serie."','".$fecha."','".$vida_util."','".$valor_compra."','".$tipo_cambio."','".$ad_id."','".$localizacion."','".$locsecundaria."','".$grupo_id."','".$resp_id."','".$num_act."','".$unidad."','1','".$cantidad."','".$valor_residual."','".$resp_pri."','".$ufv."','".$det_adqui."','".$usuario_id."')
			";
			
		//echo "<br>SQL: ".$consulta;			
			$resultado = mysql_query($consulta) or die ('La consulta -insertar_activo- fall&oacute;: ' . mysql_error());
			
		}
		
		
	}
function listacompleta($localizacion,$locsecundaria,$grupo_id,$num_act)
	{$con = new Conexion;
		if($con->conectar() == true)
		{
			$consulta = "
			SELECT	numero,localizacion,locsecundaria,grupo_id,num_act,descripcion,act_id
			FROM	activos
			where localizacion='".$localizacion."'and locsecundaria='".$locsecundaria."' and grupo_id='".$grupo_id."' and num_act='".$num_act."'
			and estado='1'
			";
		
			//echo "<br>SQL: ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -lista completa- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				$cont=0;
      			while ($row=mysql_fetch_array($resultado))
      			{
					$respuesta[$cont]['numero'] = $row['numero'];					
					$respuesta[$cont]['localizacion'] = $row['localizacion'];
					$respuesta[$cont]['locsecundaria']=$row['locsecundaria'];
					$respuesta[$cont]['grupo_id']=$row['grupo_id'];
					$respuesta[$cont]['num_act']=$row['num_act'];
					$respuesta[$cont]['descripcion']=$row['descripcion'];
					$respuesta[$cont]['act_id'] = $row['act_id'];	
					$cont++;
				}
				
				return $respuesta;
			}
		
		
	}
	
	}
function listagruponumero($grupo_id,$num_act)
	{$con = new Conexion;
		if($con->conectar() == true)
		{
			$consulta = "
			SELECT	numero,localizacion,locsecundaria,grupo_id,num_act,descripcion,act_id
			FROM	activos
			where  grupo_id='".$grupo_id."' and num_act='".$num_act."'and estado='1'
			";
		
			//echo "<br>SQL: ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -lista completa- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				$cont=0;
      			while ($row=mysql_fetch_array($resultado))
      			{
					$respuesta[$cont]['numero'] = $row['numero'];					
					$respuesta[$cont]['localizacion'] = $row['localizacion'];
					$respuesta[$cont]['locsecundaria']=$row['locsecundaria'];
					$respuesta[$cont]['grupo_id']=$row['grupo_id'];
					$respuesta[$cont]['num_act']=$row['num_act'];
					$respuesta[$cont]['descripcion']=$row['descripcion'];
					$respuesta[$cont]['act_id'] = $row['act_id'];	
					$cont++;
				}
				
				return $respuesta;
			}
		
		
	}
	
	}
function listaactivos1($localizacion,$locsecundaria,$grupo_id)
	{$con = new Conexion;
		if($con->conectar() == true)
		{
			$consulta = "
			SELECT	a.numero,a.localizacion,a.locsecundaria,a.grupo_id,a.num_act,a.descripcion,a.act_id,g.grupo
			FROM	activos a,grupoactivo g
			where a.localizacion='".$localizacion."'and a.locsecundaria='".$locsecundaria."' and a.grupo_id='".$grupo_id."' 
			and a.grupo_id=g.grupo_id and estado='1'
			group by num_act asc
			";
		
			//echo "<br>SQL:esta ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -listar activos1- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				$cont=0;
      			while ($row=mysql_fetch_array($resultado))
      			{
					$respuesta[$cont]['numero'] = $row['numero'];					
					$respuesta[$cont]['localizacion'] = $row['localizacion'];
					$respuesta[$cont]['locsecundaria']=$row['locsecundaria'];
					$respuesta[$cont]['grupo_id']=$row['grupo_id'];
					$respuesta[$cont]['num_act']=$row['num_act'];
					$respuesta[$cont]['descripcion']=$row['descripcion'];
					$respuesta[$cont]['act_id'] = $row['act_id'];	
					$respuesta[$cont]['grupo'] = $row['grupo'];	
					$cont++;
				}
				
				return $respuesta;
			}
		
		
	}
	
	
	}
function listaactivos2($grupo_id)
	{$con = new Conexion;
		if($con->conectar() == true)
		{
			$consulta = "
			SELECT	a.numero,a.localizacion,a.locsecundaria,a.grupo_id,a.num_act,a.descripcion,a.act_id,g.grupo
			FROM	activos a,grupoactivo g
			where  a.grupo_id='".$grupo_id."' 
			and a.grupo_id=g.grupo_id and estado='1'
			group by num_act asc
			";
		
			//echo "<br>SQL:esta ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -listar activos1- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				$cont=0;
      			while ($row=mysql_fetch_array($resultado))
      			{
					$respuesta[$cont]['numero'] = $row['numero'];					
					$respuesta[$cont]['localizacion'] = $row['localizacion'];
					$respuesta[$cont]['locsecundaria']=$row['locsecundaria'];
					$respuesta[$cont]['grupo_id']=$row['grupo_id'];
					$respuesta[$cont]['num_act']=$row['num_act'];
					$respuesta[$cont]['descripcion']=$row['descripcion'];
					$respuesta[$cont]['act_id'] = $row['act_id'];	
					$respuesta[$cont]['grupo'] = $row['grupo'];	
					$cont++;
				}
				
				return $respuesta;
			}
		
		
	}
	
	
	}
function detalle_activo($act_id)
	{  $con = new Conexion;
		if($con->conectar() == true)
		{
			$consulta = "
			SELECT a.numero, a.localizacion, a.locsecundaria, a.grupo_id, a.num_act, 
			a.descripcion as desact, a.act_id, a.fecha, a.vida_util, a.valor_compra, a.tipo_cambio,
			a.cantidad,p.descripcion as despri,s.descripcion as dessec,g.descripcion as desgru,a.valor_residual,
			a.serie,a.ufv,p.primaria_id,s.secundaria_id
			FROM activos a,tprimaria p,tsecundaria s,grupoactivo g
 			WHERE a.act_id =  '".$act_id."' AND p.localizacion = a.localizacion 
 			AND s.locsecundaria=a.locsecundaria AND g.grupo_id=a.grupo_id 
			
			";
		
			//echo "<br>SQL: ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -detalle activo- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				
      			if($row=mysql_fetch_array($resultado))
      			{
					$respuesta['numero'] = $row['numero'];					
					$respuesta['localizacion'] = $row['localizacion'];
					$respuesta['locsecundaria']=$row['locsecundaria'];
					$respuesta['grupo_id']=$row['grupo_id'];
					$respuesta['num_act']=$row['num_act'];  
					$respuesta['desact']=$row['desact'];
					$respuesta['act_id'] = $row['act_id'];	
					$respuesta['fecha'] = $row['fecha'];
					$respuesta['vida_util'] = $row['vida_util'];
					$respuesta['valor_compra'] = $row['valor_compra'];
					$respuesta['tipo_cambio'] = $row['tipo_cambio'];
					$respuesta['despri']=$row['despri'];
					$respuesta['dessec']=$row['dessec'];
					$respuesta['desgru']=$row['desgru'];
					$respuesta['valor_residual']=$row['valor_residual'];
				    $respuesta['serie']=$row['serie'];
				    $respuesta['ufv']=$row['ufv'];
					$respuesta['primaria_id']=$row['primaria_id'];
					$respuesta['secundaria_id']=$row['secundaria_id'];
					
					
					return $respuesta;
					
				}
				else {
					return false;
				}
				
				
			}
				
		
	}
	
}
function detalle_activo_completo($act_id)
	{  $con = new Conexion;
		if($con->conectar() == true)
		{
			$consulta = "
		
 			SELECT a.numero, a.localizacion, a.locsecundaria, a.grupo_id, a.num_act,
			a.descripcion as desact, a.act_id, a.fecha, a.vida_util, a.valor_compra, a.tipo_cambio,
			a.cantidad,p.descripcion as despri,s.descripcion as dessec,g.descripcion as desgru,a.valor_residual,
			a.serie,a.ufv,p.primaria_id,s.secundaria_id,ad.nombre,ad.ad_id,a.unidad,a.det_adqui
			FROM tprimaria p,tsecundaria s,grupoactivo g,activos a LEFT JOIN adquisicion ad on ad.ad_id=a.ad_id
 			WHERE a.act_id = '".$act_id."' AND p.localizacion = a.localizacion
 			AND s.locsecundaria=a.locsecundaria AND g.grupo_id=a.grupo_id
			";
		
			//echo "<br>SQL: ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -detalle activo- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				
      			if($row=mysql_fetch_array($resultado))
      			{
					$respuesta['numero'] = $row['numero'];					
					$respuesta['localizacion'] = $row['localizacion'];
					$respuesta['locsecundaria']=$row['locsecundaria'];
					$respuesta['grupo_id']=$row['grupo_id'];
					$respuesta['num_act']=$row['num_act'];  
					$respuesta['desact']=$row['desact'];
					$respuesta['act_id'] = $row['act_id'];	
					$respuesta['fecha'] = $row['fecha'];
					$respuesta['vida_util'] = $row['vida_util'];
					$respuesta['valor_compra'] = $row['valor_compra'];
					$respuesta['tipo_cambio'] = $row['tipo_cambio'];
					$respuesta['despri']=$row['despri'];
					$respuesta['dessec']=$row['dessec'];
					$respuesta['desgru']=$row['desgru'];
					$respuesta['valor_residual']=$row['valor_residual'];
				    $respuesta['serie']=$row['serie'];
				    $respuesta['ufv']=$row['ufv'];
					$respuesta['primaria_id']=$row['primaria_id'];
					$respuesta['secundaria_id']=$row['secundaria_id'];
					$respuesta['ad_id']=$row['ad_id'];
					$respuesta['nombre']=$row['nombre'];
					$respuesta['unidad']=$row['unidad'];
					$respuesta['det_adqui']=$row['det_adqui'];
					
					return $respuesta;
					
				}
				else {
					return false;
				}
				
				
			}
				
		
	}
	
}
function actualizar_activo($numero,$localizacion,$locsecundaria,$grupo_id,$resp_id,$resp_pri,$num_act,$cantidad,$ad_id,$fecha,$unidad,$serie,$descripcion,$tipo_cambio,$ufv,$vida_util,$valor_compra,$valor_residual,$act_id,$det_adqui)
{ $con = new Conexion;
		if($con->conectar() == true)
		{
			$consulta = "
			UPDATE	activos
			SET		numero = '".$numero."'
					, localizacion = '".$localizacion."'
					, locsecundaria = '".$locsecundaria."'
					, grupo_id='".$grupo_id."'
					,resp_id='".$resp_id."'
					,resp_pri='".$resp_pri."'
					,num_act='".$num_act."'
					,cantidad='".$cantidad."'
					,ad_id='".$ad_id."'
					,fecha='".$fecha."'
					,unidad='".$unidad."'
					,serie='".$serie."'					
					,descripcion='".$descripcion."'
					,tipo_cambio='".$tipo_cambio."'					
					,ufv='".$ufv."'
					,vida_util='".$vida_util."'					
					,valor_compra='".$valor_compra."'
					,valor_residual='".$valor_residual."'
					,det_adqui='".$det_adqui."'
					
			WHERE	act_id = ".$act_id;
						
			//echo "<br>SQL: ".$consulta;			
			$resultado = mysql_query($consulta) or die ('La consulta -actualizar_activo- fall&oacute;: ' . mysql_error());
		}
}

function mostrar_foto($numero)
{
	$con = new Conexion;
		if($con->conectar() == true)
		{
			$consulta = "
			SELECT a.numero,f.fotografia,f.foto_id
			FROM activos a,fotografia f
 			WHERE a.numero ='".$numero."' and a.numero=f.numero
			
			";
		
			//echo "<br>SQL: ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -detalle activo- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				$cont=0;
      			while ($row=mysql_fetch_array($resultado))
      			{
					$respuesta[$cont]['numero'] = $row['numero'];					
					$respuesta[$cont]['fotografia'] = $row['fotografia'];
					$respuesta[$cont]['foto_id']=$row['foto_id'];
					$cont ++;
					
				}
					return $respuesta;
					
				}
				
				
			}
				
		
	}
	
function actualizar_foto($act_id,$numerofin,$fotografia,$foto_id)
{ $con = new Conexion;
		if($con->conectar() == true)
		{
			$consulta = "
			UPDATE	fotografia
			SET		numero = '".$numerofin."'
					,fotografia='".$fotografia."'
					
					
			WHERE	foto_id = '".$foto_id."' AND act = ".$act_id;
						
			//echo "<br>SQL: ".$consulta;			
			$resultado = mysql_query($consulta) or die ('La consulta -actualizar_foto- fall&oacute;: ' . mysql_error());
		}
}


 function buscar_responsablepri($act_id)
  {
  	 $con = new Conexion;
		if($con->conectar() == true)
		{
			$consulta = "
			SELECT CONCAT(r.apellido,' ', r.nombre) AS completopri,r.resp_id
			FROM activos a,responsable r
 			WHERE a.act_id =  '".$act_id."' AND r.resp_id=a.resp_pri
						AND r.estado != 0  
			
			";
		
			//echo "<br>SQL: ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -buscar responsable: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				
      			if($row=mysql_fetch_array($resultado))
      			{
					$respuesta['completopri'] = $row['completopri'];					
					$respuesta['resp_id'] = $row['resp_id'];
					
					
					return $respuesta;
					
				}
				else {
					return false;
				}
				
				
			}
				
		
	}
  }
  function buscar_asignado($act_id)
  {
  	$con = new Conexion;
		if($con->conectar() == true)
		{
			$consulta = "
			SELECT CONCAT(r.apellido,' ', r.nombre) AS completo,r.resp_id
			FROM activos a,responsable r
 			WHERE a.act_id =  '".$act_id."' AND r.resp_id=a.resp_id
						AND r.estado != 0  
			
			";
		
			//echo "<br>SQL: ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -buscar responsable: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				
      			if($row=mysql_fetch_array($resultado))
      			{
					$respuesta['completo'] = $row['completo'];					
					$respuesta['resp_id'] = $row['resp_id'];
					
					
					return $respuesta;
					
				}
				else {
					return false;
				}
				
				
			}
				
		
	}
  }

  function resumen_kardex($resp_id)
  {
  	
  	$con = new Conexion;
		if($con->conectar() == true)
		{
			$consulta = "
			SELECT CONCAT(r.apellido,' ', r.nombre) AS completo,r.resp_id,a.descripcion,a.localizacion,a.locsecundaria
			,a.num_act,g.grupo,a.resp_pri,a.act_id,g.descripcion as desgru,g.grupo_id,a.unidad,a.serie,a.numero,r.cargo
			,p.descripcion as despri,s.descripcion as dessecun,a.valor_compra,a.fecha,a.valor_compra,a.tipo_cambio,a.ufv
			FROM activos a,responsable r,grupoactivo g,tprimaria p,tsecundaria s 
 			WHERE  r.resp_id=a.resp_id and g.grupo_id=a.grupo_id and a.localizacion=p.localizacion and a.locsecundaria=s.locsecundaria
			AND r.estado != 0 AND a.resp_id=r.resp_id  and a.estado=1 AND a.resp_pri='".$resp_id."'
			";
		
			//echo "<br>SQL: ".$consulta,"<br>";
			
							
			$resultado = mysql_query($consulta) or die ('La consulta -resumen kardex- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
			
      			$cont=0;
      			while ($row=mysql_fetch_array($resultado))
      			{
					
      				$respuesta[$row['completo']][$row['desgru']][$cont]['completo'] = $row['completo'];					
					$respuesta[$row['completo']][$row['desgru']][$cont]['resp_id'] = $row['resp_id'];
					$respuesta[$row['completo']][$row['desgru']][$cont]['descripcion']=$row['descripcion'];
					$respuesta[$row['completo']][$row['desgru']][$cont]['localizacion']=$row['localizacion'];
					$respuesta[$row['completo']][$row['desgru']][$cont]['locsecundaria']=$row['locsecundaria'];
					$respuesta[$row['completo']][$row['desgru']][$cont]['num_act']=$row['num_act'];
					$respuesta[$row['completo']][$row['desgru']][$cont]['grupo']=$row['grupo'];
					$respuesta[$row['completo']][$row['desgru']][$cont]['resp_pri']=$row['resp_pri'];
					$respuesta[$row['completo']][$row['desgru']][$cont]['act_id']=$row['act_id'];
					$respuesta[$row['completo']][$row['desgru']][$cont]['desgru']=$row['desgru'];
					$respuesta[$row['completo']][$row['desgru']][$cont]['grupo_id']=$row['grupo_id'];
					$respuesta[$row['completo']][$row['desgru']][$cont]['unidad']=$row['unidad'];
					$respuesta[$row['completo']][$row['desgru']][$cont]['serie']=$row['serie'];
					$respuesta[$row['completo']][$row['desgru']][$cont]['numero']=$row['numero'];
					$respuesta[$row['completo']][$row['desgru']][$cont]['cargo']=$row['cargo'];
					
					/*desde aqui lal ocalizacion tien que recuperar*/
					$respuesta[$row['completo']][$row['desgru']][$cont]['despri']=$row['despri'];
					$respuesta[$row['completo']][$row['desgru']][$cont]['dessecun']=$row['dessecun'];
					$respuesta[$row['completo']][$row['desgru']][$cont]['valor_compra']=$row['valor_compra'];
					$respuesta[$row['completo']][$row['desgru']][$cont]['fecha']=$row['fecha'];
					$respuesta[$row['completo']][$row['desgru']][$cont]['valor_compra']=$row['valor_compra'];
					$respuesta[$row['completo']][$row['desgru']][$cont]['tipo_cambio']=$row['tipo_cambio'];
					$respuesta[$row['completo']][$row['desgru']][$cont]['ufv']=$row['ufv'];
					
					$respuesta[$row['completo']][$row['desgru']][$cont]['valor_dolar']=$respuesta[$row['desgru']][$cont]['tipo_cambio']*$row['valor_compra'];				
					$respuesta[$row['completo']][$row['desgru']][$cont]['valor_ufv']=$respuesta[$row['desgru']][$cont]['ufv']*$row['valor_compra'];	
					$cont ++;
					
				}
					return $respuesta;
					
					
				}
				
					
		}
				
		
	}
  function resumen_kardex_personal($resp_id)
  {
  	
  	$con = new Conexion;
		if($con->conectar() == true)
		{
			$consulta = "
			SELECT CONCAT(r.apellido,' ', r.nombre) AS completo,r.resp_id,a.descripcion,a.localizacion,a.locsecundaria
			,a.num_act,g.grupo,a.resp_pri,a.act_id,g.descripcion as desgru,g.grupo_id,a.unidad,a.serie,a.numero,r.cargo
			,p.descripcion as despri,s.descripcion as dessecun,a.valor_compra,a.fecha,a.valor_compra,a.tipo_cambio,a.ufv
			FROM activos a,responsable r,grupoactivo g,tprimaria p,tsecundaria s 
 			WHERE  r.resp_id=a.resp_id and g.grupo_id=a.grupo_id and a.localizacion=p.localizacion and a.locsecundaria=s.locsecundaria
			AND r.estado != 0 AND a.resp_id=r.resp_id  and a.estado=1 AND a.resp_id='".$resp_id."'
			";
		
			//echo "<br>SQL: ".$consulta,"<br>";
			
							
			$resultado = mysql_query($consulta) or die ('La consulta -resumen kardex- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
			
      			$cont=0;
      			while ($row=mysql_fetch_array($resultado))
      			{
					
      				$respuesta[$row['desgru']][$cont]['completo'] = $row['completo'];					
					$respuesta[$row['desgru']][$cont]['resp_id'] = $row['resp_id'];
					$respuesta[$row['desgru']][$cont]['descripcion']=$row['descripcion'];
					$respuesta[$row['desgru']][$cont]['localizacion']=$row['localizacion'];
					$respuesta[$row['desgru']][$cont]['locsecundaria']=$row['locsecundaria'];
					$respuesta[$row['desgru']][$cont]['num_act']=$row['num_act'];
					$respuesta[$row['desgru']][$cont]['grupo']=$row['grupo'];
					$respuesta[$row['desgru']][$cont]['resp_pri']=$row['resp_pri'];
					$respuesta[$row['desgru']][$cont]['act_id']=$row['act_id'];
					$respuesta[$row['desgru']][$cont]['desgru']=$row['desgru'];
					$respuesta[$row['desgru']][$cont]['grupo_id']=$row['grupo_id'];
					$respuesta[$row['desgru']][$cont]['unidad']=$row['unidad'];
					$respuesta[$row['desgru']][$cont]['serie']=$row['serie'];
					$respuesta[$row['desgru']][$cont]['numero']=$row['numero'];
					$respuesta[$row['desgru']][$cont]['cargo']=$row['cargo'];
					
					/*desde aqui lal ocalizacion tien que recuperar*/
					$respuesta[$row['desgru']][$cont]['despri']=$row['despri'];
					$respuesta[$row['desgru']][$cont]['dessecun']=$row['dessecun'];
					$respuesta[$row['desgru']][$cont]['valor_compra']=$row['valor_compra'];
					$respuesta[$row['desgru']][$cont]['fecha']=$row['fecha'];
					$respuesta[$row['desgru']][$cont]['valor_compra']=$row['valor_compra'];
					$respuesta[$row['desgru']][$cont]['tipo_cambio']=$row['tipo_cambio'];
					$respuesta[$row['desgru']][$cont]['ufv']=$row['ufv'];
					
					$respuesta[$row['desgru']][$cont]['valor_dolar']=$respuesta[$row['desgru']][$cont]['tipo_cambio']*$row['valor_compra'];				
					$respuesta[$row['desgru']][$cont]['valor_ufv']=$respuesta[$row['desgru']][$cont]['ufv']*$row['valor_compra'];	
					$cont ++;
					
				}
					return $respuesta;
					
					
				}
				
					
		}
				
		
	}
  function resumen_kardex_especifico($resp_id)
  {
  	
  	$con = new Conexion;
		if($con->conectar() == true)
		{
			$consulta = "
			SELECT CONCAT(r.apellido,' ', r.nombre) AS completo,a.resp_id,a.descripcion,a.localizacion,a.locsecundaria
			,a.num_act,g.grupo,a.resp_pri,a.act_id,g.descripcion as desgru,g.grupo_id,a.unidad,a.serie,a.numero,r.cargo
			,p.descripcion as despri,s.descripcion as dessecun,a.valor_compra,a.fecha,a.valor_compra,a.tipo_cambio,a.ufv,ad.nombre,a.ad_id,a.det_adqui
			FROM activos a,responsable r,grupoactivo g,tprimaria p,tsecundaria s, adquisicion ad
 			WHERE  g.grupo_id=a.grupo_id and a.localizacion=p.localizacion and a.locsecundaria=s.locsecundaria
			AND r.estado != 0 AND a.resp_pri=r.resp_id and a.resp_pri='".$resp_id."' and a.ad_id=ad.ad_id and a.estado=1 
			
			";
		
			//echo "<br>SQL: ".$consulta,"<br>";
			
			
				//echo "<br>SQL: ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -resumen kardex- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
			
      			$cont=0;
      			while ($row=mysql_fetch_array($resultado))
      			{
					$respuesta[$cont]['completo'] = $row['completo'];					
					$respuesta[$cont]['resp_id'] = $row['resp_id'];
					$respuesta[$cont]['descripcion']=$row['descripcion'];
					$respuesta[$cont]['localizacion']=$row['localizacion'];
					$respuesta[$cont]['locsecundaria']=$row['locsecundaria'];
					$respuesta[$cont]['num_act']=$row['num_act'];
					$respuesta[$cont]['grupo']=$row['grupo'];
					$respuesta[$cont]['resp_pri']=$row['resp_pri'];
					$respuesta[$cont]['act_id']=$row['act_id'];
					$respuesta[$cont]['desgru']=$row['desgru'];
					$respuesta[$cont]['grupo_id']=$row['grupo_id'];
					$respuesta[$cont]['unidad']=$row['unidad'];
					$respuesta[$cont]['serie']=$row['serie'];
					$respuesta[$cont]['numero']=$row['numero'];
					$respuesta[$cont]['cargo']=$row['cargo'];
					
					/*desde aqui lal ocalizacion tien que recuperar*/
					$respuesta[$cont]['despri']=$row['despri'];
					$respuesta[$cont]['dessecun']=$row['dessecun'];
					$respuesta[$cont]['valor_compra']=$row['valor_compra'];
					$respuesta[$cont]['fecha']=$row['fecha'];
					$respuesta[$cont]['tipo_cambio']=$row['tipo_cambio'];
					$respuesta[$cont]['ufv']=$row['ufv'];
					$respuesta[$cont]['nombre']=$row['nombre'];
					$respuesta[$cont]['ad_id']=$row['ad_id'];
					$respuesta[$cont]['det_adqui']=$row['det_adqui'];
					
					$cont ++;
					
				}
					return $respuesta;
					
					
				}
				
					
		}
				
		
	}
  
  function reporte_transferencia($act_id)
  {
  	
  	$con = new Conexion;
		if($con->conectar() == true)
		{
			$consulta = "
			SELECT a.descripcion,a.num_act,CONCAT(r.apellido,' ', r.nombre) AS completo,asi.fecha ,
			 a.act_id,a.serie,a.unidad,s.descripcion as dessec, p.descripcion as despri,g.descripcion as desgru,g.grupo,a.numero 
			 FROM asignacion asi,responsable r,activos a,tsecundaria s,tprimaria p,grupoactivo g 
			 WHERE asi.act_id = '".$act_id."' and asi.resp_id=r.resp_id and asi.secundaria_id=s.secundaria_id 
			 and asi.primaria_id=p.primaria_id and a.grupo_id=g.grupo_id and asi.act_id=a.act_id

			";
		
	    	//echo "<br>SQL: ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -detalle activo- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				$cont=0;
      			while ($row=mysql_fetch_array($resultado))
      			{
					$respuesta[$cont]['descripcion'] = $row['descripcion'];
					$respuesta[$cont]['dessec']=$row['dessec'];	
					$respuesta[$cont]['despri']=$row['despri'];		
					$respuesta[$cont]['completo']=$row['completo'];
					$respuesta[$cont]['fecha']=$row['fecha'];	
					//$respuesta[$cont]['area']=$row['area'];	no todos los empleados estan con area
					$respuesta[$cont]['act_id']=$row['act_id'];
					$respuesta[$cont]['serie']=$row['serie'];
					$respuesta[$cont]['unidad']=$row['unidad'];
					$respuesta[$cont]['num_act'] = $row['num_act'];
					$respuesta[$cont]['desgru']=$row['desgru'];
					$respuesta[$cont]['numero']=$row['numero'];
					$respuesta[$cont]['grupo']=$row['grupo'];
					$cont ++;
					
				}
					return $respuesta;
					
				}
				
				
			}
  	
  	
  }
  
  function dar_baja($act_id,$obser,$estado,$fecha)
{ $con = new Conexion;
		if($con->conectar() == true)
		{
			$consulta = "
			UPDATE	activos
			SET		observacion = '".$obser."',estado='".$estado."',fecha_baja='".$fecha."'
			
			WHERE	act_id= ".$act_id;
						
			//echo "<br>SQL: ".$consulta;			
			$resultado = mysql_query($consulta) or die ('La consulta -actualizar_foto- fall&oacute;: ' . mysql_error());
		}
}

  
 

/* function diferencia_fechas ($fecha_inicio,$fecha_fin)
  {
		//defino fecha 1
		list($ano1, $mes1, $dia1) = split("-", $fecha_inicio);
		//defino fecha 2
		list($ano2, $mes2, $dia2) = split("-", $fecha_fin);
		//calculo timestam de las dos fechas
		$timestamp1 = mktime(0, 0, 0, $mes1, $dia1, $ano1);
		$timestamp2 = mktime(0, 0, 0, $mes2, $dia2, $ano2);
		//restar a una fecha la otra
		$segundos_diferencia = $timestamp2 - $timestamp1;
		//convertir segundos en d�as
		$dias_diferencia = $segundos_diferencia / (60* 24 * 60);
		//obtener el valor absoulto de los d�as (quitar el posible signo negativo)
		$dias_diferencia = abs($dias_diferencia);
		
		//quitar los decimales a los d�as de diferencia
		$dias_diferencia = floor($dias_diferencia);
		
		$meses_diferencia=floor($dias_diferencia/30)+1;
		return $meses_diferencia; 
	}
*/
 
	
  function diferencia_fechas_meses ($fecha_inicio, $fecha_fin)
  {
	//************************************************************************
/*$fecha_de_nacimiento = "2000-07-31";
$fecha_actual = date ("2008-03-01"); //para pruebas 103

echo "<br>Hoy es $fecha_actual";
echo "<br>Naciste el $fecha_de_nacimiento";*/

// separamos en partes las fechas
			$activo= new Activo();
			$array_nacimiento = explode ( "-", $fecha_inicio );
			$array_actual = explode ( "-", $fecha_fin );
			
			$anos =  $array_actual[0] - $array_nacimiento[0]; // calculamos a�os
			$meses = $array_actual[1] - $array_nacimiento[1]; // calculamos meses
			$dias =  $array_actual[2] - $array_nacimiento[2]; // calculamos d�as
			
			//ajuste de posible negativo en $d�as
			if ($dias < 0)
			{
			    --$meses;
			
			    //ahora hay que sumar a $dias los dias que tiene el mes anterior de la fecha actual
			    switch ($array_actual[1]) {
			           case 1:     $dias_mes_anterior=31; break;
			           case 2:     $dias_mes_anterior=31; break;
			           case 3: 
			           		$x=$activo->bisiesto($array_actual[0]);
			                if ($x)
			                {
			                    $dias_mes_anterior=29; break;
			                } else {
			                    $dias_mes_anterior=28; break;
			                }
			           case 4:     $dias_mes_anterior=31; break;
			           case 5:     $dias_mes_anterior=30; break;
			           case 6:     $dias_mes_anterior=31; break;
			           case 7:     $dias_mes_anterior=30; break;
			           case 8:     $dias_mes_anterior=31; break;
			           case 9:     $dias_mes_anterior=31; break;
			           case 10:     $dias_mes_anterior=30; break;
			           case 11:     $dias_mes_anterior=31; break;
			           case 12:     $dias_mes_anterior=30; break;
			    }
			
			    $dias = abs($dias + $dias_mes_anterior);
			}
			
			//ajuste de posible negativo en $meses
			if ($meses < 0)
			{
			    --$anos;
			    $meses=$meses + 12;
			}
			
			//echo "<br>Tu edad es: $anos a�os con $meses meses y $dias d�as";
			
			if ($dias > 0)
				$meses = $meses + 1;
			
			$calculo = abs($anos*12 + $meses);
			
			//echo "<br>meses: ".$calculo;
			return $calculo;

	}
	
function bisiesto($anio_actual)
{
	$bisiesto=false;
    //probamos si el mes de febrero del a�o actual tiene 29 d�as
      if (checkdate(2,29,$anio_actual))
      {
        $bisiesto=true;
    }
    return $bisiesto;
}


function diferencia_fechas_undia($fecha_inicio)
  	{    
  		$activo= new Activo();
  		list($ano1, $mes1, $dia1) = split("-", $fecha_inicio);
		$nueva_fecha1 = $dia1."-".$mes1."-".$ano1;
		if ($mes1==1)
		{
			$mes=12;
			$ano=$ano1-1;
			$dia=$activo->utltimoDia($mes,$ano);
		    $fecha=date("Y-m-d",mktime(0,0,0,$mes,$dia,$ano));
			
		}   
		else   
		{  if ($mes1 >1)
			{$m=$mes1-1;
			$mes2="0".$m;
			$dia2=$activo->utltimoDia($mes2,$ano1);
			
			$fecha=date("Y-m-d",mktime(0,0,0,$mes2,$dia2,$ano1));
			}
		}    
	
		return $fecha;				
		
		
	}
	
	function utltimoDia($mes,$ano)
	{
		 switch($mes){
		  case 1:  // Enero
		  case 3:  // Marzo
		  case 5:  // Mayo
		  case 7:  // Julio
		  case 8:  // Agosto
		  case 10:  // Octubre
		  case 12:// Diciembre
		   return 31;
		   break;
		  case 4:  // Abril
		  case 6:  // Junio
		  case 9:  // Septiembre
		  case 11: // Noviembre
		   return 30;
		   break;
		  case 2:  // Febrero
		   if ( (($ano%100 == 0)&&($ano%400 == 0)) || (($ano%100 != 0)&&($ano%4 == 0)) ) 
		    return 29;  // A�o Bisiesto
		   else 
		    return 28;
		   break;
		  
		 }
		}

	 function Primerdiames($fecha_inicio)
  {
	//************************************************************************
/*$fecha_de_nacimiento = "2000-07-31";
$fecha_actual = date ("2008-03-01"); //para pruebas 103

echo "<br>Hoy es $fecha_actual";
echo "<br>Naciste el $fecha_de_nacimiento";*/

// separamos en partes las fechas

			$array_nacimiento = explode ( "-", $fecha_inicio );
			
			 $anos = $array_nacimiento[0]; 
			$meses = $array_nacimiento[1]; 
			$dias =   $array_nacimiento[2]; 
			
			//ajuste de posible negativo en $d�as
			
			$fecha=date("Y-m-d",mktime(0,0,0,$meses,1,$anos));
			
return $fecha;
	}

		 
	}

	?>