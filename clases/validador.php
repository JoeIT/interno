<?php

class Validador {
    function validarNumeros($valor, $min, $max)
    {
        // return (!ereg("[0-9]{1,255}$", $valor));
        return (!ereg("[0-9]{" . $min . "," . $max . "}$", $valor));
    }
    function validarNumerosDecimales($valor, $min, $max)
    {
        // return (!ereg("[0-9]{1,255}$", $valor));
        return (!ereg("[0-9.]{" . $min . "," . $max . "}$", $valor));
    }

    function validarTexto($valor, $min, $max)
    {
        // return (!ereg("[a-zA-Z]{1,255}$", $valor));
        return (!ereg("[a-zA-Z ]{" . $min . "," . $max . "}$", $valor));
    }
    function validarTextoNumeros($valor, $min, $max)
    {
        return (!ereg("[a-zA-Z0-9{}@#_\-\.]{" . $min . "," . $max . "}$", $valor));
    }

    function validarTodo($valor, $min, $max)
    {
        return (empty($valor));
    }

    function validarTelefono($valor, $min, $max)
    {
        return (!ereg("[0-9\-_\.()]{" . $min . "," . $max . "}$", $valor));
    }
    function validarZip($valor, $min, $max)
    {
        return (!ereg("[0-9a-zA-Z\-_\.()]{" . $min . "," . $max . "}$", $valor));
    }

    function validarEmail($email)
    {
      return (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)  );
    }
    function validarFecha($fecha)
    {
       $fecha_div=split("-",$fecha);
       if($fecha_div[1]<10)
       		$fecha_div[1]=str_replace("0","",$fecha_div[1]);
       if($fecha_div[0]<10)
       		$fecha_div[0]=str_replace("0","",$fecha_div[0]);

       return checkdate(trim($fecha_div[1]),trim($fecha_div[0]),$fecha_div[2]);

    }

	function validarFecha2($mydate) {
		list($yy,$mm,$dd)=explode("-",$mydate);
		if (is_numeric($yy) && is_numeric($mm) && is_numeric($dd)) {
			if ($yy >= 1900)
				return checkdate($mm,$dd,$yy);
		}
		return false;
	}

	function cambiaf_a_mysql($fecha)
	{     
	          //cambia de formato dd-mm-yyyy a yyyy-mm-dd
			 $mifecha=split("-",$fecha);
   		  	 $lafecha=$mifecha[2]."-".$mifecha[1]."-".$mifecha[0];
			 return $lafecha;
	} 
}

?>