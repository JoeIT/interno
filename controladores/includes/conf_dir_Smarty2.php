<?php
define('SMARTY_DIR', '../../Smarty/');
require_once(SMARTY_DIR . 'Smarty.class.php');

class Smarty_conf extends Smarty {
	function __construct() {
		$this->Smarty();
		$this->template_dir = '../../templates/';
		$this->compile_dir = '../../templates_c/';
		$this->config_dir = '../../configs/';
		$this->cache_dir = '../../cache/';
	}
}

$smarty = new Smarty_conf;
?>