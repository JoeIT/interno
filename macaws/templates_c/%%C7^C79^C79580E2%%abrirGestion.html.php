<?php /* Smarty version 2.6.18, created on 2007-07-16 17:36:22
         compiled from abrirGestion.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'abrirGestion.html', 101, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.html", 'smarty_include_vars' => array('title' => $this->_tpl_vars['title'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php echo '
<script type="text/javascript">
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

<br/>
<?php if ($this->_tpl_vars['gesopen'] < 2): ?>
<form name="formp" action="actions/action_abrirGestion.php" method="post">
  <table width="700" border="0" cellpadding="2" cellspacing="1" class="tableblue">
    <caption class="search-box" >
      Registrar 
      Gesti&oacute;n
    </caption>
	
    <tr>
      <td colspan="2" align="left" valign="top">
		<?php if ($this->_tpl_vars['reg_ok'] != null): ?>  
		  <table width="500" border="1" cellpadding="0" cellspacing="0" class="finish">
		<tr>
		<td align="center" width="500" >Se abrio la gesti&oacute;n con exito.</td>
		</tr>
		</table>

		<?php endif; ?>	  </td>     
    </tr>
	
	 <tr>
      <td colspan="2" >	  
	  <?php if ($this->_tpl_vars['error']['closedgestion'] != null): ?>
	    <table width="500" border="0" cellpadding="0" cellspacing="0" class="error-box">
		<tr>
		<td width="500"><?php echo $this->_tpl_vars['error']['closedgestion']; ?>
<br/>		</td>
		</tr>
		</table>
	  <?php endif; ?>	  </td>
    </tr>
	
    <tr>
      <td width="200" align="right" valign="top" nowrap="nowrap" class="styleText">Gestiones abiertas: </td>
      <td width="483" valign="top"> <?php if ($this->_tpl_vars['gestionesab'] != null): ?>
        <?php unset($this->_sections['ind2']);
$this->_sections['ind2']['name'] = 'ind2';
$this->_sections['ind2']['loop'] = is_array($_loop=$this->_tpl_vars['gestionesab']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['ind2']['show'] = true;
$this->_sections['ind2']['max'] = $this->_sections['ind2']['loop'];
$this->_sections['ind2']['step'] = 1;
$this->_sections['ind2']['start'] = $this->_sections['ind2']['step'] > 0 ? 0 : $this->_sections['ind2']['loop']-1;
if ($this->_sections['ind2']['show']) {
    $this->_sections['ind2']['total'] = $this->_sections['ind2']['loop'];
    if ($this->_sections['ind2']['total'] == 0)
        $this->_sections['ind2']['show'] = false;
} else
    $this->_sections['ind2']['total'] = 0;
if ($this->_sections['ind2']['show']):

            for ($this->_sections['ind2']['index'] = $this->_sections['ind2']['start'], $this->_sections['ind2']['iteration'] = 1;
                 $this->_sections['ind2']['iteration'] <= $this->_sections['ind2']['total'];
                 $this->_sections['ind2']['index'] += $this->_sections['ind2']['step'], $this->_sections['ind2']['iteration']++):
$this->_sections['ind2']['rownum'] = $this->_sections['ind2']['iteration'];
$this->_sections['ind2']['index_prev'] = $this->_sections['ind2']['index'] - $this->_sections['ind2']['step'];
$this->_sections['ind2']['index_next'] = $this->_sections['ind2']['index'] + $this->_sections['ind2']['step'];
$this->_sections['ind2']['first']      = ($this->_sections['ind2']['iteration'] == 1);
$this->_sections['ind2']['last']       = ($this->_sections['ind2']['iteration'] == $this->_sections['ind2']['total']);
?>
        <?php echo $this->_tpl_vars['gestionesab'][$this->_sections['ind2']['index']]['gestion']; ?>
<br/>
        <?php endfor; endif; ?>	
        <?php else: ?>
        No disponible.
        <?php endif; ?> </td>
    </tr>
    <tr>
      <td align="right" valign="top" nowrap="nowrap" class="styleText">Gesti&oacute;n a abrir:</td>
      <td><input name="gestion" type="text" class="text3" value="<?php echo $this->_tpl_vars['val']['gestion']; ?>
" size="5" maxlength="4" onkeypress="return enter_handle(this, event, 1, 0)"/>
      &nbsp;&nbsp;&nbsp;&nbsp;<label id="errorlabel"><?php echo $this->_tpl_vars['error']['gestion']; ?>
</label></td>
    </tr>
    <tr>
      <td align="right" valign="top" nowrap="nowrap" class="styleText">Observaci&oacute;n:</td>
      <td><textarea name="obs" cols="70" class="text3" onkeypress="return enter_handle(this, event, 1, 1)"><?php echo $this->_tpl_vars['val']['obs']; ?>
</textarea></td>
    </tr>
   
    <tr>
      <td align="right" valign="middle" width="40">&nbsp;</td>
      <td>	  
	  <input name="registrar" type="submit" class="text3" value="Registrar gestión" />
	  <input name="salir" type="button" class="text7" onclick="location='/sistema/macaws/lcv.php'" value="Cancelar" />
  	  </td>
    </tr>
	<tr>
      <td align="center" valign="top" colspan="2">
	  <?php if ($this->_tpl_vars['gestiones'] != null): ?>
	  <p><br />
		<table width="400" border="1" cellspacing="0" cellpadding="0">
			<tr>
			<td class="header-table3">Getiones Registrados</td>
			</tr>
			
			
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
			<tr bgcolor="<?php echo smarty_function_cycle(array('values' => "#FFFFFF,#f0f0f0"), $this);?>
">
			<td>
    		    Gestion: &nbsp;<?php echo $this->_tpl_vars['gestiones'][$this->_sections['ind']['index']]['gestion']; ?>
 &nbsp;&nbsp; 
				Estado:&nbsp;<?php if ($this->_tpl_vars['gestiones'][$this->_sections['ind']['index']]['estado'] == 1): ?>Abierta<?php else: ?>
				Cerrada<?php endif; ?>&nbsp;&nbsp;&nbsp;Obs:&nbsp;<?php echo $this->_tpl_vars['gestiones'][$this->_sections['ind']['index']]['obs']; ?>
 <br/>
			</td>
			</tr>
		    <?php endfor; endif; ?>
			
		</table>
		<br /></p>
      <?php else: ?>
      <span class="text6">No disponible. </span><?php endif; ?>	  </td>
    </tr>	
  </table>
</form>
<?php else: ?>
<table width="700" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" class="error-box" width="700">
	<p>
		NO PUEDE HABER MAS DE 2 GESTIONES ABIERTAS.<br/>
		CIERRE UNA PARA CONTINUAR.<br/>
		GRACIAS.<br/>
	</p>
	</td>
  </tr>
</table>

<?php endif; ?>

</body>
</html>