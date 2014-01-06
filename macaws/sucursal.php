<?php

/**
 * @author Erika Ballesteros
 * @copyright 2011
 */
                           
require_once('ajax/include/loader.php');
session_start();
define('CLAS', "clases/");

require_once(CLAS . "conexion.php");
require_once(CLAS . "ContriSucursal.php");

$link = new Coneccion();
$link = $link->conectiondb();
$title = ".:: Registrar Sucursal ::.";

//lineas para q se cargue Contribuyentes automaticamente
$contri = new ContriSucursal($link);
$contribuyentes = $contri->Contribuyentes();

$t->template_dir = 'templates';
$t->compile_dir = 'templates_c';

$t->assign_by_ref('contribuyentes',$contribuyentes);
$t->display('sucursal.html');
?>