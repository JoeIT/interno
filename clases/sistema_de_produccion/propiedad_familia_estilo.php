<?php


    include_once('../../clases/includes/dbmanejador.php');
   include_once('../../clases/includes/validador.php');
  class PropiedadFamiliaEstilo
  {
      
	  function verificar_propiedad_familia_estilo($propiedad_id,$familia_estilo_id)
	  {
	       
	       $con = new DBmanejador;
		  if($con->conectar()==true)
		  {
                 $consulta= " SELECT prop_familia_estilo_id FROM tprop_familia_estilo 
							 where propiedad_id=".$propiedad_id." and $familia_estilo_id=".$familia_estilo_id;

                  $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

			if (!$resultado) return false;
			else
			{
			      if(mysql_num_rows($resultado) == 0)
				  {$propiedad_familia_estilo_id=-1; }
                   else
                  {	
				      $reg = mysql_fetch_array($resultado);
					  $propiedad_familia_estilo_id=$reg['prop_familia_estilo_id'];
				  }
	        }
		 }
		 return $propiedad_familia_estilo_id;
	  }
	  
	  function insertar_nueva_propiedad_familia_estilo($propiedad_id,$familia_estilo_id)
	  {
	       $con = new DBmanejador;
           if($con->conectar()==true)
       	   {
			  
 			  $consulta= "INSERT  into tprop_familia_estilo (propiedad_id,familia_estilo_id) values(".$propiedad_id.",".$familia_estilo_id.")";
				 
		         $resultado=mysql_query($consulta) or die('La consulta fall&oacute; en insertar propiedad_familia_estilo: ' . mysql_error());
			     if (!$resultado) return false;
 	     		 else
				 { 
				      $propiedad_familia_estilo_id = mysql_insert_id();
					  return $propiedad_familia_estilo_id;
		     	 }
	           		
			}
	  
	  }
}
?>