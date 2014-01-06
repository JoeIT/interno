<?php /* Smarty version 2.6.18, created on 2013-08-22 11:35:50
         compiled from sucursal.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.html", 'smarty_include_vars' => array('title' => $this->_tpl_vars['title'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<head>
<?php echo '
<script type="text/javascript">
function validar(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[0-9\\s-]/; // 4
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
} 
</script>

'; ?>

</head>
<body>

<form id="form1" name="form1" method="post" action="actions/action_registrarSucursal.php">

  <table width="553" border="0" cellspacing="2" cellpadding="0" align="center" class="tableblue">
    <tr>
      <td colspan="2" align="left" class="header-table">REGISTRO DE SUCURSAL</td>
    </tr>
    <tr>
      <td width="180" align="right" class="styleText">NIT:</td>
    <td width="367" nowrap="nowrap" >
    <select name="contribuyentes" class="text3">
    <?php unset($this->_sections['id']);
$this->_sections['id']['name'] = 'id';
$this->_sections['id']['loop'] = is_array($_loop=$this->_tpl_vars['contribuyentes']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
      <option value="<?php echo $this->_tpl_vars['contribuyentes'][$this->_sections['id']['index']]['cod_cont']; ?>
"><?php echo $this->_tpl_vars['contribuyentes'][$this->_sections['id']['index']]['nit']; ?>
</option>
    <?php endfor; endif; ?>
    </select> 
     </td>
    </tr>
    <tr>
    <td align="right" class="styleText">Nombre Sucursal: </td>
      <td align="left">
	  <input name="nombre" type="text" value="<?php echo $this->_tpl_vars['nombre']; ?>
" size="25" maxlength="25" class="text3" onKeyPress="return enter_handle(this, event, 1, 0)"/>
      <label id="errorlabel"><?php echo $this->_tpl_vars['error']['nombre']; ?>
 </label></td>
    </tr>
    <tr>
      <td align="right" class="styleText">Direcci&oacute;n: </td>
      <td align="left">
	  <input name="direccion" type="text" value="<?php echo $this->_tpl_vars['direccion']; ?>
" size="25" maxlength="25" class="text3" onKeyPress="return enter_handle(this, event, 1, 0)"/>
      <label id="errorlabel"><?php echo $this->_tpl_vars['error']['direccion']; ?>
 </label></td>
    </tr>
    <tr>
      <td align="right" class="styleText">Telefono: </td>
      <td align="left"><input name="telefono" value="<?php echo $this->_tpl_vars['telefono']; ?>
" type="text" size="25" maxlength="30" class="text3" onKeyPress="return validar(event)"/><label id="errorlabel"><?php echo $this->_tpl_vars['error']['telefono']; ?>
 </label></td>
    </tr>
    <tr>
      <td align="right" class="styleText">Casilla: </td>
      <td align="left"><input name="casilla" type="text"  value="<?php echo $this->_tpl_vars['casilla']; ?>
" size="25" maxlength="25" class="text3" onKeyPress="return enter_handle(this, event, 1, 1)"/></td>
    </tr>
    <tr>
      <td align="right" class="styleText">Fax: </td>
      <td align="left"><input name="fax" type="text" value="<?php echo $this->_tpl_vars['fax']; ?>
" size="25" maxlength="25" class="text3" onKeyPress="return enter_handle(this, event, 1, 1)"/></td>
    </tr>
    <tr>
    <tr>
      <td>&nbsp;</td>
      <td>
	  <input type="submit" name="registrar" value="Registrar Sucursal" class="text3"/>
	  <input type="reset" name="Reset" value="Limpiar campos" />
      <input type="button" name="cancelar" value="Cancelar" onClick="location='/sistema/macaws/lcv.php'" class="text7"/>	  </td>
    </tr>
  </table>
</form>
</body>
</html>