<?php /* Smarty version 2.6.18, created on 2007-07-16 17:36:19
         compiled from adminGestion.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'adminGestion.html', 48, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.html", 'smarty_include_vars' => array('title' => $this->_tpl_vars['title'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php echo '
<script type="text/javascript">
// Función sobre aviso 
function confirmActions(message)
{ 
	var conf = confirm(message); 
    if (conf) 		
       return true;
	else
	   return false; 
} 
</script>
'; ?>

<br/>
  <table width="700" border="1" cellpadding="2" cellspacing="1" class="tableblue">
    <caption class="search-box" >
      Abrir - Cerrar gestiones.
    </caption>
	<tr>
	<td colspan="7">
	<?php if ($this->_tpl_vars['reg_ok'] != null): ?>
	<table width="250" border="0" cellpadding="0" cellspacing="0" class="finish">
	  <tr>
		<td><?php echo $this->_tpl_vars['reg_ok']; ?>
</td>
	  </tr>
	</table>
	<?php endif; ?>
	</td>
	</tr>
	
	<tr>
	<td colspan="7" class="error-box">
	<?php echo $this->_tpl_vars['error']['abrir']; ?>

	</td>
	</tr>
	    
	<?php if ($this->_tpl_vars['gestiones'] != null): ?>	
	<tr>
	<td class="header-table">Gesti&oacute;n</td>
	<td class="header-table">Estado</td>
	<td class="header-table">Observaci&oacute;n</td>
	<td class="header-table">&nbsp;</td>
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
      <td align="center" width="150"> <?php echo $this->_tpl_vars['gestiones'][$this->_sections['ind']['index']]['gestion']; ?>
  </td>
	  <?php if ($this->_tpl_vars['gestiones'][$this->_sections['ind']['index']]['estado'] == 1): ?>
	  <td width="150">Abierta</td>
 	  <td><?php echo $this->_tpl_vars['gestiones'][$this->_sections['ind']['index']]['obs']; ?>
&nbsp;</td>
	  <form method="post" name="form1"  action="actions/action_adminGestion.php">	  
  	  <td width="150"> 
	  <input name="gestion" type="hidden" value="<?php echo $this->_tpl_vars['gestiones'][$this->_sections['ind']['index']]['gestion']; ?>
" />
	  <input name="cerrar" type="submit" class="text3" id="cerrar"  value="Cerrar gestión" onclick="return confirmActions('Al CERRAR la gestión cerrara todos sus peridos abierto. \n ¿Desea continuar?');" />
	  </td>
  	  </form>
	  <?php else: ?>
  	  <td width="150">Cerrada</td>
 	  <td><?php echo $this->_tpl_vars['gestiones'][$this->_sections['ind']['index']]['obs']; ?>
&nbsp;</td>
  	  <form method="post" name="form2" action="actions/action_adminGestion.php">
	  <td width="150">	  
	  <input name="gestion" type="hidden" value="<?php echo $this->_tpl_vars['gestiones'][$this->_sections['ind']['index']]['gestion']; ?>
" />
	  <input name="abrir" type="submit" class="text3" id="abrir"  value="Abrir gestión" onclick="return confirmActions('¿Esta seguro de ABRIR esta gestión?');" />
	  </td>
  	  </form>
	  <?php endif; ?>
    </tr>	
	<?php endfor; endif; ?>	
	
    <?php else: ?>
	<tr>
	<td colspan="7" class="text6">
    No disponible.	</td>
	</tr>
    <?php endif; ?>	
	
	<tr>
	<td colspan="7" align="center">
	<br/>
	<input name="volver" type="button" class="text3" onclick="location='/sistema/macaws/abrirGestion.php'" value="Registrar nueva gestión" />
	<br />
	<input name="salir" type="button" class="text7" onclick="location='/sistema/macaws/lcv.php'" value="Salir" />
	</td>
	</tr>	
</table>

</body>
</html>