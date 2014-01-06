<?php /* Smarty version 2.6.18, created on 2013-10-07 12:33:20
         compiled from venta.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ajax_call', 'venta.html', 154, false),)), $this); ?>
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
function calcular(iva) {
  tf = form1.totalfact.value;
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
  form1.totalexento.value = (Math.round((tf-ice-exe)*1000000))/1000000;
  res = (tf-ice-exe)*(iva/100);
  res = res*1000000;
  res= Math.round(res);
  res= res/1000000;
  form1.iva.value = res; 
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

</head>
<body>
<?php echo '
<script type="text/javascript">
	var calculate =
	{
		params: function() {
			return {
				nit: $F("nit")
			}
		},
		cb: function(originalRequest) {								
			  $("rsocial").value = originalRequest.responseText;			
		}
	}	
</script>
'; ?>


<?php if ($this->_tpl_vars['gestionperiodo'] != null): ?>
<form id="form1" name="form1" method="post" action="actions/action_registrarVenta.php">

  <table width="1000" border="0" cellspacing="2" cellpadding="0" align="center" class="tableblue">
    <tr>
      <td colspan="2" align="left" class="header-table">REGISTRO DE VENTA </td>
    </tr>
	<tr>
      <td colspan="2" align="left" >
	  <h3><font color="#226C91">Periodo Fiscal: <?php echo $this->_tpl_vars['gestionperiodo']['periodo']; ?>
/<?php echo $this->_tpl_vars['gestionperiodo']['gestion']; ?>
</font></h3>	  </td>
    </tr>
    <tr>
      <td colspan="2" align="left" nowrap="nowrap">      
	  <?php if ($this->_tpl_vars['reg_ok'] != null): ?>
		<table width="700" border="1" cellpadding="0" cellspacing="0" class="finish">
		<tr>
		<td align="center" width="700" >Se registro la venta con exito, Nro. Factura: <?php echo $this->_tpl_vars['reg_ok']; ?>
</td>
		</tr>
		</table>
	  <?php endif; ?>
	  <?php if ($this->_tpl_vars['error'] != null): ?>
		<table width="700" border="1" cellpadding="0" cellspacing="0" class="error-box">
		<tr>
		<td align="center" width="700" >ERROR:<BR/>No se registro la factura.<br/><?php echo $this->_tpl_vars['error']['facturadoble']; ?>
</td>
		</tr>
		</table>
	  <?php endif; ?>	  </td>
    </tr>
    <tr>
      <td width="216" align="right" class="styleText">Fecha:</td>
      <td width="778" align="left">
	  <select name="diaperiodo" id="diaperiodo" tabindex="4" class="text3" onkeypress="return enter_handle(this, event, 1, 0)">
	  <option value="0" <?php if ($this->_tpl_vars['val']['diaperiodo'] == 0): ?>selected="selected" <?php endif; ?>>--</option>
	  <option value="1" <?php if ($this->_tpl_vars['val']['diaperiodo'] == 1): ?>selected="selected" <?php endif; ?>>01</option>
	  <option value="2" <?php if ($this->_tpl_vars['val']['diaperiodo'] == 2): ?>selected="selected" <?php endif; ?>>02</option>
	  <option value="3" <?php if ($this->_tpl_vars['val']['diaperiodo'] == 3): ?>selected="selected" <?php endif; ?>>03</option>
	  <option value="4" <?php if ($this->_tpl_vars['val']['diaperiodo'] == 4): ?>selected="selected" <?php endif; ?>>04</option>
	  <option value="5" <?php if ($this->_tpl_vars['val']['diaperiodo'] == 5): ?>selected="selected" <?php endif; ?>>05</option>
	  <option value="6" <?php if ($this->_tpl_vars['val']['diaperiodo'] == 6): ?>selected="selected" <?php endif; ?>>06</option>
	  <option value="7" <?php if ($this->_tpl_vars['val']['diaperiodo'] == 7): ?>selected="selected" <?php endif; ?>>07</option>
	  <option value="8" <?php if ($this->_tpl_vars['val']['diaperiodo'] == 8): ?>selected="selected" <?php endif; ?>>08</option>
	  <option value="9" <?php if ($this->_tpl_vars['val']['diaperiodo'] == 9): ?>selected="selected" <?php endif; ?>>09</option>
	  <option value="10" <?php if ($this->_tpl_vars['val']['diaperiodo'] == 10): ?>selected="selected" <?php endif; ?>>10</option>
	  <option value="11" <?php if ($this->_tpl_vars['val']['diaperiodo'] == 11): ?>selected="selected" <?php endif; ?>>11</option>
	  <option value="12" <?php if ($this->_tpl_vars['val']['diaperiodo'] == 12): ?>selected="selected" <?php endif; ?>>12</option>
	  <option value="13" <?php if ($this->_tpl_vars['val']['diaperiodo'] == 13): ?>selected="selected" <?php endif; ?>>13</option>
	  <option value="14" <?php if ($this->_tpl_vars['val']['diaperiodo'] == 14): ?>selected="selected" <?php endif; ?>>14</option>
	  <option value="15" <?php if ($this->_tpl_vars['val']['diaperiodo'] == 15): ?>selected="selected" <?php endif; ?>>15</option>
	  <option value="16" <?php if ($this->_tpl_vars['val']['diaperiodo'] == 16): ?>selected="selected" <?php endif; ?>>16</option>
	  <option value="17" <?php if ($this->_tpl_vars['val']['diaperiodo'] == 17): ?>selected="selected" <?php endif; ?>>17</option>
	  <option value="18" <?php if ($this->_tpl_vars['val']['diaperiodo'] == 18): ?>selected="selected" <?php endif; ?>>18</option>
	  <option value="19" <?php if ($this->_tpl_vars['val']['diaperiodo'] == 19): ?>selected="selected" <?php endif; ?>>19</option>
	  <option value="20" <?php if ($this->_tpl_vars['val']['diaperiodo'] == 20): ?>selected="selected" <?php endif; ?>>20</option>
	  <option value="21" <?php if ($this->_tpl_vars['val']['diaperiodo'] == 21): ?>selected="selected" <?php endif; ?>>21</option>
	  <option value="22" <?php if ($this->_tpl_vars['val']['diaperiodo'] == 22): ?>selected="selected" <?php endif; ?>>22</option>
	  <option value="23" <?php if ($this->_tpl_vars['val']['diaperiodo'] == 23): ?>selected="selected" <?php endif; ?>>23</option>
	  <option value="24" <?php if ($this->_tpl_vars['val']['diaperiodo'] == 24): ?>selected="selected" <?php endif; ?>>24</option>
	  <option value="25" <?php if ($this->_tpl_vars['val']['diaperiodo'] == 25): ?>selected="selected" <?php endif; ?>>25</option>
	  <option value="26" <?php if ($this->_tpl_vars['val']['diaperiodo'] == 26): ?>selected="selected" <?php endif; ?>>26</option>
	  <option value="27" <?php if ($this->_tpl_vars['val']['diaperiodo'] == 27): ?>selected="selected" <?php endif; ?>>27</option>
	  <option value="28" <?php if ($this->_tpl_vars['val']['diaperiodo'] == 28): ?>selected="selected" <?php endif; ?>>28</option>
	  <option value="29" <?php if ($this->_tpl_vars['val']['diaperiodo'] == 29): ?>selected="selected" <?php endif; ?>>29</option>
	  <option value="30" <?php if ($this->_tpl_vars['val']['diaperiodo'] == 30): ?>selected="selected" <?php endif; ?>>30</option>
	  <option value="31" <?php if ($this->_tpl_vars['val']['diaperiodo'] == 31): ?>selected="selected" <?php endif; ?>>31</option>
	  </select>/<?php echo $this->_tpl_vars['gestionperiodo']['periodo']; ?>
/<?php echo $this->_tpl_vars['gestionperiodo']['gestion']; ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label id="errorlabel"><?php echo $this->_tpl_vars['error']['diaperiodo']; ?>
 </label></td>
    </tr>
	<tr>
   <td align="right" class="styleText">Sucursal: </td>
   <td  nowrap="nowrap">&nbsp;&nbsp;<?php echo $this->_tpl_vars['sucursal_name']; ?>
</td>
    </tr>
	  <tr>
      <td align="right" class="styleText">Tipo de venta:</td>
      <td align="left" class="styleText"><input name="tipovent" type="radio" value="1" <?php if ($this->_tpl_vars['val']['tipovent'] == 1): ?> checked="checked"<?php endif; ?>>Manual <input name="tipovent" type="radio" value="2" <?php if ($this->_tpl_vars['val']['tipovent'] == 2): ?> checked="checked"<?php endif; ?>>Computarizada
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label id="errorlabel"><?php echo $this->_tpl_vars['error']['tipoventa']; ?>
 </label>
	  </td>
    </tr>
	
    <tr>
      <td align="right" class="styleText">NIT proveedor: </td>
      <td align="left">
	  <input name="nit" type="text" value="<?php echo $this->_tpl_vars['val']['nit']; ?>
" size="25" maxlength="25" 
	  onblur="<?php echo smarty_function_ajax_call(array('function' => 'cargarRazonSocial','params_func' => "calculate.params",'callback' => "calculate.cb"), $this);?>
" class="text3" onKeyPress="return enter_handle(this, event, 1, 0)"/>
      <label id="errorlabel"><?php echo $this->_tpl_vars['error']['nit']; ?>
 </label></td>
    </tr>
    <tr>
      <td align="right" class="styleText">Raz&oacute;n social: </td>
      <td align="left"><input name="rsocial" type="text" value="<?php echo $this->_tpl_vars['val']['rsocial']; ?>
" size="70" maxlength="50" class="text3" onKeyPress="return enter_handle(this, event, 1, 1)"/>
      <label id="errorlabel"><?php echo $this->_tpl_vars['error']['rsocial']; ?>
 </label></td>
    </tr>
    <tr>
      <td align="right" class="styleText">N&uacute;mero de factura: </td>
      <td align="left"><input name="factura" type="text" value="<?php echo $this->_tpl_vars['val']['factura']; ?>
" size="25" maxlength="25" class="text3" onKeyPress="return enter_handle(this, event, 1, 1)"/>
      <label id="errorlabel"><?php echo $this->_tpl_vars['error']['factura']; ?>
 <?php echo $this->_tpl_vars['error']['facturadoble']; ?>
</label></td>
    </tr>
    <tr>
      <td align="right" class="styleText">N&uacute;mero de autorizacion: </td>
      <td align="left"><input name="autorizacion" type="text" value="<?php echo $this->_tpl_vars['val']['autorizacion']; ?>
" size="25" maxlength="25" class="text3" onKeyPress="return enter_handle(this, event, 1, 1)"/>
      <label id="errorlabel"><?php echo $this->_tpl_vars['error']['autorizacion']; ?>
 </label></td>
    </tr>
    <tr>
      <td align="right" class="styleText">C&oacute;digo de control: </td>
      <td align="left"><input name="control" type="text" value="<?php echo $this->_tpl_vars['val']['control']; ?>
" size="25" maxlength="25" class="text3" onKeyPress="return enter_handle(this, event, 1, 1)"/>
      <label id="errorlabel"><?php echo $this->_tpl_vars['error']['control']; ?>
 </label></td>
    </tr>
    <tr>
      <td align="right" class="styleText">TOTAL FACTURA: </td>
      <td align="left"><input name="totalfact" type="text" value="<?php echo $this->_tpl_vars['val']['totalfact']; ?>
" size="13" maxlength="10" onBlur="calcular(<?php echo $this->_tpl_vars['gestionperiodo']['iva']; ?>
)" class="text3" onKeyPress="return enter_handle(this, event, 1, 1)"/>
      <label id="errorlabel"><?php echo $this->_tpl_vars['error']['totalfact']; ?>
 </label></td>
    </tr>
    <tr>
      <td align="right" class="styleText">Total ICE:</td>
      <td align="left"><input name="ice" type="text" value="<?php echo $this->_tpl_vars['val']['ice']; ?>
" size="13" maxlength="10" onBlur="calcular(<?php echo $this->_tpl_vars['gestionperiodo']['iva']; ?>
)" class="text3" onKeyPress="return enter_handle(this, event, 1, 1)"/>
      <label id="errorlabel"><?php echo $this->_tpl_vars['error']['ice']; ?>
 </label></td>
    </tr>
    <tr>
      <td align="right" class="styleText">Importe exento: </td>
      <td align="left"><input name="exento" type="text" value="<?php echo $this->_tpl_vars['val']['exento']; ?>
" size="13" maxlength="10" onBlur="calcular(<?php echo $this->_tpl_vars['gestionperiodo']['iva']; ?>
)" class="text3" onKeyPress="return enter_handle(this, event, 6, 1)"/>
      <label id="errorlabel"><?php echo $this->_tpl_vars['error']['exento']; ?>
 </label></td>
    </tr>
    <tr>
      <td align="right" class="styleText">&nbsp;</td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td align="right" class="styleText">Importe NETO Factura:</td>
      <td align="left"><input name="totalexento" type="text" value="<?php echo $this->_tpl_vars['val']['totalexento']; ?>
" size="13" maxlength="13" readonly="true" class="text3" /></td>
    </tr>
    <tr>
      <td align="right" class="styleText">Impuesto D&eacute;bito Fiscal:</td>
      <td align="left"><input name="iva" type="text" value="<?php echo $this->_tpl_vars['val']['iva']; ?>
" size="13" maxlength="10" readonly="true" class="text3"/>
      (IVA de <?php echo $this->_tpl_vars['gestionperiodo']['iva']; ?>
%)</td>
    </tr>
  
    <tr>
      <td>&nbsp;</td>
      <td align="left">
	  <input type="hidden" name="cod_pg" value="<?php echo $this->_tpl_vars['gestionperiodo']['cod_pg']; ?>
" />
	  <input type="hidden" name="periodo" value="<?php echo $this->_tpl_vars['gestionperiodo']['periodo']; ?>
" />
	  <input type="hidden" name="gestion" value="<?php echo $this->_tpl_vars['gestionperiodo']['gestion']; ?>
" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>
	  <input type="submit" name="registrar" value="Registrar venta" class="text3"/>
	  <input type="reset" name="Reset" value="Limpiar campos" />
     <?php if ($this->_tpl_vars['id'] == 1): ?>
      <input type="button" name="cancelar" value="Cancelar" onClick="location='/sistema/macaws/lcv.php'" class="text7"/>
      <?php else: ?>	<input type="button" name="cancelar" value="Cancelar" onClick="location='/sistema/macaws/generalcv.php'" class="text7"/> <?php endif; ?> </td>
    </tr>
  </table>
</form>
<?php else: ?>
<table width="700" border="0" cellpadding="2" cellspacing="1" class="error-box">
	<tr>
	<td>ERROR: EL PERIODO SELECCIONADO ESTA CERRADO.</td>
	</tr>
	<tr>
	<td width="700" >
    <?php if ($this->_tpl_vars['id'] == 1): ?>
    <input name="volver" type="button" class="text3" onClick="location='/sistema/macaws/lcv.php'" value="Volver" />
    <?php else: ?>
    <input name="volver" type="button" class="text3" onClick="location='/sistema/macaws/generalcv.php'" value="Volver" />
    <?php endif; ?>
    </td>
	</tr>
</table>
<?php endif; ?>
</body>
</html>