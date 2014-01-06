<?php /* Smarty version 2.6.18, created on 2011-09-12 12:55:55
         compiled from tipo_cambio_automatico.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "cabecera_popup.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Upload files with other Form fields example</title>
	<!--<link rel="stylesheet" href="styles.css">-->
	
	<script src="../templates/script/jquery.min.js" type="text/javascript"></script> 
	<!--<script src="../templates/fileManager/jquery.easing.js" type="text/javascript"></script> -->
	<script src="../templates/jqueryFileTree/jqueryFileTree.js" type="text/javascript"></script> 
	<link href="../templates/jqueryFileTree/jqueryFileTree.css" rel="stylesheet" type="text/css" media="screen" /> 
	<link type="text/css" href="../templates/css/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
	<link href="../templates/plupload/js/jquery.ui.plupload/css/jquery.ui.plupload.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../templates/script/jquery-ui-1.8.16.custom.min.js"></script>
	<!-- Thirdparty intialization scripts, needed for the Google Gears and BrowserPlus runtimes -->
	<script type="text/javascript" src="http://bp.yahooapis.com/2.4.21/browserplus-min.js"></script>
	
	<script type="text/javascript" src="../templates/plupload/js/plupload.full.js"></script>
	<script type="text/javascript" src="../templates/plupload/js/plupload.gears.js"></script>	

	<!-- Load plupload and all it's runtimes and finally the jQuery UI queue widget -->
	
	<script type="text/javascript" src="../templates/plupload/js/jquery.ui.plupload/jquery.ui.plupload.js"></script>

<body>
<p><br>
<spam class="enunciados"></spam>
</p>


<?php echo '
<script language="JavaScript">
	jQuery(document).ready( function() {
		
		loadFileTree();
		
		jQuery("#uploader").plupload({
			runtimes : \'gears,html5,html4\',
			url : \'upload.php\',
			max_file_size : \'10mb\',
			chunk_size : \'1mb\',
			//unique_names : true,//para encriptar los archivos
			filters : [
				{title : "csv files", extensions : "csv"}
			],
			init:{
				FileUploaded: function(up, file, info) {
					jQuery(\'#nombre_archivo\').val(file.name);
					loadFileTree();
				}
			}
		});
	});
	function loadFileTree(){
		jQuery(\'#container_id\').fileTree({
				root: \'/wamp/www/sistema/contabilidad/controladores/UploadedTipo/\',
				folderEvent: \'dblclick\', 
				expandSpeed: 1, 
				collapseSpeed: 1
			},
			function(file) {
				archi=file.split(\'/\');
				nombre=archi[archi.length-1];
				jQuery(\'#nombre_archivo\').val(nombre);
			}
		);
	}
		
</script>
<style>
.plupload_cell,.plupload_file_status, .plupload_file_size, .plupload_file_action{
	font-size:10px;
}
</style>
'; ?>

<form method="POST" onSubmit="return mysubmit();" id="myform" name="myform" action="../../contabilidad/controladores/uploadfiles.php?bandera">
	<input name="bandera" type="hidden" id="bandera" value="1" size="1" />
	<table width="450" align="center">
		<tr>
			<td colspan="6" align="center">Actualizando Tipo Cambio</td>
		</tr>
		<?php if ($this->_tpl_vars['tipos_actuales'] != null): ?>
		<?php unset($this->_sections['ind']);
$this->_sections['ind']['name'] = 'ind';
$this->_sections['ind']['loop'] = is_array($_loop=$this->_tpl_vars['tipos_actuales']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['ind']['show'] = true;
$this->_sections['ind']['max'] = $this->_sections['ind']['loop'];
$this->_sections['ind']['step'] = 1;
$this->_sections['ind']['start'] = $this->_sections['ind']['step'] > 0 ? 0 : $this->_sections['ind']['loop']-1;
if ($this->_sections['ind']['show']) {
    $this->_sections['ind']['total'] = $this->_sections['ind']['loop'];
    if ($this->_sections['ind']['total'] == 0)
        $this->_sections['ind']['show'] = false;
} else
    $this->_sections['ind']['total'] = 0;
if ($this->_sections['ind']['show']):

            for ($this->_sections['ind']['index'] = $this->_sections['ind']['start'], $this->_sections['ind']['iteration'] = 1;
                 $this->_sections['ind']['iteration'] <= $this->_sections['ind']['total'];
                 $this->_sections['ind']['index'] += $this->_sections['ind']['step'], $this->_sections['ind']['iteration']++):
$this->_sections['ind']['rownum'] = $this->_sections['ind']['iteration'];
$this->_sections['ind']['index_prev'] = $this->_sections['ind']['index'] - $this->_sections['ind']['step'];
$this->_sections['ind']['index_next'] = $this->_sections['ind']['index'] + $this->_sections['ind']['step'];
$this->_sections['ind']['first']      = ($this->_sections['ind']['iteration'] == 1);
$this->_sections['ind']['last']       = ($this->_sections['ind']['iteration'] == $this->_sections['ind']['total']);
?>
		<tr>
			<?php if ($this->_tpl_vars['tipos_actuales'][$this->_sections['ind']['index']]['id'] == 2): ?>
			<td class="enunciados">UFV:</td>
			<td class="enunciados"><?php if ($this->_tpl_vars['fecha'] > $this->_tpl_vars['tipos_actuales'][$this->_sections['ind']['index']]['fecha_ini']): ?><label style="color:#F00"><?php echo $this->_tpl_vars['tipos_actuales'][$this->_sections['ind']['index']]['fecha_ini']; ?>
</label><?php else: ?><label style="color:#30F"><?php echo $this->_tpl_vars['tipos_actuales'][$this->_sections['ind']['index']]['fecha_ini']; ?>
</label><?php endif; ?></td>
			<td class="enunciados"><?php if ($this->_tpl_vars['fecha'] > $this->_tpl_vars['tipos_actuales'][$this->_sections['ind']['index']]['fecha_ini']): ?><label style="color:#F00"><?php echo $this->_tpl_vars['tipos_actuales'][$this->_sections['ind']['index']]['tipo_cambio']; ?>
</label><?php else: ?><label><?php echo $this->_tpl_vars['tipos_actuales'][$this->_sections['ind']['index']]['tipo_cambio']; ?>
</label><?php endif; ?></td>
			<?php else: ?>
			<td class="enunciados">DOLAR ($):</td>
			<td class="enunciados"><?php if ($this->_tpl_vars['fecha'] > $this->_tpl_vars['tipos_actuales'][$this->_sections['ind']['index']]['fecha_ini']): ?><label style="color:#F00"><?php echo $this->_tpl_vars['tipos_actuales'][$this->_sections['ind']['index']]['fecha_ini']; ?>
</label><?php else: ?><label style="color:#30F"><?php echo $this->_tpl_vars['tipos_actuales'][$this->_sections['ind']['index']]['fecha_ini']; ?>
</label><?php endif; ?></td>
			<td class="enunciados"><?php if ($this->_tpl_vars['fecha'] > $this->_tpl_vars['tipos_actuales'][$this->_sections['ind']['index']]['fecha_ini']): ?><label style="color:#F00"><?php echo $this->_tpl_vars['tipos_actuales'][$this->_sections['ind']['index']]['tipo_cambio']; ?>
</label><?php else: ?><label><?php echo $this->_tpl_vars['tipos_actuales'][$this->_sections['ind']['index']]['tipo_cambio']; ?>
</label><?php endif; ?></td>
		 <?php endif; ?>
		</tr>
		<?php endfor; endif; ?>
		<?php else: ?>
		<label style="color:#F00">No se pudo actualizar el tipo de cambio UFV - DOLAR[$]</label>
		<?php endif; ?>
	</table>
	<table align="center" >
		<tr>
			<td align="center" >				
				<div id="uploader" style="width:500px;"></div>
				<div style="background-color:#1C94D0;border:2px solid #095375;">Historial de Archivos CSV Subidos</div>
				<div id="container_id" style="width:500px;height:75px;overflow-y:scroll;font-size:10px;border:3px solid #095375; padding:5px" ></div>
			</td>
		</tr>
	</table>
</form>
<!--FORMULARIO PARA ABRIR ARCHIVO Y CARGARLO A TABLA TIPO CAMBIO-->
<form name="archivo" method="post" action="../controladores/activo.php?opcion=automatico_tipo"  align="center">
	<div align="center">
	<?php if ($this->_tpl_vars['mensaje'] != null): ?>
		<span class="error-box"><?php echo $this->_tpl_vars['mensaje']; ?>
</span><br><br>
	<?php endif; ?>
		<span align="left" class="entrada" style="margin:5px;vertical-align:top;">
			<input name="nombre_archivo" type="text" id="nombre_archivo" value="" size="50" tabindex="1" style="border:none;" onKeyPress="return enter_handle(this, event, 1, 1)">
		</span>
		<input type="submit" value="Enviar Archivo" name="enviar_archivo" class="enviar" onMouseOver="this.className='boton'" onMouseOut="this.className='enviar'" onClick="noVacio()">
	</div>
</form>
</body>
</html>