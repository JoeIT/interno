<?php /* Smarty version 2.6.18, created on 2011-04-29 12:47:38
         compiled from reporteLcv.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'reporteLcv.html', 152, false),array('modifier', 'string_format', 'reporteLcv.html', 159, false),)), $this); ?>
<?php if ($this->_tpl_vars['id'] == 1): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.html", 'smarty_include_vars' => array('title' => $this->_tpl_vars['title'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php else: ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "headerAuxiliar.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<link rel="stylesheet" href="css/calendar/themes/aqua.css" />
<link rel="stylesheet" href="css/calendar/themes/layouts/big.css" />
<script type="text/javascript" src="css/calendar/utils/zapatec.js"></script>
<script type="text/javascript" src="css/calendar/src/calendar.js"></script>
<script type="text/javascript" src="css/calendar/lang/calendar-en.js"></script>

<form id="form1" name="form1" method="post" action="/sistema/macaws/reportesLcv.php">
<table width="700" border="0" cellpadding="0" cellspacing="0" class="buscarTabla">
  <tr>
    <td>&nbsp;</td>
    <td width="672" nowrap="nowrap" ><input name="tsearch" type="text" class="text6" size="90" maxlength="255" />
      <input name="buscar" type="submit" class="text6" id="buscar" value="Buscar" />
      <input name="search" type="hidden" value="buscar" /></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td >
	<table width="478" height="10" border="0" cellpadding="0" cellspacing="0">
      <tr>
	  <td width="58">&nbsp;</td>
        <td width="111" align="left" valign="top" nowrap="nowrap" class="textWhite"><input name="criterio" type="radio" value="razon_social" checked="checked" />
          Raz&oacute;n social. </td>
        <td width="51" align="left" valign="top" nowrap="nowrap" class="textWhite"><input name="criterio" type="radio" value="nit" />
          NIT</td>
        <td width="105" align="left" valign="top" nowrap="nowrap" class="textWhite"><input name="criterio" type="radio"  value="factura" />
        Nro. Factura. </td>
        <td width="153" align="left" valign="top" nowrap="nowrap" class="textWhite"><input name="criterio" type="radio" value="total_facturado" />
          Importe factura. </td>
      </tr>
    </table>	
	</td>
	
    </tr>
  <tr>
    <td width="26">&nbsp;</td>
    <td align="left" class="textWhite">
	  <table width="500" border="0" cellspacing="1" cellpadding="0">
  <tr>
    <td>Gestion</td>
    <td>
	<select name="gestion" class="obs-quotation">
		<option value="0">Todas las gestiones</option>
		<?php unset($this->_sections['ind']);
$this->_sections['ind']['name'] = 'ind';
$this->_sections['ind']['loop'] = is_array($_loop=$this->_tpl_vars['gestiones']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
		<option value="<?php echo $this->_tpl_vars['gestiones'][$this->_sections['ind']['index']]['gestion']; ?>
"><?php echo $this->_tpl_vars['gestiones'][$this->_sections['ind']['index']]['gestion']; ?>
</option>
		<?php endfor; endif; ?>      	  
	</select>
	</td>
    <td>Periodo</td>
    <td>
		<select name="periodo" class="obs-quotation">
		<option value="0">Todos los periodos</option>
		<option value="1">01</option>
		<option value="2">02</option>
		<option value="3">03</option>
		<option value="4">04</option>
		<option value="5">05</option>
		<option value="6">06</option>
		<option value="7">07</option>
		<option value="8">08</option>
		<option value="9">09</option>
		<option value="10">10</option>
		<option value="11">11</option>
		<option value="12">12</option>
		</select>	
	</td>
    <td>Tipo
	</td>
    <td>
		<select name="tipo" class="obs-quotation">
		<option value="0">Todos</option>
		<option value="1">Compras</option>
		<option value="2">Ventas</option>
		</select>
	</td>
     <?php if ($this->_tpl_vars['_SESSION'][$this->_sections['sucursal_id']['index']] == 1): ?>
    <td>Sucursal</td>
    <td nowrap="nowrap">
    <select name="sucursales" class="obs-quotation">
    <option value="todos">Todos</option>
    <?php unset($this->_sections['id']);
$this->_sections['id']['name'] = 'id';
$this->_sections['id']['loop'] = is_array($_loop=$this->_tpl_vars['sucursales']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['id']['show'] = true;
$this->_sections['id']['max'] = $this->_sections['id']['loop'];
$this->_sections['id']['step'] = 1;
$this->_sections['id']['start'] = $this->_sections['id']['step'] > 0 ? 0 : $this->_sections['id']['loop']-1;
if ($this->_sections['id']['show']) {
    $this->_sections['id']['total'] = $this->_sections['id']['loop'];
    if ($this->_sections['id']['total'] == 0)
        $this->_sections['id']['show'] = false;
} else
    $this->_sections['id']['total'] = 0;
if ($this->_sections['id']['show']):

            for ($this->_sections['id']['index'] = $this->_sections['id']['start'], $this->_sections['id']['iteration'] = 1;
                 $this->_sections['id']['iteration'] <= $this->_sections['id']['total'];
                 $this->_sections['id']['index'] += $this->_sections['id']['step'], $this->_sections['id']['iteration']++):
$this->_sections['id']['rownum'] = $this->_sections['id']['iteration'];
$this->_sections['id']['index_prev'] = $this->_sections['id']['index'] - $this->_sections['id']['step'];
$this->_sections['id']['index_next'] = $this->_sections['id']['index'] + $this->_sections['id']['step'];
$this->_sections['id']['first']      = ($this->_sections['id']['iteration'] == 1);
$this->_sections['id']['last']       = ($this->_sections['id']['iteration'] == $this->_sections['id']['total']);
?>
      <option value="<?php echo $this->_tpl_vars['sucursales'][$this->_sections['id']['index']]['cod_sucursal']; ?>
"><?php echo $this->_tpl_vars['sucursales'][$this->_sections['id']['index']]['sucursal']; ?>
</option>
    <?php endfor; endif; ?>
    </select> 
	</td>
	 <?php else: ?>
    <br>
    <?php echo $this->_tpl_vars['sucursal_name']; ?>

	<?php endif; ?>
    <td>Fecha</td>
    <td><input name="fecha" type="text" class="obs-quotation" size="11" maxlength="10" id="fecha"/></td>
	<td><button id="trigger">&lt;&lt;</button>
	  <label id="errorlabel"> <?php echo $this->_tpl_vars['error']['dateE']; ?>
 </label>
			<?php echo '
			<script type="text/javascript">//<![CDATA[
			Zapatec.Calendar.setup({
			firstDay          : 1,
			showOthers        : true,
			electric          : false,
			inputField        : "fecha",
			button            : "trigger",
			ifFormat          : "%Y-%m-%d",
			daFormat          : "%Y/%m/%d"
			});
			//]]></script>
			'; ?>

			<noscript>
			<br/>
			This page uses a <a href="http://www.zapatec.com/website/main/products/prod1/"> Javascript Calendar </a>, but
			your browser does not support Javascript. 
			<br/>
			Either enable Javascript in your Browser or upgrade to a newer version.
			</noscript>
			<a href="http://www.zapatec.com/website/main/products/prod1/"></a>
	</td>
  </tr>
</table>

	</td>    
    </tr>
</table>
</form>
<?php if ($this->_tpl_vars['result'] != null): ?>
<table width="850" border="1" cellspacing="0" cellpadding="0">
<thead>
  <tr>
    <td class="header-table4">Fecha</td>
    <td class="header-table4">NIT</td>
    <td class="header-table4">Raz&oacute;n Social </td>
    <td class="header-table4">Nro. Factura </td>
    <td class="header-table4">Autorizaci&oacute;n</td>
    <td class="header-table4">C&oacute;digo de control </td>
    <td class="header-table4">Importe</td>
    <td class="header-table4">ICE</td>
    <td class="header-table4">Exento</td>
    <td class="header-table4">Importe Neto </td>
    <td class="header-table4">Impuesto</td>
	<td class="header-table4">Periodo</td>
    <td class="header-table4">Gestion</td>
 <!--   <td>Estado </td>-->
    <td class="header-table4">Tipo</td>
    <td class="header-table4">Sucursal</td>
	<td class="header-table4"></td>
  </tr>
</thead>
<tbody>
<?php unset($this->_sections['idx']);
$this->_sections['idx']['name'] = 'idx';
$this->_sections['idx']['loop'] = is_array($_loop=$this->_tpl_vars['result']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['idx']['show'] = true;
$this->_sections['idx']['max'] = $this->_sections['idx']['loop'];
$this->_sections['idx']['step'] = 1;
$this->_sections['idx']['start'] = $this->_sections['idx']['step'] > 0 ? 0 : $this->_sections['idx']['loop']-1;
if ($this->_sections['idx']['show']) {
    $this->_sections['idx']['total'] = $this->_sections['idx']['loop'];
    if ($this->_sections['idx']['total'] == 0)
        $this->_sections['idx']['show'] = false;
} else
    $this->_sections['idx']['total'] = 0;
if ($this->_sections['idx']['show']):

            for ($this->_sections['idx']['index'] = $this->_sections['idx']['start'], $this->_sections['idx']['iteration'] = 1;
                 $this->_sections['idx']['iteration'] <= $this->_sections['idx']['total'];
                 $this->_sections['idx']['index'] += $this->_sections['idx']['step'], $this->_sections['idx']['iteration']++):
$this->_sections['idx']['rownum'] = $this->_sections['idx']['iteration'];
$this->_sections['idx']['index_prev'] = $this->_sections['idx']['index'] - $this->_sections['idx']['step'];
$this->_sections['idx']['index_next'] = $this->_sections['idx']['index'] + $this->_sections['idx']['step'];
$this->_sections['idx']['first']      = ($this->_sections['idx']['iteration'] == 1);
$this->_sections['idx']['last']       = ($this->_sections['idx']['iteration'] == $this->_sections['idx']['total']);
?>
<tr bgcolor="<?php echo smarty_function_cycle(array('values' => "#FFFFFF,#f0f0f0"), $this);?>
">
    <td class="obs-quotation" nowrap="nowrap"><?php echo $this->_tpl_vars['result'][$this->_sections['idx']['index']]['fecha']; ?>
&nbsp;</td>
    <td class="obs-quotation"><?php echo $this->_tpl_vars['result'][$this->_sections['idx']['index']]['nit']; ?>
&nbsp;</td>
    <td class="obs-quotation"><?php echo $this->_tpl_vars['result'][$this->_sections['idx']['index']]['razon_social']; ?>
&nbsp;</td>
    <td class="obs-quotation"><?php echo $this->_tpl_vars['result'][$this->_sections['idx']['index']]['factura']; ?>
&nbsp;</td>
    <td class="obs-quotation"><?php echo $this->_tpl_vars['result'][$this->_sections['idx']['index']]['autorizacion']; ?>
&nbsp;</td>
    <td class="obs-quotation"><?php echo $this->_tpl_vars['result'][$this->_sections['idx']['index']]['codigo_control']; ?>
&nbsp;</td>
    <td class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['result'][$this->_sections['idx']['index']]['total_facturado'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
&nbsp;</td>
    <td class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['result'][$this->_sections['idx']['index']]['ice'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
&nbsp;</td>
    <td class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['result'][$this->_sections['idx']['index']]['exento'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
&nbsp;</td>
    <td class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['result'][$this->_sections['idx']['index']]['importe'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
&nbsp;</td>
    <td class="obs-quotation"><?php echo ((is_array($_tmp=$this->_tpl_vars['result'][$this->_sections['idx']['index']]['credito_fiscal'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
&nbsp;</td>
	<td class="obs-quotation"><?php echo $this->_tpl_vars['result'][$this->_sections['idx']['index']]['periodo']; ?>
&nbsp;</td>
	<td class="obs-quotation"><?php echo $this->_tpl_vars['result'][$this->_sections['idx']['index']]['gestion']; ?>
&nbsp;</td>
<!--	<td>result[idx].estado}&nbsp;</td>-->
	<td class="obs-quotation"><?php echo $this->_tpl_vars['result'][$this->_sections['idx']['index']]['tipo']; ?>
&nbsp;</td>
    <td class="obs-quotation"><?php echo $this->_tpl_vars['result'][$this->_sections['idx']['index']]['sucursal_id']; ?>
&nbsp;</td>
	<form method="post" action="/sistema/macaws/editarFactura.php">
	<td class="obs-quotation">
	<input name="editar" type="submit" value="Editar" class="obs-quotation" />
	<input name="mcwtipofac" type="hidden" value="<?php echo $this->_tpl_vars['result'][$this->_sections['idx']['index']]['tipo']; ?>
" />
	<input name="macawscodpg" type="hidden" value="<?php echo $this->_tpl_vars['result'][$this->_sections['idx']['index']]['cod_pg']; ?>
" />
	<input name="mcwcodigofac" type="hidden" value="<?php echo $this->_tpl_vars['result'][$this->_sections['idx']['index']]['cod_compra']; ?>
<?php echo $this->_tpl_vars['result'][$this->_sections['idx']['index']]['cod_venta']; ?>
" />
	</td>
	</form>
  </tr>
<?php endfor; endif; ?>
</tbody>
</table>
<?php else: ?>
<table width="700" border="1" cellpadding="0" cellspacing="1" class="shadetabs">
  <tr>
    <td align="center" class="titles" >No hay resultados.</td>
  </tr>
</table>


<?php endif; ?>

</body>
</html>