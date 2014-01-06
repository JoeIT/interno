<?php

/** Start session */
session_start();
require_once('smarty_ajax.php');

/** Smarty configuration */
require_once('../smarty/Smarty.class.php');
$t = new Smarty();
$t->debugging = false;
$t->force_compile = true;
$t->caching = false;
$t->compile_check = true;
$t->cache_lifetime = -1;
$t->template_dir = 'templates';
$t->compile_dir = 'templates_c';
//$t->template_dir = 'ajax/resources/templates';
//$t->compile_dir = 'ajax/resources/templates_c';
$t->plugins_dir = array(
  SMARTY_DIR . 'plugins',
  'ajax/resources/plugins');

$t->assign('site_title', 'smarty_ajax - AJAX-enabled Smarty plugins');

?>