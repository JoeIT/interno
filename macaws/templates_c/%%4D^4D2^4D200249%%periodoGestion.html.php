<?php /* Smarty version 2.6.18, created on 2007-07-17 13:26:05
         compiled from periodoGestion.html */ ?>
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
<?php if ($this->_tpl_vars['perdis'] != null): ?>
<?php if ($this->_tpl_vars['peropen'] < 2): ?>
<form name="formp" action="actions/action_registrarPeriodo.php" method="post">
  <table width="700" border="0" cellpadding="2" cellspacing="1" class="tableblue">
    <caption class="search-box" >
      Registrar Periodo -
      Gesti&oacute;n
    </caption>
	
    <tr>
      <td colspan="2" align="left" valign="top">
		<?php if ($this->_tpl_vars['reg_ok'] != null): ?>  
		  <table width="500" border="1" cellpadding="0" cellspacing="0" class="finish">
		<tr>
		<td align="center" width="500">Se registro el periodo con exito.</td>
		</tr>
		</table>

		<?php endif; ?>	  </td>     
    </tr>	
    <tr>
      <td align="right" valign="top" class="styleText">Gestion:</td>
      <td valign="top"><?php echo $this->_tpl_vars['gestion']; ?>

      <input type="hidden" name="gestion" value="<?php echo $this->_tpl_vars['gestion']; ?>
" /></td>
    </tr>
    <tr>
      <td width="200" align="right" valign="top" nowrap="nowrap" class="styleText">Periodos registrados:</td>
      <td width="483" valign="top">
	  
        <?php unset($this->_sections['ind']);
$this->_sections['ind']['name'] = 'ind';
$this->_sections['ind']['loop'] = is_array($_loop=$this->_tpl_vars['periodos']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
        <?php echo $this->_tpl_vars['periodos'][$this->_sections['ind']['index']]['periodo']; ?>
/<?php echo $this->_tpl_vars['periodos'][$this->_sections['ind']['index']]['gestion']; ?>
<br/>
        <?php endfor; endif; ?>	
      </td>
    </tr>
    <tr>
      <td align="right" valign="top" class="styleText">Periodos abiertos: </td>
      <td valign="top"> <?php if ($this->_tpl_vars['abiertos'] != null): ?>
        <?php unset($this->_sections['ind2']);
$this->_sections['ind2']['name'] = 'ind2';
$this->_sections['ind2']['loop'] = is_array($_loop=$this->_tpl_vars['abiertos']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
        <?php echo $this->_tpl_vars['abiertos'][$this->_sections['ind2']['index']]['periodo']; ?>
/<?php echo $this->_tpl_vars['abiertos'][$this->_sections['ind2']['index']]['gestion']; ?>
<br/>
        <?php endfor; endif; ?>	
        <?php else: ?>
        No disponible.
        <?php endif; ?> </td>
    </tr>
    <tr>
      <td align="right" valign="top" class="styleText">Periodos disponibles: </td>
      <td valign="top"> <?php if ($this->_tpl_vars['perdis'] != null): ?>
        <select name="periodo" class="text3" onkeypress="return enter_handle(this, event, 1, 1)">
          
	<?php unset($this->_sections['idx']);
$this->_sections['idx']['name'] = 'idx';
$this->_sections['idx']['loop'] = is_array($_loop=$this->_tpl_vars['perdis']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
	
          <option value="<?php echo $this->_tpl_vars['perdis'][$this->_sections['idx']['index']]; ?>
"><?php echo $this->_tpl_vars['perdis'][$this->_sections['idx']['index']]; ?>
</option>
          
	<?php endfor; endif; ?>
	
        </select>
        <?php else: ?>
        <span id="errorlabel">No existe periodos disponibles para la gestion .</span>        <?php endif; ?> </td>
    </tr>
    <tr>
      <td align="right" valign="top" class="styleText">I.V.A.:</td>
      <td><input name="iva" type="text" class="text3" value="<?php echo $this->_tpl_vars['val']['iva']; ?>
" size="5" maxlength="6" onkeypress="return enter_handle(this, event, 1, 1)"/>
        %&nbsp;&nbsp;&nbsp;&nbsp;<span id="errorlabel"><?php echo $this->_tpl_vars['error']['iva']; ?>
</span></td>
    </tr>
    <tr>
      <td align="right" valign="top" class="styleText">Observascion:</td>
      <td><textarea name="obs" cols="70" class="text3" onkeypress="return enter_handle(this, event, 1, 1)"><?php echo $this->_tpl_vars['val']['obs']; ?>
</textarea></td>
    </tr>
    <tr>
      <td align="right" valign="top">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right" valign="top">&nbsp;</td>
      <td>	  
	  <input name="registrar" type="submit" class="text3" value="Registrar" />
	   <input name="cancelar" type="button" class="text3" onclick="location='/sistema/macaws/lcv.php'" value="Cancelar" />
	  </td>
    </tr>
  </table> 
</form>
<?php else: ?>
<table width="700" border="1" cellpadding="0" cellspacing="0" class="error-box">
	<tr>
	<td align="center" width="700">
		<p>
		No puede registrar mas periodos, solo puede haber 2 periodos abiertos en el sistema.<br/>
		Cierre alguno para poder continuar.<br/>
		</p>
	</td>
	</tr>
</table>
<?php endif; ?>
<?php else: ?>
<table width="700" border="1" cellpadding="0" cellspacing="0" class="error-box">
	<tr>
	<td align="center" width="700">
		<p>
		SE COMPLETO EL REGISTRO DE 12 PERIODOS PARA ESTA GESTION.<br/>
		GRACIAS.<br/>
		</p>
	</td>
	</tr>
</table>
<?php endif; ?>
</body>
</html>