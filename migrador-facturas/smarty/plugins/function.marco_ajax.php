<?php
function smarty_function_marco_ajax($params, &$smarty) {
	$url = $params['url'];
	$parametros = $params['parametros'];
	$contenedor = $params['contenedor'];
	
	return 'getHTML(\''.$url.'\', \''.$parametros.'\', \''.$contenedor.'\')';
}
?>