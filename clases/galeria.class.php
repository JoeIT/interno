<?php
//***********************************************************
//GALERIA DE FOTOS 
//***********************************************************
class Galeria
{
    var $table;
    var $directorio;
    function Galeria()
    {
        $this->directorio = "../galeria/";
    }
    function getDirectorios($categoria)
    {
        $i=0;
        $dir=opendir($this->directorio.$categoria); 
        while ($archivo = readdir($dir)) {
            if ($archivo=="." || $archivo==".."){
                echo " "; 
            }else{
                $entradas[$i] = $archivo;
                $i++;
            } 
        }
        if (count($entradas)>0)
        {        
            arsort ($entradas);
        }
//		echo "**".$this->directorio.$categoria;
        return $entradas;
    }
    function getPhotosDirectorio($categoria,$mes)
    {
		$folder = opendir($this->directorio.$categoria."/".$mes);
        $i=0;		
		while (($fil = readdir($folder))!== false) 
		{
		
			if(!is_dir($fil))
			{
				$arr = explode('.', $fil);
				if(count($arr) > 1)
				{	
				   $archivos[$i]["ruta"] = $fil;
    	           $titulo = explode('.', $fil);
                   $archivos[$i]["titulo"] = $titulo[0];
                   $i++;
				}
			}
		}
		closedir($folder);       
      /*  echo "<pre>";
        print_r($archivos);
        echo "</pre>";*/
		return $archivos;
    }        
}

?>