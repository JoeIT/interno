<?php /* Smarty version 2.6.18, created on 2013-10-08 14:59:53
         compiled from editarFactura.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'editarFactura.html', 70, false),array('function', 'ajax_call', 'editarFactura.html', 176, false),)), $this); ?>
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

<?php echo '
<script type="text/javascript">
// Función sobre aviso 
function calcular(iva) {
  tf = form1.total_facturado.value;
  ice = form1.ice.value;
  exe = form1.exento.value;
  if (tf==\'\' || isNaN(tf)) tf = 0;
  if (ice==\'\' || isNaN(ice)) ice = 0;
  if (exe==\'\' || isNaN(exe)) exe = 0;
  if (iva==\'\' || isNaN(iva)) iva = 0;
  tf = parseFloat(tf);
  ice = parseFloat(ice);
  exe = parseFloat(exe);  
  iva = parseFloat(iva);  
  form1.importe.value = (Math.round((tf-ice-exe)*1000000))/1000000;
  res = (tf-ice-exe)*(iva/100);
  res = res*1000000;
  res= Math.round(res);
  res= res/1000000;
  form1.credito_fiscal.value = res; 
}

function confirmActions(message)
{ 
	var conf = confirm(message); 
    if (conf) 		
       return true;
	else
	   return false; 
} 

function enter_handle (field, event, salto, tipo) {
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
	if (keyCode == 13) {
		var i;
		for (i = 0; i < field.form.elements.length; i++)
			if (field == field.form.elements[i])
				break;
		
		i = (i + salto) % field.form.elements.length;
		if (field.form.elements[i] != null) {
			field.form.elements[i].focus();
			field.form.elements[i].select();
			return false;
		}
	} else
		if (tipo == 0)//validar numeros
			if (keyCode < 48 || keyCode > 57)
				return false;
			else
				return true;
		else
			return true;
}

</script>
'; ?>


<?php if ($this->_tpl_vars['errorperiodo'] == null): ?>
<form name="form1" method="post" action="actions/action_editarFactura.php">
  <table width="1000" border="0" align="center" cellpadding="0" cellspacing="2" class="tableblue">
    <tr>
      <td colspan="2" align="left" class="header-table">EDITAR <?php echo ((is_array($_tmp=$this->_tpl_vars['tipo'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
 </td>
    </tr>
	<tr>
      <td colspan="2" align="left" >
	  <h3><font color="#226C91">Periodo Fiscal: <?php echo $this->_tpl_vars['val']['periodo']; ?>
/<?php echo $this->_tpl_vars['val']['gestion']; ?>
</font></h3>	  </td>
    </tr>
    <tr>
      <td colspan="2" align="left" nowrap="nowrap">      
	  <?php if ($this->_tpl_vars['reg_ok'] != null): ?>
		<table width="700" border="1" cellpadding="0" cellspacing="0" class="finish">
		<tr>
		<td align="center" width="700" >Se actualizo  con exito, Nro. de Factura: <?php echo $this->_tpl_vars['reg_ok']; ?>
 </td>
		</tr>
		</table>
	  <?php endif; ?>
	  <?php if ($this->_tpl_vars['error'] != null): ?>
		<table width="700" border="1" cellpadding="0" cellspacing="0" class="error-box">
		<tr>
		<td align="center" width="700" >ERROR:<BR/>
		  No se modifico la factura.<br/><?php echo $this->_tpl_vars['error']['facturadoble']; ?>
</td>
		</tr>
		</table>
	  <?php endif; ?>	  </td>
    </tr>
    <tr>
      <td width="216" align="right" class="styleText">Fecha:</td>
      <td width="778" align="left">
	  <select name="fecha" id="fecha" tabindex="4" class="text3" onkeypress="return enter_handle(this, event, 1, 0)">
	  <option value="0" <?php if ($this->_tpl_vars['val']['fecha'] == 0): ?>selected="selected" <?php endif; ?>>--</option>
	  <option value="1" <?php if ($this->_tpl_vars['val']['fecha'] == 1): ?>selected="selected" <?php endif; ?>>01</option>
	  <option value="2" <?php if ($this->_tpl_vars['val']['fecha'] == 2): ?>selected="selected" <?php endif; ?>>02</option>
	  <option value="3" <?php if ($this->_tpl_vars['val']['fecha'] == 3): ?>selected="selected" <?php endif; ?>>03</option>
	  <option value="4" <?php if ($this->_tpl_vars['val']['fecha'] == 4): ?>selected="selected" <?php endif; ?>>04</option>
	  <option value="5" <?php if ($this->_tpl_vars['val']['fecha'] == 5): ?>selected="selected" <?php endif; ?>>05</option>
	  <option value="6" <?php if ($this->_tpl_vars['val']['fecha'] == 6): ?>selected="selected" <?php endif; ?>>06</option>
	  <option value="7" <?php if ($this->_tpl_vars['val']['fecha'] == 7): ?>selected="selected" <?php endif; ?>>07</option>
	  <option value="8" <?php if ($this->_tpl_vars['val']['fecha'] == 8): ?>selected="selected" <?php endif; ?>>08</option>
	  <option value="9" <?php if ($this->_tpl_vars['val']['fecha'] == 9): ?>selected="selected" <?php endif; ?>>09</option>
	  <option value="10" <?php if ($this->_tpl_vars['val']['fecha'] == 10): ?>selected="selected" <?php endif; ?>>10</option>
	  <option value="11" <?php if ($this->_tpl_vars['val']['fecha'] == 11): ?>selected="selected" <?php endif; ?>>11</option>
	  <option value="12" <?php if ($this->_tpl_vars['val']['fecha'] == 12): ?>selected="selected" <?php endif; ?>>12</option>
	  <option value="13" <?php if ($this->_tpl_vars['val']['fecha'] == 13): ?>selected="selected" <?php endif; ?>>13</option>
	  <option value="14" <?php if ($this->_tpl_vars['val']['fecha'] == 14): ?>selected="selected" <?php endif; ?>>14</option>
	  <option value="15" <?php if ($this->_tpl_vars['val']['fecha'] == 15): ?>selected="selected" <?php endif; ?>>15</option>
	  <option value="16" <?php if ($this->_tpl_vars['val']['fecha'] == 16): ?>selected="selected" <?php endif; ?>>16</option>
	  <option value="17" <?php if ($this->_tpl_vars['val']['fecha'] == 17): ?>selected="selected" <?php endif; ?>>17</option>
	  <option value="18" <?php if ($this->_tpl_vars['val']['fecha'] == 18): ?>selected="selected" <?php endif; ?>>18</option>
	  <option value="19" <?php if ($this->_tpl_vars['val']['fecha'] == 19): ?>selected="selected" <?php endif; ?>>19</option>
	  <option value="20" <?php if ($this->_tpl_vars['val']['fecha'] == 20): ?>selected="selected" <?php endif; ?>>20</option>
	  <option value="21" <?php if ($this->_tpl_vars['val']['fecha'] == 21): ?>selected="selected" <?php endif; ?>>21</option>
	  <option value="22" <?php if ($this->_tpl_vars['val']['fecha'] == 22): ?>selected="selected" <?php endif; ?>>22</option>
	  <option value="23" <?php if ($this->_tpl_vars['val']['fecha'] == 23): ?>selected="selected" <?php endif; ?>>23</option>
	  <option value="24" <?php if ($this->_tpl_vars['val']['fecha'] == 24): ?>selected="selected" <?php endif; ?>>24</option>
	  <option value="25" <?php if ($this->_tpl_vars['val']['fecha'] == 25): ?>selected="selected" <?php endif; ?>>25</option>
	  <option value="26" <?php if ($this->_tpl_vars['val']['fecha'] == 26): ?>selected="selected" <?php endif; ?>>26</option>
	  <option value="27" <?php if ($this->_tpl_vars['val']['fecha'] == 27): ?>selected="selected" <?php endif; ?>>27</option>
	  <option value="28" <?php if ($this->_tpl_vars['val']['fecha'] == 28): ?>selected="selected" <?php endif; ?>>28</option>
	  <option value="29" <?php if ($this->_tpl_vars['val']['fecha'] == 29): ?>selected="selected" <?php endif; ?>>29</option>
	  <option value="30" <?php if ($this->_tpl_vars['val']['fecha'] == 30): ?>selected="selected" <?php endif; ?>>30</option>
	  <option value="31" <?php if ($this->_tpl_vars['val']['fecha'] == 31): ?>selected="selected" <?php endif; ?>>31</option>
	  </select>/<?php echo $this->_tpl_vars['val']['periodo']; ?>

	<!--  <select name="periodo" id="periodo">
	  <option value="1" if $val.periodo eq 01} selected="selected"/if}>01</option>
	  <option value="2" if $val.periodo eq 02} selected="selected"/if}>02</option>
	  <option value="3" if $val.periodo eq 03} selected="selected"/if}>03</option>
	  <option value="4" if $val.periodo eq 04} selected="selected"/if}>04</option>
	  <option value="5" if $val.periodo eq 05} selected="selected"/if}>05</option>
	  <option value="6" if $val.periodo eq 06} selected="selected"/if}>06</option>
	  <option value="7" if $val.periodo eq 07} selected="selected"/if}>07</option>
	  <option value="8" if $val.periodo eq 08} selected="selected"/if}>08</option>
	  <option value="9" if $val.periodo eq 09} selected="selected"/if}>09</option>
	  <option value="10" if $val.periodo eq 10} selected="selected"/if}>10</option>
	  <option value="11" if $val.periodo eq 11} selected="selected"/if}>11</option>
	  <option value="12" if $val.periodo eq 12} selected="selected"/if}>12</option>
	  </select>	  
	  -->
	  
	  
	  /<?php echo $this->_tpl_vars['val']['gestion']; ?>

	  <label id="errorlabel">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['error']['diaperiodo']; ?>
 </label></td>
    </tr>
    <tr>
          <td align="right" class="styleText">Sucursal: </td>
          <!--<td align="left" class="styleText">
    <select name="sucursal" class="text3">
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
" <?php if ($this->_tpl_vars['sucursales'][$this->_sections['id']['index']]['cod_sucursal']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['sucursales'][$this->_sections['id']['index']]['sucursal']; ?>
</option>
    <?php endfor; endif; ?>
    </select> 
          </td>-->
		  <?php if ($this->_tpl_vars['id'] == 1): ?>
          <td align="left" class="styleText">
    <select name="sucursal" class="text3">
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
" <?php if ($this->_tpl_vars['sucursales'][$this->_sections['id']['index']]['cod_sucursal'] == $this->_tpl_vars['id']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['sucursales'][$this->_sections['id']['index']]['sucursal']; ?>
</option>
    <?php endfor; endif; ?>
    </select> 
          </td>
          <?php else: ?>
          <td class="header-table" nowrap="nowrap"><?php echo $this->_tpl_vars['sucursal_name']; ?>
</td>
          <?php endif; ?>
    </tr>
    <tr>
      <td align="right" class="styleText">NIT proveedor: </td>
      <td align="left">
	  <input name="nit" id="nit" type="text" value="<?php echo $this->_tpl_vars['val']['nit']; ?>
" size="25" maxlength="25"  class="text3"
	  onblur="<?php echo smarty_function_ajax_call(array('function' => 'cargarRazonSocial','params_func' => "calculate.params",'callback' => "calculate.cb"), $this);?>
" 
	  onkeypress="return enter_handle(this, event, 1, 0)"/>
      <label id="errorlabel"><?php echo $this->_tpl_vars['error']['nit']; ?>
 </label>	  </td>
    </tr>
	<?php if ($this->_tpl_vars['tipo'] == Venta): ?>
	<tr>
	<td align="right" class="styleText">Tipo de venta:</td>
	<td>
	<input name="tipovent" type="radio" value="1" <?php if ($this->_tpl_vars['val']['tipovent'] == 1): ?> checked="checked"<?php endif; ?> onKeyPress="return enter_handle(this, event, 1, 1)">Manual 
	<input name="tipovent" type="radio" value="2" <?php if ($this->_tpl_vars['val']['tipovent'] == 2): ?> checked="checked"<?php endif; ?> onKeyPress="return enter_handle(this, event, 1, 0)">Computarizada
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label id="errorlabel"><?php echo $this->_tpl_vars['error']['tipoventa']; ?>
 </label>	</td>
	</tr>
	<?php endif; ?>
    <tr>
      <td align="right" class="styleText">Raz&oacute;n social: </td>
      <td align="left">	  
	  <input name="razon_social" id="razon_social" type="text" value="<?php echo $this->_tpl_vars['val']['razon_social']; ?>
" size="70" maxlength="50" class="text3" 
	  onkeypress="return enter_handle(this, event, 1, 1)"/>
      <label id="errorlabel"><?php echo $this->_tpl_vars['error']['rsocial']; ?>
 </label></td>
    </tr>
    <tr>
      <td align="right" class="styleText">N&uacute;mero de factura: </td>
      <td align="left"><input name="factura" type="text" class="text3" id="factura" value="<?php echo $this->_tpl_vars['val']['factura']; ?>
" size="25" maxlength="25" onKeyPress="return enter_handle(this, event, 1, 1)"/>
      <label id="errorlabel"><?php echo $this->_tpl_vars['error']['factura']; ?>
 <?php echo $this->_tpl_vars['error']['facturadoble']; ?>
 </label></td>
    </tr>
    <tr>
      <td align="right" class="styleText">N&uacute;mero de autorizacion: </td>
      <td align="left"><input name="autorizacion" type="text" class="text3" id="autorizacion" value="<?php echo $this->_tpl_vars['val']['autorizacion']; ?>
" size="25" maxlength="25" onKeyPress="return enter_handle(this, event, 1, 1)"/>
      <label id="errorlabel"><?php echo $this->_tpl_vars['error']['autorizacion']; ?>
 </label></td>
    </tr>
    <tr>
      <td align="right" class="styleText">C&oacute;digo de control: </td>
      <td align="left"><input name="codigo_control" type="text" class="text3" id="codigo_control" value="<?php echo $this->_tpl_vars['val']['codigo_control']; ?>
" size="25" maxlength="25" onKeyPress="return enter_handle(this, event, 1, 1)"/>
      <label id="errorlabel"><?php echo $this->_tpl_vars['error']['control']; ?>
 </label></td>
    </tr>
    <tr>
      <td align="right" class="styleText">TOTAL FACTURA: </td>
      <td align="left"><input name="total_facturado" type="text" class="text3" id="total_facturado" 
	  onblur="calcular(<?php echo $this->_tpl_vars['gestionperiodo']['iva']; ?>
)" value="<?php echo $this->_tpl_vars['val']['total_facturado']; ?>
" size="13" maxlength="10" onKeyPress="return enter_handle(this, event, 1, 1)"/>
      <label id="errorlabel"><?php echo $this->_tpl_vars['error']['totalfact']; ?>
 </label></td>
    </tr>
    <tr>
      <td align="right" class="styleText">Total ICE:</td>
      <td align="left"><input name="ice" type="text" class="text3" id="ice" 
	  onblur="calcular(<?php echo $this->_tpl_vars['gestionperiodo']['iva']; ?>
)" value="<?php echo $this->_tpl_vars['val']['ice']; ?>
" size="13" maxlength="10" onKeyPress="return enter_handle(this, event, 1, 1)"/>
      <label id="errorlabel"><?php echo $this->_tpl_vars['error']['ice']; ?>
 </label></td>
    </tr>
    <tr>
      <td align="right" class="styleText">Importe exento: </td>
      <td align="left"><input name="exento" type="text" class="text3" id="exento" 
	  onblur="calcular(<?php echo $this->_tpl_vars['gestionperiodo']['iva']; ?>
)" value="<?php echo $this->_tpl_vars['val']['exento']; ?>
" size="13" maxlength="10" onKeyPress="return enter_handle(this, event, 8, 1)"/>
      <label id="errorlabel"><?php echo $this->_tpl_vars['error']['exento']; ?>
 </label></td>
    </tr>
    <tr>
      <td align="right" class="styleText">&nbsp;</td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td align="right" class="styleText">Importe NETO factura:</td>
      <td align="left">
	  <input name="importe" type="text" class="text3" id="importe" value="<?php echo $this->_tpl_vars['val']['importe']; ?>
" size="13" maxlength="13" readonly="true" /></td>
    </tr>
    <tr>
      <td align="right" class="styleText">Impuesto Cr&eacute;dito Fiscal:</td>
      <td align="left">
	  <input name="credito_fiscal" type="text" class="text3" id="credito_fiscal" value="<?php echo $this->_tpl_vars['val']['credito_fiscal']; ?>
" size="13" maxlength="10" readonly="true" />
      (IVA de <?php echo $this->_tpl_vars['gestionperiodo']['iva']; ?>
%)</td>
    </tr>
    <tr>
      <td class="styleText">&nbsp;</td>
      <td align="left">
	  <input type="hidden" name="cod_pg" value="<?php echo $this->_tpl_vars['cod_pg']; ?>
" />
	  <input type="hidden" name="periodo" value="<?php echo $this->_tpl_vars['val']['periodo']; ?>
" />
	  <input type="hidden" name="gestion" value="<?php echo $this->_tpl_vars['val']['gestion']; ?>
" />	  
	  <input type="hidden" name="tipo" value="<?php echo $this->_tpl_vars['tipo']; ?>
" />
	  <input type="hidden" name="codfac" value="<?php echo $this->_tpl_vars['codfac']; ?>
" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>
	  <input name="update" type="submit" class="text3" id="update" value="Guardar cambios" onclick="return confirmActions('Cualquier cambio hecho se guardara.¿Desea continuar? ');"/>
	  <input type="submit" name="eliminar" class="text3" id="eliminar" value="Eliminar" onclick="return confirmActions('¿Esta seguro en ELIMINAR la factura? ');">
	  <input type="button" name="cancelar" value="Cancelar" onClick="location='/sistema/macaws/reportesLcv.php'" class="text7"/></td>
    </tr>
  </table>
</form>
<?php else: ?>
<br/>
<br/>
<table width="700" border="0" cellpadding="2" cellspacing="1" class="error-box">
	<tr>
	<td width="700" >ERROR: <br />
	  <?php echo $this->_tpl_vars['errorperiodo']; ?>
</td>
	</tr>
    <tr>
    <td width="700" ><input type="button" name="volver" value="Volver" onClick="location='/sistema/macaws/reportesLcv.php'" class="text3"/></td>
    </tr>
</table>
<?php endif; ?>

</body>
</html>