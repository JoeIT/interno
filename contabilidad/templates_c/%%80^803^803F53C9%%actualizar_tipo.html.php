<?php /* Smarty version 2.6.18, created on 2009-01-27 18:51:35
         compiled from actualizar_tipo.html */ ?>

 
 
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
 <?php echo '
 <script language="JavaScript"> 
 function redirect(url)
{
location.href=url;
} 
</script>
 
 '; ?>

 
 <?php if ($this->_tpl_vars['cerrar'] != null && $this->_tpl_vars['cerrar'] == 'yes'): ?>
	<?php echo '
	<script>
		opener.location.reload();
		this.close();
	</script>
	'; ?>

<?php endif; ?>
  <br />
<table width="70%" align="center">
     <th>ACTUALIZAR TIPO CAMBIO </th>
</table>

				  
			 		  
                   		
				      <form name="form_registrar" method="get" action="../controladores/tipo_cambio.php" >
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
					 
					  
						<tr>
				 <?php if ($this->_tpl_vars['errores']['err_tipo'] != null): ?><tr> 
					 <td class="error-box" colspan="2"> <?php echo $this->_tpl_vars['errores']['err_tipo']; ?>
 </td> </tr> <?php endif; ?>
				
						<tr>						
						
						<td class="enunciados">Tipo:</td>
						<td><span class="body-sector">
						  <select name="tipo" id="select2"  >
                            <option value="selc">Seleccionar</option>
						                
          
  						<?php $_from = $this->_tpl_vars['moneda']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>	   
      		    
						    <option value="<?php echo $this->_tpl_vars['item']['tipo_id']; ?>
"><?php echo $this->_tpl_vars['item']['moneda']; ?>
--<?php echo $this->_tpl_vars['item']['tipo_cambio']; ?>
</option>
						    
          
	<?php endforeach; endif; unset($_from); ?>	    
      
      
					    </select>
						</span></td>
						</tr>
					
		<?php if ($this->_tpl_vars['errores']['err_valor'] != null): ?><tr> <td class="error-box" colspan="2"><?php echo $this->_tpl_vars['errores']['err_valor']; ?>
 </td> </tr> <?php endif; ?>
		<tr>
			<td class="enunciados">Valor:</td>
			<td><input type="text" id="valor" name="valor"  value="<?php echo $this->_tpl_vars['valor']; ?>
" onKeyPress="return handleEnter(this, event)"/></td>
		</tr>
			<?php if ($this->_tpl_vars['errores']['err_cargo'] != null): ?><tr> <td class="error-box" colspan="2"> <?php echo $this->_tpl_vars['errores']['err_cargo']; ?>
 </td> </tr> <?php endif; ?>
                       
        <tr>
			<td class="enunciados">Fecha:</td>
			 <td class="body-sector"><span align="left" class="entrada" style="margin:5px;vertical-align:top;"> <img src="../../templates/imagenes/fecha.jpg" class="imagenes"/>
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
		<td colspan="2"> <center>
		  <input name="submit" type="submit" class="button1" onclick="redirect('../controladores/activo.php?opcion=registrar_activo')" value="Registrar" />
		</center>			</td>
		</tr>
	</table>

 </form>
<?php endif; ?>