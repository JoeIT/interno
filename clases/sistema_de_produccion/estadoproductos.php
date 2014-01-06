<?php


   include_once('../../clases/includes/dbmanejador.php');
   include_once('../../clases/includes/validador.php');

  class EstadoProducto
  {
       
         
	   function registrar_estado($orden_producto_id,$observaciones,$usuario)
       {
	    
		 
           $con = new DBmanejador;
           if($con->conectar()==true)
           {
		      $fec = date("d-m-Y");
			  $hora =date("h:i");
			  $validar = new Validador();
  	          $fecha=$validar->cambiaf_a_mysql($fec);
			  //inserta todas las caracteristicas del nuevo producto
		       $consulta= "INSERT  into 
						   thistorialordenprodnuevo(orden_producto_id,fecha,observaciones,usuario_envio_id) 	
						   values(".$orden_producto_id.",'".$fecha." ".$hora."','".$observaciones."',".$usuario.")";
			   $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			  
			  //recibo el último id
				$estado_id = mysql_insert_id();
				
				
			 if (!$resultado) return false;
			 else return $estado_id;
              
        	}
      }  
	  function obtener_estados($ordenproducto_id)
	  {
	     $con = new DBmanejador;
 		    if($con->conectar()==true)
		    {
			    $consulta= "SELECT o.estadoproducto_id,o.orden_producto_id,u.nombres,												 o.fecha,o.observaciones FROM thistorialordenprodnuevo o ,tusuarios u WHERE u.usuario_id=o.usuario_envio_id and o.orden_producto_id=".$ordenproducto_id;
                $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

			if (!$resultado) return false;
			else
	 		{       
			           $contador=0;
					   
			           while($row = mysql_fetch_array($resultado))
					   {
					    
						$respuesta[$contador]["estadoproducto_id"]= $row[0];
						$respuesta[$contador]["ordenproducto_id"]= $row[1];
						$respuesta[$contador]["nombre"]= $row[2];
						$respuesta[$contador]["fecha"]= $row[3];
						$respuesta[$contador]["observaciones"]= $row[4];
						$contador++;
					  }
						return $respuesta;
			}
	   }
	   }
	   
	  
	  }
?>