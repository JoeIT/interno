<?php
class Validador {
    function validarNumeros($valor, $min, $max)
    {
        // return (!ereg("[0-9]{1,255}$", $valor));
        return (!ereg("^[0-9]{" . $min . "," . $max . "}$", $valor));
    }
    function validarNumerosDecimales($valor, $min, $max)
    {
        // return (!ereg("[0-9]{1,255}$", $valor));
        return (!ereg("^[0-9\.]{" . $min . "," . $max . "}$", $valor));
    }

    function validarTexto($valor, $min, $max)
    {
        // return (!ereg("[a-zA-Z]{1,255}$", $valor));
        return (!ereg("^[a-zA-Z ]{" . $min . "," . $max . "}$", $valor));
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
    function validarTextoNumeros2($valor, $min, $max)
    {
        return (!ereg("[a-zA-Z0-9]{" . $min . "," . $max . "}$", $valor));
    }
     
}

?>