<?php


   include_once('../../clases/includes/dbmanejador.php');

  class Producto
  {
       
      
      function Producto()
	  {
      }
	  
      function consulta_lista_productos()
      {

	     $con = new DBmanejador;
         if($con->conectar()==true)
         {  
		 
		 
		    
		   /* $consulta= 'SELECT 	s.producto_id,m.descripcion,e.descripcion,c.descripcion,q.tipocuero,o.descripcion,
								t.descripcion,l.descripcion,s.estado
             			FROM tproductos s, tpropiedades p, tcolores o, testilos e, tclips c, tcueros q, 		
							  tetiquetas t, tsellos l, trelaciones r, tmodelos m
						WHERE s.prop_id=p.prop_id and p.estilo_id=e.estilo_id and p.clip_id=c.clip_id 
						and p.cuero_id=q.cuero_id and p.color_id=o.color_id and p.etiqueta_id=t.etiqueta_id
						and p.sello_id=l.sello_id and s.relacion=r.relacion and m.familia=r.familia';*/
			
			$consulta= 'SELECT 	s.producto_id,e.descripcion,c.descripcion,q.tipocuero,o.descripcion,
								t.descripcion,l.descripcion,s.estado
             			FROM    tproductos s, tpropiedades p, tcolores o, testilos e, tclips c, tcueros q, 		
							    tetiquetas t, tsellos l
						WHERE   s.prop_id=p.prop_id and p.estilo_id=e.estilo_id and p.clip_id=c.clip_id 
						        and p.cuero_id=q.cuero_id and p.color_id=o.color_id and  
								p.etiqueta_id=t.etiqueta_id and p.sello_id=l.sello_id';
			
            $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

			if (!$resultado) return false;
			else  
			{
				$contador=0;

				while($row = mysql_fetch_array($resultado))
				{
  				  //$respuesta[$contador]["codigo"]= $row[0];
				  /*$respuesta[$contador]["descripcion"]= $row[1];
				         $respuesta[$contador]["estilo"]= $row[2];
  				         $respuesta[$contador]["clip"]= $row[3];
  				         $respuesta[$contador]["tipo cuero"]= $row[4];
  				         $respuesta[$contador]["color"]= $row[5];
				         $respuesta[$contador]["etiqueta"]= $row[6];
				         $respuesta[$contador]["sello"]= $row[7];
				         $respuesta[$contador]["estado"]= $row[8];*/
						 
				  $respuesta[$contador]["descripcion"]= $row[0];
				  $respuesta[$contador]["estilo"]= $row[1];
  				  $respuesta[$contador]["clip"]= $row[2];
  				  $respuesta[$contador]["tipo cuero"]= $row[3];
  				  $respuesta[$contador]["color"]= $row[4];
				  $respuesta[$contador]["etiqueta"]= $row[5];
                  $respuesta[$contador]["sello"]= $row[6];
				  $respuesta[$contador]["estado"]= $row[7];
				  $contador=$contador+1;
  				}

				return $respuesta;
		 		
			}
		 }
      }
	  
	   function adicionar_producto($descripcion,$estilo,$clip,$cuero,$color,$estilo,$etiqueta,$sello)
       {
	    
		 
           $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
			  //inserta todas las caracteristicas del nuevo producto
		       $consulta= "INSERT  into 
						   tpropiedades(estilo_id,clip_id,cuero_id,color_id,etiqueta_id,sello_id) 	
						   values(".$estilo.",".$clip.",".$cuero.",".$color.",".$etiqueta.",".$sello.")";
			   
			   $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			  
			  //recibo el último id
				$prop_id = mysql_insert_id();
				
				//recupera el valor de la ultima familia insertada
				$consulta="select familia from tmodelos where familia = (Select max(familia) from tmodelos) ";
				$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
				$fila=mysql_fetch_array($resultado);
				$familia=$fila[0][0]+1;
				
				//crea una nueva familia e inserta la descripcion del producto
				$consulta= "INSERT  into tmodelos (descripcion,familia) 	
							values('".$descripcion."',".$familia.")";
				$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());			
				$modelo_id = mysql_insert_id();			
			}
			else{
				echo "La inserción no se realizó";
			} 
                             
			/* if (!$resultado) return false;
			 else return true;*/
               return $familia;
        	}
      }  
	 
  

?>      