<?php /* Smarty version 2.6.18, created on 2009-03-18 11:33:45
         compiled from registrar_responsable.html */ ?>
 <?php if ($this->_tpl_vars['menu'] == 'false' && $this->_tpl_vars['errores']['err_confirm'] != null): ?>
     <?php echo '
        <script language="JavaScript"> 
		     window.close();
		</script>
     '; ?>


  <?php else: ?>
<?php if ($this->_tpl_vars['menu'] != 'false'): ?>
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "cabecera_popup.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <?php else: ?>
  
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "cabecera_popup.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <?php endif; ?>
  <br />
<table width="70%" align="center">
   <th>
		REGISTRAR RESPONSABLE </th>
</table>

				  
			 		  
                   		
				      <form name="form_registrar" method="get" action="../controladores/responsable.php">
				<?php if ($this->_tpl_vars['menu'] == 'false'): ?>
				    <input type="hidden" name="popup" value="true">
				<?php else: ?>
				    <input type="hidden" name="popup" value="false">
				<?php endif; ?>	
					
				 <input type="hidden" name="funcion" value="validar">


					<table width="70%" border="0" align="center">
					 <?php if ($this->_tpl_vars['errores']['err_confirm'] != null): ?><tr> 
					 <td class="error-box" colspan="2"> <?php echo $this->_tpl_vars['errores']['err_confirm']; ?>
 </td> </tr> <?php endif; ?>
					  <?php if ($this->_tpl_vars['errores']['err_nombre'] != null): ?> 
					  <tr> <td class="error-box" colspan="2">  <?php echo $this->_tpl_vars['errores']['err_nombre']; ?>
 </td> </tr> <?php endif; ?>
						<tr>
						<td class="enunciados">Nombre:</td>
						<td><input type="text" name="nombre"  value="<?php echo $this->_tpl_vars['nombre']; ?>
" onKeyPress="return handleEnter(this, event)"/></td>
						</tr>
					
		<?php if ($this->_tpl_vars['errores']['err_apell'] != null): ?><tr> <td class="error-box" colspan="2"><?php echo $this->_tpl_vars['errores']['err_apell']; ?>
 </td> </tr> <?php endif; ?>
		<tr>
			<td class="enunciados">Apellidos:</td>
			<td><input type="text" id="apellido" name="apellido"  value="<?php echo $this->_tpl_vars['apellido']; ?>
" onKeyPress="return handleEnter(this, event)"/></td>
		</tr>
			<?php if ($this->_tpl_vars['errores']['err_cargo'] != null): ?><tr> <td class="error-box" colspan="2"> <?php echo $this->_tpl_vars['errores']['err_cargo']; ?>
 </td> </tr> <?php endif; ?>
                       
        <tr>
			<td class="enunciados">Cargo:</td>
			<td ><input  type="text" id="cargo" name="cargo"  value="<?php echo $this->_tpl_vars['cargo']; ?>
" onKeyPress="return handleEnter(this, event)"/></td>
		</tr>
		</tr>
			<?php if ($this->_tpl_vars['errores']['err_ci'] != null): ?><tr> <td class="error-box" colspan="2"> <?php echo $this->_tpl_vars['errores']['err_ci']; ?>
 </td> </tr> <?php endif; ?>
                       
        <tr>
		<tr>
		<td class="enunciados">Ci
		 </td>
		<td><input  type="text" id="ci" name="ci"  value="<?php echo $this->_tpl_vars['ci']; ?>
" onkeypress="return handleEnter(this, event)"/></td>
		</tr>
		</tr>
			<?php if ($this->_tpl_vars['errores']['err_celular'] != null): ?><tr> <td class="error-box" colspan="2"> <?php echo $this->_tpl_vars['errores']['err_celular']; ?>
 </td> </tr> <?php endif; ?>
                       
        <tr>
		<tr>
		  <td class="enunciados">Celular:</td>
		  <td><input  type="text" id="celular" name="celular"  value="<?php echo $this->_tpl_vars['celular']; ?>
" onkeypress="return handleEnter(this, event)"/></td>
		</tr>
		</tr>
			<?php if ($this->_tpl_vars['errores']['err_telefono'] != null): ?><tr> <td class="error-box" colspan="2"> <?php echo $this->_tpl_vars['errores']['err_telefono']; ?>
 </td> </tr> <?php endif; ?>
                       
        <tr>
		<tr>
		  <td class="enunciados">Telefono:</td>
		  <td><input  type="text" id="telfono" name="telefono"  value="<?php echo $this->_tpl_vars['telefono']; ?>
" onkeypress="return handleEnter(this, event)"/></td>
		</tr>
		</tr>
			<?php if ($this->_tpl_vars['errores']['err_area'] != null): ?><tr> <td class="error-box" colspan="2"> <?php echo $this->_tpl_vars['errores']['err_area']; ?>
 </td> </tr> <?php endif; ?>
                       
        <tr>
		<tr>
		  <td class="enunciados">Area</td>
		  <td><select name="area" id="sec"  >
            <option value="selc">Seleccionar</option>
            
  <?php $_from = $this->_tpl_vars['area']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>	 
	 	    <option value="<?php echo $this->_tpl_vars['item']['area_id']; ?>
" ><?php echo $this->_tpl_vars['item']['area']; ?>
</option>
	<?php endforeach; endif; unset($_from); ?>	
 
	      </select></td>
		</tr>
		<tr>
		  <td colspan="2">&nbsp;</td>
		  </tr>
		<tr>
		  <td colspan="2"><div align="center">
		    <input class="button1" type="submit" value="Registrar" name="registrar" />
	      </div></td>
		  </tr>
	</table>

 </form>
<?php endif; ?>