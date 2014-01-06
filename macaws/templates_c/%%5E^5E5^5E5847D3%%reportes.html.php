<?php /* Smarty version 2.6.18, created on 2011-04-29 12:47:44
         compiled from reportes.html */ ?>
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
<form method="post" action="actions/action_reportes.php">
<table width="400" border="0" cellspacing="1" cellpadding="0">
  <tr>
  <td width="31" class="text3"></td>
    <td width="46" nowrap="nowrap" class="header-table">Gesti&oacute;n</td>
    <td width="100" nowrap="nowrap" class="header-table"><select name="gestion" class="text3">
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
"<?php if ($this->_tpl_vars['val']['gestion'] == $this->_tpl_vars['gestiones'][$this->_sections['ind']['index']]['gestion']): ?> selected="selected" <?php endif; ?>><?php echo $this->_tpl_vars['gestiones'][$this->_sections['ind']['index']]['gestion']; ?>
</option>
	<?php endfor; endif; ?>
    </select>    </td>
    <td width="50"  nowrap="nowrap" class="header-table">Periodo</td>
    <td  nowrap="nowrap" class="header-table">
	<select name="periodo" class="text3">		
		<option value="1" <?php if ($this->_tpl_vars['val']['periodo'] == 1): ?> selected="selected"<?php endif; ?>>01</option>
		<option value="2" <?php if ($this->_tpl_vars['val']['periodo'] == 2): ?> selected="selected"<?php endif; ?>>02</option>
		<option value="3" <?php if ($this->_tpl_vars['val']['periodo'] == 3): ?> selected="selected"<?php endif; ?>>03</option>
		<option value="4" <?php if ($this->_tpl_vars['val']['periodo'] == 4): ?> selected="selected"<?php endif; ?>>04</option>
		<option value="5" <?php if ($this->_tpl_vars['val']['periodo'] == 5): ?> selected="selected"<?php endif; ?>>05</option>
		<option value="6" <?php if ($this->_tpl_vars['val']['periodo'] == 6): ?> selected="selected"<?php endif; ?>>06</option>
		<option value="7" <?php if ($this->_tpl_vars['val']['periodo'] == 7): ?> selected="selected"<?php endif; ?>>07</option>
		<option value="8" <?php if ($this->_tpl_vars['val']['periodo'] == 8): ?> selected="selected"<?php endif; ?>>08</option>
		<option value="9" <?php if ($this->_tpl_vars['val']['periodo'] == 9): ?> selected="selected"<?php endif; ?>>09</option>
		<option value="10" <?php if ($this->_tpl_vars['val']['periodo'] == 10): ?> selected="selected"<?php endif; ?>>10</option>
		<option value="11" <?php if ($this->_tpl_vars['val']['periodo'] == 11): ?> selected="selected"<?php endif; ?>>11</option>
		<option value="12" <?php if ($this->_tpl_vars['val']['periodo'] == 12): ?> selected="selected"<?php endif; ?>>12</option>
		</select>	</td>
    <td width="65" class="header-table"><input name="generar" id="generar" type="submit" class="text4" value="Generar" /></td>
  </tr>
  <tr>
  <td></td>
  <td colspan="2" class="header-table" nowrap="nowrap">Registros por hoja: <input name="intervalo" type="text" size="3" maxlength="3"  class="text3" value="<?php if ($this->_tpl_vars['val']['intervalo'] != null): ?><?php echo $this->_tpl_vars['val']['intervalo']; ?>
<?php else: ?>30<?php endif; ?>"/></td>
  
  <?php if ($this->_tpl_vars['id'] == 1): ?>
  <td class="header-table">Sucursal</td>
    <td class="header-table">
    <select name="sucursales" class="text3">
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

     <td class="header-table" nowrap="nowrap"><?php echo $this->_tpl_vars['sucursal_name']; ?>
</td>
    <?php endif; ?>   
  </tr>
  <tr>
  	<td>&nbsp;</td>
    <td colspan="5" align="center" class="header-table2">
	<table width="248" cellpadding="0" cellspacing="0" border="0">
	<tr>
	<td width="248" align="left" nowrap="nowrap" class="text3">
	<input name="tipo" type="radio" value="0" <?php if ($this->_tpl_vars['val']['tipo'] == 0): ?> checked="checked" <?php endif; ?>/>
	Libro de compras IVA </td>
	</tr>
	<tr>
	  <td align="left" nowrap="nowrap" class="text2">
		<table width="0" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td nowrap="nowrap"><input name="alfanumerico" id="alfanumerico" type="checkbox" value="2" <?php if ($this->_tpl_vars['val']['alfanumerico'] == 2): ?> checked="checked" <?php endif; ?> />
		Con N&deg; Alfanumerico.</td>
		</tr>
		</table>
	  </td>
	  </tr>
	<tr>
    <td width="248" align="left" nowrap="nowrap" class="text3">
	<input name="tipo" type="radio" value="1" <?php if ($this->_tpl_vars['val']['tipo'] == 1): ?> checked="checked" <?php endif; ?> />
	Libro de ventas IVA	</td>
	</tr>
	<tr>
	  <td align="left" nowrap="nowrap" class="text2">
	  <table width="0" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
    <td nowrap="nowrap"><input name="tipoventa" type="radio" value="1" <?php if ($this->_tpl_vars['val']['tipoventa'] == 1 || $this->_tpl_vars['val']['tipoventa'] == null): ?> checked="checked" <?php endif; ?>/>Todas las ventas.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td nowrap="nowrap"><input name="tipoventa" type="radio" value="2" <?php if ($this->_tpl_vars['val']['tipoventa'] == 2): ?> checked="checked" <?php endif; ?>/>Ventas Manuales.</td>
  </tr>
  <tr>
    <td><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p></td>
    <td nowrap="nowrap"><input name="tipoventa" type="radio" value="3" <?php if ($this->_tpl_vars['val']['tipoventa'] == 3): ?> checked="checked" <?php endif; ?>/>Ventas computarizadas.</td>
  </tr>
    <?php if ($this->_tpl_vars['id'] == 1): ?>
  <tr>
    <td colspan="2" class="titles">Reportes Da VINCI </td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td nowrap="nowrap">
	<input name="tipo" type="radio" value="2" <?php if ($this->_tpl_vars['val']['tipo'] == 2): ?> checked="checked" <?php endif; ?>/>
	LCV compras Da VINCI	</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td nowrap="nowrap">
	<input name="tipo" type="radio" value="3" <?php if ($this->_tpl_vars['val']['tipo'] == 3): ?> checked="checked" <?php endif; ?>/>
	LCV ventas Da VINCI	</td>
  </tr>
  <tr>
    <td colspan="2" class="titles">Reportes SDI </td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td nowrap="nowrap">
	<input name="tipo" type="radio" value="4" <?php if ($this->_tpl_vars['val']['tipo'] == 4): ?> checked="checked" <?php endif; ?>/>
      SDI DUI </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td nowrap="nowrap">
	<input name="tipo" type="radio" value="5" <?php if ($this->_tpl_vars['val']['tipo'] == 5): ?> checked="checked" <?php endif; ?>/> 
      SDI FACT </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td nowrap="nowrap">&nbsp;</td>
  </tr>
  <tr>
    <?php endif; ?>
  <td></td>
  <td >
  Fecha<br>
  <input name="fechactual" id="fechactual" type="text" size="50" maxlength="100" value="<?php echo $this->_tpl_vars['fechactual']; ?>
" class="text2"/>  </td>
  </tr>
</table>	  </td>
	  </tr>
	</table>
	<input type="hidden" name="genrepcv" value="reportecv" /></td>
	</tr>
</table>
</form>

<?php if ($this->_tpl_vars['paginacion'] != null): ?>
<table width="800" border="0" cellspacing="0" cellpadding="0"  class="tableblue">
<caption class="styleText" style="background-color: #E0E0E0" >RESULTADO ORDENADO EN PAGINAS PARA SU IMPRESION
</caption>
  <tr>
  <?php unset($this->_sections['ind']);
$this->_sections['ind']['name'] = 'ind';
$this->_sections['ind']['loop'] = is_array($_loop=$this->_tpl_vars['paginacion']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
    <td align="center" width="200">
	<a href="actions/action_reportes.php?<?php echo $this->_tpl_vars['paginacion'][$this->_sections['ind']['index']]['indice']; ?>
&fechactual=<?php echo $this->_tpl_vars['fechactual']; ?>
" target="_blank" class="link-light-blue-11">Pagina N&deg;<?php echo $this->_sections['ind']['index']+1; ?>
</a>	</td>
  <?php endfor; endif; ?>
  </tr>
</table>
<?php endif; ?>

<?php if ($this->_tpl_vars['error']['periodogestion'] != null): ?>
<table width="800" border="1" cellspacing="1" cellpadding="0"  class="error-box">
  <tr>
    <td align="center"><?php echo $this->_tpl_vars['error']['periodogestion']; ?>
</td>
  </tr>
</table>
<?php endif; ?>
</body>
</html>