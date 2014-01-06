<?php

   include_once('../../clases/includes/dbmanejador.php');
   include_once('../../clases/sistema_de_produccion/familias.php');
  class FamiliaEstilos
  {
           /***********OBTIENE LA LISTA DE FAMILIAS DE ACUERDO A LOS ESTILOS************************/
			function consulta_lista_familia_estilos()
     		{
			     $familias=new Familia;
			     $con = new DBmanejador;
        		 if($con->conectar()==true)
		         {
				     $consulta="select e.descripcion,f.familia_id, t.familia_estilo_id from testilos e,tfamilia_estilo t,tfamilias f where e.estilo_id=t.estilo_id and f.familia_id=t.familia_id limit 0,5";
   			         $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
				     if (!$resultado) return false;
					 else
					 {
					        if(mysql_num_rows($resultado) == 0)
							{
							   return false;
							}
							else
							{        
							          for($a=0;$a<(mysql_num_rows($resultado));$a++)
									  {
									 		$reg = mysql_fetch_array($resultado);
									
											 $familia=$familias->Obtener_cadena_modelos($reg['familia_id']); 
											 // echo "<li>".$reg['descripcion']."</li>";
											 $lista[$reg['familia_estilo_id']]=$familia." - ".$reg['descripcion'];
											 $contador++;
											
		  							  }
									  
							   return $lista;
							}
					 }
				 }  			
        }
	  
	   function verifica_familia_estilo_valida($cadena_familia)
	   {
			   $lista_familias=$this->consulta_lista_familia_estilos();
			   $familia_id=-1;
			   foreach($lista_familias as $indice => $valor )
			   {
			      
				  if (trim($lista_familias[$indice])==$cadena_familia)
					     $familia_id=$indice;
			   }
		  
			  return $familia_id;							  
	  }
	   	
	 
	  
	  function busqueda_familia_estilos($cadena)
	  {
	      $lista_familia_estilos=$this->consulta_lista_familia_estilos();
		  $contador=0;
		  foreach($lista_familia_estilos as $indice => $valor )
			   {   
			        if(!empty($cadena))
                    {
			      		 $existe=strpos(strtolower($lista_familia_estilos[$indice]),strtolower($cadena));
						
						 if(is_numeric($existe))
           
				   		 {	     
						     $lista[$contador]=$lista_familia_estilos[$indice];
						     $contador++;
                         }
				  }
				  else
				  {
				      $lista[$contador]=$lista_familia_estilos[$indice];
					  $contador++;
				  
				  }
			   }
	       return $lista;
	  }
	            
}	  
?>