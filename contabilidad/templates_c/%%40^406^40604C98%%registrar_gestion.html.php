<?php /* Smarty version 2.6.18, created on 2008-11-25 14:20:26
         compiled from registrar_gestion.html */ ?>
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
		REGISTRAR GESTION </th>
</table>

				  
			 		  
                   		
				      <form name="form_registrar" method="get" action="../controladores/gestion.php">
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
					  <?php if ($this->_tpl_vars['errores']['err_gestion'] != null): ?> 
					  <tr> <td class="error-box" colspan="2">  <?php echo $this->_tpl_vars['errores']['err_gestion']; ?>
 </td> </tr> <?php endif; ?>
						<tr>
						<td class="enunciados">Nombre:</td>
						<td><input type="text" name="gestion"  value="<?php echo $this->_tpl_vars['gestion']; ?>
" onKeyPress="return handleEnter(this, event)"/></td>
						</tr>


		<tr>
			<td class="enunciados">Fecha inicio :</td>
			<td class="body-sector">
			<span align="left" class="entrada" style="margin:5px;vertical-align:top;"> 
			<img src="../../templates/imagenes/fecha.jpg" class="imagenes"/>
			<input type="text" name="fecha_inicio" id="fecha_inicio" value="<?php echo $this->_tpl_vars['fecha_inicio']; ?>
" readonly="READONLY" style="border:none;" onkeypress="return enter_handle(this, event, 1, 1)" />
			</span>
		     <input type="button" name="b_fecha_inicio" id="b_fecha_inicio" value="..." class="boton" onkeypress="return enter_handle(this, event, 1, 1)" />
      <?php echo '
      <script type="text/javascript">Calendar.setup({inputField:"fecha_inicio", ifFormat:"%Y-%m-%d",button:"b_fecha_inicio"});</script>
      '; ?>

	   </td>
	   </tr>
				
                       
        <tr>
			<td class="enunciados">Fecha fin :</td>
			<td class="body-sector">
			<span align="left" class="entrada" style="margin:5px;vertical-align:top;"> 
			<img src="../../templates/imagenes/fecha.jpg" class="imagenes"/>
          <input type="text" name="fecha_fin" id="fecha_fin" value="<?php echo $this->_tpl_vars['fecha_fin']; ?>
" readonly="READONLY" style="border:none;" onkeypress="return enter_handle(this, event, 1, 1)" />
      </span>
        <input type="button" name="b_fecha_fin" id="b_fecha_fin" value="..." class="boton" onkeypress="return enter_handle(this, event, 1, 1)" />
      <?php echo '
      <script type="text/javascript">Calendar.setup({inputField:"fecha_fin", ifFormat:"%Y-%m-%d",button:"b_fecha_fin"});</script>
      '; ?>
 </td>
		</tr>
		</tr>
			<?php if ($this->_tpl_vars['errores']['err_ci'] != null): ?> <?php endif; ?>			<?php if ($this->_tpl_vars['errores']['err_celular'] != null): ?> <?php endif; ?>			<?php if ($this->_tpl_vars['errores']['err_telefono'] != null): ?> <?php endif; ?>			<?php if ($this->_tpl_vars['errores']['err_area'] != null): ?> <?php endif; ?>
		<tr>
		  <td colspan="2"><div align="center">
		    <input class="button1" type="submit" value="Registrar" name="registrar" />
	      </div></td>
		  </tr>
	</table>

 </form>
<?php endif; ?>