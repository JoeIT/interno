<?php /* Smarty version 2.6.18, created on 2008-11-08 10:08:37
         compiled from modificar_responsable.html */ ?>

  
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "cabecera.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

  <br />
<table width="35%" align="center">
     <th>
Datos	    Responsable</th>
</table>

				  
			 		  
                   		
				      <form name="form_registrar" method="get" action="../controladores/modificar_responsable.php">
				
					
				 <input type="hidden" name="funcion" value="validar">
				<input type="hidden" name="resp_id" value="<?php echo $this->_tpl_vars['resp_id']; ?>
">

					<table  border="0" align="center">
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
			<td ><textarea name="cargo"><?php echo $this->_tpl_vars['cargo']; ?>
</textarea></td>
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
            <option value="<?php echo $this->_tpl_vars['miarea']['area_id']; ?>
"><?php echo $this->_tpl_vars['miarea']['area']; ?>
</option>
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
		    <input class="button1" type="submit" value="Modificar" name="modificar" />
	      </div></td>
		  </tr>
	</table>

 </form>