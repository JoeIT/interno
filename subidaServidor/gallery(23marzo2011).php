<?php
class gallery {
	
	var $files = array();
	var $path;
	
	function loadFolder($path){
		$this->path = $path;
		
		//---Guardar en un arreglo todos los archivos en el directorio	
		$folder = opendir($this->path);
		while (($fil = readdir($folder))!== false) 
		{
			//---Si no es un directorio
			$borrar=is_dir($fil);
			if(!is_dir($fil))
			{
				$arr = explode('.', $fil);
				if(count($arr) > 1)
				{	
					//---Ir guardando los nombres en un arreglo
					$this->files[] = $fil;
				}
			}
		}
		//---Cerrar el directorio
		closedir($folder);
		//---Ordenar alfabeticamente el arreglo
		sort($this->files);
	}

	function show(){
		
		//---Crear la galería con los nombres de todos los archivos
		$total = count($this->files);
		$cont = 0;
		usort($this->files,"ordenado");
		
		
       echo '<div id="thumbs"> 
	   <ul class="thumbs noscript" id="tgalleryA" >';
		//para ordenar el arreglo
     	
			//---Situar los thumbnails
			for($i = 0; $i < $total; $i++)
			{	
				
				$separar = explode('.', $this->files[$i]);				
				echo '<li><a  href="'.$this->path.'/'.$this->files[$i].'" rel="lightbox" title="'.$separar[0].'"><img src="'.$this->path.'/'.$this->files[$i].'" width="72" height="72" alt="" /><p align="center">'.$separar[0].'</p></a></li>';
				//echo $separar[0]." ";
			}
		?>
			<script language="javascript">
             $(document).ready(function()
             {
             $("a[rel = 'lightbox']").lightBox();
             });
            </script>
        
       	<?php
		echo '</ul>		
		      </div>';
	}


function ordenado($a, $b)
{ 
	$p1a=explode('[', $a);
	$p2a=explode(']', $p1a[1]);
	$p3a=explode('[', $b);
	$p4a=explode(']', $p3a[1]);

	$p5a=explode(' ', $p2a[0]);
	$p6a=explode(' ', $p4a[0]);
	
	if($p5a[0] == $p6a[0])
	{//echo "step10";
	return 0;
	}
	else{//echo "step11";
			if($p5a[0] == "Ene")   			
   				return -1;//para q vaya al principio
   			if($p5a[0] == "Feb")
   			{
   				if($p6a[0]=="Ene")
   				return 1;//para q vaya al final
   				else 
   				return -1;
   			}	
   			if($p5a[0] == "Mar")
   			{
   				if($p6a[0]=="Ene" or $p6a[0] == "Feb")
   				return 1;//para q vaya al final
   				else 
   				return -1;
   			}
   			if($p5a[0] == "Abr")
   			{
   				if($p6a[0]=="Ene" or $p6a[0] == "Feb" or $p6a[0] == "Mar")
   				return 1;//para q vaya al final
   				else 
   				return -1;
   			}
   			if($p5a[0] == "May")
   			{
   				if($p6a[0]=="Ene" or $p6a[0] == "Feb" or $p6a[0] == "Mar"or $p6a[0] == "Abr")
   				return 1;//para q vaya al final
   				else 
   				return -1;
   			}
   			if($p5a[0] == "Jun")
   			{
   				if($p6a[0]=="Ene" or $p6a[0] == "Feb" or $p6a[0] == "Mar"or $p6a[0] == "Abr"or $p6a[0] == "May")
   				return 1;//para q vaya al final
   				else 
   				return -1;
   			}
   			if($p5a[0] == "Jul")
   			{
   				if($p6a[0]=="Ene" or $p6a[0] == "Feb" or $p6a[0] == "Mar"or $p6a[0] == "Abr" or $p6a[0] == "May" or $p6a[0] == "Jun")
   				return 1;//para q vaya al final
   				else 
   				return -1;
   			}
   			if($p5a[0] == "Ago")
   			{
   				if($p6a[0]=="Ene" or $p6a[0] == "Feb" or $p6a[0] == "Mar"or $p6a[0] == "Abr" or $p6a[0] == "May" or $p6a[0] == "Jun" or $p6a[0] == "Jul")
   				return 1;//para q vaya al final
   				else 
   				return -1;
   			}
   			if($p5a[0] == "Sep")
   			{
   				if($p6a[0]=="Ene" or $p6a[0] == "Feb" or $p6a[0] == "Mar"or $p6a[0] == "Abr" or $p6a[0] == "May" or $p6a[0] == "Jun" or $p6a[0] == "Jul" or $p6a[0] == "Ago")
   				return 1;//para q vaya al final
   				else 
   				return -1;
   			}
   			if($p5a[0] == "Oct")
   			{
   				if($p6a[0]=="Ene" or $p6a[0] == "Feb" or $p6a[0] == "Mar"or $p6a[0] == "Abr" or $p6a[0] == "May" or $p6a[0] == "Jun" or $p6a[0] == "Jul" or $p6a[0] == "Ago" or $p6a[0] == "Sep")
   				return 1;//para q vaya al final
   				else 
   				return -1;
   			}
   			if($p5a[0] == "Nov-Dic")//hacer prueba caso especial
   			{
   				if($p6a[0]=="Ene" or $p6a[0] == "Feb" or $p6a[0] == "Mar"or $p6a[0] == "Abr" or $p6a[0] == "May" or $p6a[0] == "Jun" or $p6a[0] == "Jul" or $p6a[0] == "Ago" or $p6a[0] == "Sep"  or $p6a[0] == "Oct")
   				return 1;//para q vaya al final
   				else 
   				return -1;
   			}
	}
}
}
?>