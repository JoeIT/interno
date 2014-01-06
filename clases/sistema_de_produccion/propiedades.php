<?php


    include_once('../../clases/includes/dbmanejador.php');
   include_once('../../clases/includes/validador.php');
  class Propiedad
  {
      function actualizar_propiedades($propiedad_id,$estilo_id,$clip_id,$cuero_id,$color_id,$etiqueta_id,$sello_id)
		 {
		    $con = new DBmanejador;
         	if($con->conectar()==true)
         	{
		    	$consulta= "update tpropiedades set estilo_id=".$estilo_id.",clip_id=".$clip_id.",cuero_id=".$cuero_id. 
				            ",color_id=".$color_id.",etiqueta_id=".$etiqueta_id.",sello_id=".$sello_id." where prop_id=".$propiedad_id;
				$resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			 	if (!$resultado) return false;
			 	else return true;
            }
		 
		    
		 }
	 
	 
	 
      function insertar_nueva_propiedad($clip_id,$cuero_id,$color_id,$etiqueta_id,$sello_id)
	  {
	       $con = new DBmanejador;
           if($con->conectar()==true)
       	   {
			  
 			  $consulta= "INSERT  into tpropiedades (clip_id,cuero_id,color_id,etiqueta_id,sello_id)  values(".$clip_id.",".$cuero_id.",".$color_id.",".$etiqueta_id.",".$sello_id.")";
			
		         $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en insertar propiedades: ' . mysql_error());
			     if (!$resultado) return false;
 	     		 else
				 { 
				      $prop_id = mysql_insert_id();
					  return $prop_id;
		     	 }
	           		
			}
	  
	  }
     

	  	
     
	   
	   
	   
	   
	  function verificar_propiedad($clip_id,$cuero_id,$color_id,$etiqueta_id,$sello_id)
	  {
	       
	       $con = new DBmanejador;
		  if($con->conectar()==true)
		  {
                 $consulta= " SELECT prop_id FROM tpropiedades 
							 where clip_id=".$clip_id." and cuero_id=".$cuero_id." and color_id=".$color_id." and etiqueta_id=".$etiqueta_id." and sello_id=".$sello_id;

                  $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

			if (!$resultado) return false;
			else
			{
			      if(mysql_num_rows($resultado) == 0)
				  {$propiedad_id=-1; }
                   else
                  {	
				      $reg = mysql_fetch_array($resultado);
					  $propiedad_id=$reg['prop_id'];
				  }
	        }
		 }
		 return $propiedad_id;
	  }
}
?>