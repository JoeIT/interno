<?php


  include_once('../../clases/includes/dbmanejador.php');
  
  class Material
  {

     

      // FUNCION QUE DEVUELVE UN ARRAY CON LA LISTA DE MATERIALES
      function consulta_lista_materiales()
      {

	     $con = new DBmanejador;
         if($con->conectar()==true)
         {
		    $consulta= 'SELECT * FROM tmateriales order by nombre';
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


		 	if (!$resultado) return false;
		 	else
		 	{      $contador=0;

					while($row = mysql_fetch_array($resultado))
					{
  				  		$respuesta[$contador]["codigo"]= $row['material_id'];
						$respuesta[$contador]["nombre"]= $row['nombre'];
						$respuesta[$contador]["descripcion"]= $row['descripcion'];
						$respuesta[$contador]["unidad"]= $row['unidad'];
                  		$contador=$contador+1;

  					}

		     	return $respuesta;
		  	}
		 }
      }

	 function consultar_material($id)
      {

	     $con = new DBmanejador;
         if($con->conectar()==true)
         {
		    $consulta= 'SELECT * FROM tmateriales where material_id='.$id ;
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


		 	if (!$resultado) return false;
		 	else
		 	{      $contador=0;

					while($row = mysql_fetch_array($resultado))
					{
  				  		$respuesta[$contador]["codigo"]= $row['material_id'];
						$respuesta[$contador]["nombre"]= $row['nombre'];
						$respuesta[$contador]["descripcion"]= $row['descripcion'];
						$respuesta[$contador]["unidad"]= $row['unidad'];
						$contador++;

  					}

		     	return $respuesta;
		  	}
		 }
      }
	  // FUNCION PARA INSERTAR UN NUEVO TIPO DE CUERO
	  function nuevo_material($descripcion)
      {
            $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		    	$consulta= "INSERT  into  tmateriales (descripcion) values('".$descripcion."')";
				$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


			 if (!$resultado) return false;
			 else return true;

        	}
      }
	  // FUNCION PARA ELIMINAR TIPO DE MATERIAL
  	  function eliminar_material($id)
      {
            $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		      	 $consulta= "delete from tmateriales where material_id=('".$id."')";
				 $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

				 if (!$resultado) return false;
				 else return true;
        	}
      }
	  function verificar_material($descrip)
      {
	       $cadena=split("-",$descrip);
		   $nombre=$cadena[0];
		   $descripcion=$cadena[1];
	       $con = new DBmanejador;
		   if($con->conectar()==true)
		   {  	
				
			  $consulta = "SELECT material_id FROM tmateriales WHERE nombre='". trim($nombre) ."' and descripcion='".trim($descripcion)."'";
			  //echo "Consulta: ".$consulta;
			  
			  $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
  			  if (!$resultado) return false;
			  else  
			  {
			      $contador=0;
                  while($row = mysql_fetch_array($resultado))
				  {
				      $material_id = $row['material_id'];
					  $contador++;
				  }
				  if($contador==0) $material_id=-1;
			  
			  }
			  
			  return $material_id;
			}
	   
	  }
	  
   	  function modificar_material($id,$descripcion)
      {

		    $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		    	$consulta= "update tmateriales set descripcion='".$descripcion."' where material_id=".$id;
				$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

			 	if (!$resultado) return false;
			 	else return true;
            }
	  }
	  
	function verificar_material_existente($nombre){
		$con = new DBmanejador;
		if($con->conectar()==true){
			$consulta = "SELECT tipo_material_id FROM tipo_material WHERE descripcion='". trim($nombre)."'";
			$resultado=mysql_query($consulta) or die('La consulta -verificar material existente- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
				$contador = 0;
				while($row = mysql_fetch_array($resultado)){
					$material_id = $row['tipo_material_id'];
					$contador++;
				}
				if($contador == 0)
					$material_id = -1;
			}
			//echo "<br>mat exis: ".$consulta." - ".$material_id;
			return $material_id;
		}
	}
	  
	   function busqueda_materiales($cadena)
      {

	     $con = new DBmanejador;
         if($con->conectar()==true)
         {
		 
		  
		    $consulta= "select m.nombre,m.descripcion,m.unidad,m.dimension_id,d.largo,d.ancho,m.desperdicio from tmateriales m LEFT JOIN tdimensiones d ON m.dimension_id = d.dimension_id where (m.nombre like '%".$cadena."%' or m.descripcion like '%".$cadena."%') order by m.nombre,m.descripcion Limit 0,20";
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());


		 	if (!$resultado) return false;
		 	else
		 	{      $contador=0;

					while($row = mysql_fetch_array($resultado))
					{
  				  		
						$respuesta[$contador]["nombre"]= $row['nombre']."-".$row['descripcion'];
						$respuesta[$contador]["unidad"]= $row['unidad'];
						$respuesta[$contador]["dimensiones"]= $row['largo']." x ".$row['ancho'];
						$respuesta[$contador]["desperdicio"]= $row['desperdicio'];
                  		$contador=$contador+1;
  					}
		     	return $respuesta;
		  	}
		 }
      }
      
	  
	}
?>