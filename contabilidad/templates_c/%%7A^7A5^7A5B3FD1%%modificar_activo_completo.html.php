<?php /* Smarty version 2.6.18, created on 2009-07-22 10:01:53
         compiled from modificar_activo_completo.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "cabecera.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> <br/>
<?php echo '

<script language="javascript">
/*function actualizar (paginaservidor,combo,lista,clave){

new Ajax.Updater(lista, paginaservidor+\'?\'+clave+\'=\'+$F(combo), { 
	  method: \'get\', 
	   
	});

//alert (paginaservidor+\'?\'+clave+\'=\'+$F(combo)+\' - \' + secundaria.innerHTML);
}
*/
function actualizar (paginaservidor, combo, lista, clave){
	new Ajax.Updater(lista, paginaservidor+\'?\'+clave+\'=\'+$F(combo), { 
	  method: \'get\'	});
}

function correlativo (paginaservidor,combo,lista,clave){

new Ajax.Updater(lista, paginaservidor, { 
  method: \'get\', 
  parameters: { cuenta: $F(combo)} 
});

//alert (paginaservidor+" "+combo+" "+lista+" "+$(combo).value);
}
/*function actualizar (paginaservidor,combo,lista,clave){

new Ajax.Updater(lista, paginaservidor, { 
  method: \'get\', 
  parameters: { primaria: $F(combo)} 
});

//alert (paginaservidor+" "+combo+" "+lista+" "+$(combo).value);
}*/
</script>



'; ?>

<form name="orden" method="get" action="../controladores/modificar_activo_completo.php"  align="center">
 
 <div align="center">Modificar  activos </div> 
 
 <div align="center">
<fieldset style="width:95%;">
<legend style="color:#6699CC">Localizacion y Responsable </legend><br />

<table width="100%" border="0" cellspacing="3" cellpadding="0">

	 <?php if ($this->_tpl_vars['errores']['err_confirm'] != null): ?>
  
    <tr>
		<td></td>
		
		
      <td class="error-box" colspan="2"> <?php echo $this->_tpl_vars['errores']['err_confirm']; ?>
 </td>
    </tr>
  <?php endif; ?>
  
  
  

	<?php if ($this->_tpl_vars['errores']['err_nombre'] != null && $this->_tpl_vars['errores']['err_pri'] != null): ?>
	<tr>	
		<td colspan="2" class="error-box" ><?php echo $this->_tpl_vars['errores']['err_pri']; ?>
</td>
		<td class="error-box" colspan="2"><?php echo $this->_tpl_vars['errores']['err_nombre']; ?>
</td>
	</tr>
	
	<?php elseif ($this->_tpl_vars['errores']['err_nombre'] != null): ?> 
 	<tr>	
		<td colspan="2" ></td>
		<td class="error-box" colspan="2"><?php echo $this->_tpl_vars['errores']['err_nombre']; ?>
</td>
	</tr>
  	
  <?php elseif ($this->_tpl_vars['errores']['err_pri'] != null): ?> 
 	<tr>	
		<td colspan="2" class="error-box" ><?php echo $this->_tpl_vars['errores']['err_pri']; ?>
</td>
		<td colspan="2"></td>
	</tr>
  	<?php endif; ?>
    <td width="12%" class="enunciados" ><div align="left" >Primaria:</div></td>
    <td width="25%"><div id="primaria">
      <select name="pri" id="pri2" onchange="actualizar ('combo1.php','pri2','secundaria','primaria')" >
     <?php if ($this->_tpl_vars['si']): ?>
	   <option value="<?php echo $this->_tpl_vars['detalle']['primaria_id']; ?>
"><?php echo $this->_tpl_vars['detalle']['despri']; ?>
</option>
        
	   <?php endif; ?>      	    	  		
  <?php $_from = $this->_tpl_vars['primaria']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>	 
	       
      <option value="<?php echo $this->_tpl_vars['item']['primaria_id']; ?>
"><?php echo $this->_tpl_vars['item']['descripcion']; ?>
</option>
          	    	
	  
	<?php endforeach; endif; unset($_from); ?>	
    
    </select>

    </div></td>
     <td width="13%" class="enunciados"><div align="left">Responsable:</div></td>
    <td width="50%" class="body-sector"  >
	<div align="left" class="entrada" style="margin:5px; border:none">
	 <span class="entrada" style="margin:5px; border:none">
	 	<span class="entrada" style="margin:5px;">

		 <input type="text" name="nombre_asis2" id="nombre_asis2" style="border:none;"  onkeypress="return handleEnter(this, event)" value="<?php echo $this->_tpl_vars['nombre_asis2']; ?>
" size="40"/>
        </span>	</span><span class="entrada" style="margin:5px; border:none">
        <input type="hidden" name="resp_id2" id="resp_id2"  value="<?php echo $this->_tpl_vars['resp_id2']; ?>
" />
        </span><img src="../../templates/imagenes/combo.jpg" class="imagenes" onclick="<?php echo 'document.getElementById(\'nombre_asis2\').focus();document.getElementById(\'nombre_asis2\').select();'; ?>
" style="cursor:pointer;" alt="Seleccionar Nombre"/><span class="entrada" style="margin:5px; border:none">
        <input type="hidden" name="resp_1" id="resp_1"  value="<?php echo $this->_tpl_vars['resp_1']; ?>
" />
        </span></div>
        <span id="spinner" style="display:none"> </span>
        <div id="lista_nombres_asis" class="autorelleno"></div>
      <?php echo '
      <script>
			new Ajax.Autocompleter("nombre_asis2", "lista_nombres_asis", "../controladores/activo.php?opcion=busqueda_ajax1", {method:"post", paramName: "nombre", minChars: 1, indicator: "spinner", afterUpdateElement : mostrar_categoria});
			function mostrar_categoria(text,li) {
				cadena = li.id;
				//caracteristicas = cadena.split("-");
				//var str = document.getElementById("nombre_asis").value;
				document.getElementById("resp_id2").value = cadena;
				document.getElementById("nombre_asis2").value = str.substr(0)
			}
		</script>
      '; ?>
</td>  </tr>
	  
  
  <?php if ($this->_tpl_vars['errores']['err_sec'] != null && $this->_tpl_vars['errores']['err_nombre1'] != null): ?>
  <tr>
  	<td class="error-box" colspan="2"><?php echo $this->_tpl_vars['errores']['err_sec']; ?>
 </td>	
	<td class="error-box" colspan="2"><?php echo $this->_tpl_vars['errores']['err_nombre1']; ?>
  </td>
  </tr>
 
 <?php elseif ($this->_tpl_vars['errores']['err_sec'] != null): ?> 
 	<tr>	
		<td class="error-box" colspan="2"><?php echo $this->_tpl_vars['errores']['err_sec']; ?>
</td>
		<td colspan="2" ></td>
	</tr>
  	
  <?php elseif ($this->_tpl_vars['errores']['err_nombre1'] != null): ?> 
 	<tr>	
		<td colspan="2"></td>
		<td colspan="2" class="error-box" ><?php echo $this->_tpl_vars['errores']['err_nombre1']; ?>
</td>
	</tr>
  	<?php endif; ?>
 
 
  <tr>
    <td class="enunciados" ><div align="left">Secundaria:</div></td>
    <td> <div id="secundaria" >
      <select name="secun" id="select2"  >
        <option value="<?php echo $this->_tpl_vars['detalle']['secundaria_id']; ?>
"><?php echo $this->_tpl_vars['detalle']['dessec']; ?>
</option>
        
		<?php $_from = $this->_tpl_vars['secundaria']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>	 
	   		      
            
      
        <option value="<?php echo $this->_tpl_vars['item']['secundaria_id']; ?>
"><?php echo $this->_tpl_vars['item']['descripcion']; ?>
</option>
        
                      	    	   
	  
	<?php endforeach; endif; unset($_from); ?>	
  
				
      
      </select>
    </div></td>
   <td class="enunciados" ><div align="left">Asignado:</div></td>
    <td  class="body-sector" >
	<div align="left" class="entrada" style="margin:5px; border:none" > 
	<span class="entrada" style="margin:5px; border:none">
	<span class="entrada" style="margin:5px;">
     	
			 <input type="text" name="nombre_asis" id="nombre_asis" style="border:none;"  onkeypress="return handleEnter(this, event)" value="<?php echo $this->_tpl_vars['nombre_asis']; ?>
" size="40"/>
        </span></span>
            <input type="hidden" name="resp_id" id="resp_id"  value="<?php echo $this->_tpl_vars['resp_id']; ?>
" />
			
            <img src="../../templates/imagenes/combo.jpg" class="imagenes" onclick="<?php echo 'document.getElementById(\'nombre_asis\').focus();document.getElementById(\'nombre_asis\').select();'; ?>
" style="cursor:pointer;" alt="Seleccionar Nombre"/><span class="entrada" style="margin:5px; border:none">
            <input type="hidden" name="resp_2" id="resp_2"  value="<?php echo $this->_tpl_vars['resp_2']; ?>
" />
            </span></div>
        <span id="spinner" style="display:none"> </span>
        <div id="lista_nombres_asis" class="autorelleno"></div>
      <?php echo '
      <script>
			new Ajax.Autocompleter("nombre_asis", "lista_nombres_asis", "../controladores/activo.php?opcion=busqueda_ajax1", {method:"post", paramName: "nombre", minChars: 1, indicator: "spinner", afterUpdateElement : mostrar_categoria});
			function mostrar_categoria(text,li) {
				cadena = li.id;
				//caracteristicas = cadena.split("-");
				//var str = document.getElementById("nombre_asis").value;
				document.getElementById("resp_id").value = cadena;
				document.getElementById("nombre_asis").value = str.substr(0)
			}
		</script>
      '; ?>
 </td>
  </tr>
  <?php if ($this->_tpl_vars['errores']['err_gru'] != null): ?>
   <tr>
    <td colspan="2" class="error-box"><?php echo $this->_tpl_vars['errores']['err_gru']; ?>
 </td>
	
	<td colspan="2"> </td>
  </tr>
  <?php endif; ?>
  <tr>
    <td height="42" class="enunciados"><div align="left">Cuenta:</div></td>
    <td><select name="gru" id="sec"  >
      <option value="<?php echo $this->_tpl_vars['detalle']['grupo_id']; ?>
"><?php echo $this->_tpl_vars['detalle']['desgru']; ?>
</option>
      
   
    
    
    </select></td>
    <td><div align="left"></div></td>
    <td class="body-sector" ><div id="mainContent"> <a  href="javascript:ventanaSecundaria ('responsable.php?popup=true')"> registrar responsable </a> </div></td>
  </tr>
</table>
<br />

</fieldset>
</div>

<br />

<div align="center">
<fieldset style="width:95%">
<legend style="color:#6699CC">Activo</legend>
<input type="hidden" name="funcion" value="validar" />
<input type="hidden" name="act_id" value="<?php echo $this->_tpl_vars['detalle']['act_id']; ?>
" />
<table width="100%" border="0" cellspacing="3" cellpadding="0">
  <?php if ($this->_tpl_vars['errores']['err_cambio'] != null): ?>  
    <tr>
		<td></td>	
		<td></td>	
		<td></td>	
		<td></td>	
		<td></td>	
		<td></td>		
      <td class="error-box" colspan="2"> <?php echo $this->_tpl_vars['errores']['err_cambio']; ?>
 </td>
    </tr>
  <?php endif; ?>
 <tr>
    <td class="enunciados"><div align="left">N Correlativo</div></td>
    <td colspan="3">
	 
	   <input type="text" name="num_corr" value="<?php echo $this->_tpl_vars['detalle']['num_act']; ?>
" />	</td>
    <td class="enunciados"><div align="left">Fecha</div></td>
	
    <td><span align="left" class="entrada" style="margin:5px;vertical-align:top;">
			<img src="../../templates/imagenes/fecha.jpg" class="imagenes"/>
			<input type="text" name="fecha"  value="<?php echo $this->_tpl_vars['detalle']['fecha']; ?>
" readonly style="border:none;"/>
		</span>
        <input type="button" id="fecha_boton" class="boton" value="..." /></td>
    <td class="enunciados"><div align="left">Dolar $</div></td>
    <td class="body-sector">
	  
      <input type="text" name="tipo" size="12" value="<?php echo $this->_tpl_vars['detalle']['tipo_cambio']; ?>
"  /></td>
  </tr>
  <tr>
    <td class="enunciados"><div align="left">Cantidad</div></td>
    <td colspan="3"><input type="text" name="cantidad"  value="1" size="12" readonly="false"/></td>
    <td class="enunciados"><div align="left">Unidad</div></td>
    <td><select name="unidad" id="select" >
	 <?php if ($this->_tpl_vars['detalle']['unidad'] == ''): ?>
		 <option value="Pza">Seleccionar</option>
	  <option value="Pza">Pza</option>
	   <option value="GLB">GLB</option>
	   <option value="Juego">Juego</option>
	  
	  <?php endif; ?>
     <?php if (Pza == $this->_tpl_vars['detalle']['unidad']): ?>
	  <option value="Pza"><?php echo $this->_tpl_vars['detalle']['unidad']; ?>
</option>
	   <option value="GLB">GLB</option>
	   <option value="Juego">Juego</option>
	  
	  <?php endif; ?>
	     <?php if (GLB == $this->_tpl_vars['detalle']['unidad']): ?>
	  <option value="GLB"><?php echo $this->_tpl_vars['detalle']['unidad']; ?>
</option>
	   <option value="Pza">Pza</option>
	   <option value="Juego">Juego</option>
	  
	  <?php endif; ?>
	  <?php if (Juego == $this->_tpl_vars['detalle']['unidad']): ?>
	  <option value="Juego"><?php echo $this->_tpl_vars['detalle']['unidad']; ?>
</option>
	   <option value="Pza">Pza</option>
	 <option value="GLB">GLB</option>
	  
	  <?php endif; ?>   
    </select></td>
    <td class="enunciados"><div align="left">UFV</div></td>
	
    <td class="body-sector" >
	
	  <input type="text" name="ufv"  size="12" value="<?php echo $this->_tpl_vars['detalle']['ufv']; ?>
"  /></td>
  </tr>
  <tr>
  <td height="19"></td>
  <td colspan="3"></td>
  <td></td>
  <td></td>
  <td align="left" class="body-sector"><div align="left"><a href="javascript:ventanaSecundaria ('tipo_cambio.php?popup=true')">Ingresar T/C </a></div></td>
  </tr>
   <?php if ($this->_tpl_vars['errores']['err_adqui'] != null && $this->_tpl_vars['errores']['err_vida'] != null): ?>
  <tr>
  	<td class="error-box" colspan="4"><?php echo $this->_tpl_vars['errores']['err_adqui']; ?>
 </td>	
	<td colspan="2"> </td>
	<td class="error-box" colspan="2"><div align="left"><?php echo $this->_tpl_vars['errores']['err_vida']; ?>
</div></td>
  </tr>
  
   <?php elseif ($this->_tpl_vars['errores']['err_adqui'] != null): ?>
  <tr>
  	<td class="error-box" colspan="4"><?php echo $this->_tpl_vars['errores']['err_adqui']; ?>
 </td>	
	<td colspan="2"> </td>
	<td colspan="2"><div align="left"></div></td>
  </tr>
   <?php elseif ($this->_tpl_vars['errores']['err_vida'] != null): ?>
  <tr>
  	
	<td height="23" colspan="4"> </td>
	<td colspan="2"></td>
	<td class="error-box" colspan="2"><div align="left"><?php echo $this->_tpl_vars['errores']['err_vida']; ?>
 </div></td>	
  </tr>
  <?php endif; ?>
  
  
  <tr>
    <td class="enunciados"><div align="left">Adquisicion</div></td>
    <td><select name="adqui" id="ad" >
     <?php if ($this->_tpl_vars['adquis']['nombre'] == ''): ?>
	  <option value="selc">Seleccionar</option>  
	 <?php else: ?>
	  <option value="<?php echo $this->_tpl_vars['adquis']['ad_id']; ?>
"><?php echo $this->_tpl_vars['adquis']['nombre']; ?>
</option>
     <?php endif; ?> 
	
 	 <?php $_from = $this->_tpl_vars['adqui']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>	       
        
     
      
      <option value="<?php echo $this->_tpl_vars['item']['ad_id']; ?>
" <?php if ($this->_tpl_vars['ad']['ad_id'] == $this->_tpl_vars['item']['ad_id']): ?>selected="selected"<?php endif; ?> ><?php echo $this->_tpl_vars['item']['nombre']; ?>
</option>
      
 
	  
	<?php endforeach; endif; unset($_from); ?>	 
  
    
    </select></td>
    <td class="enunciados">N&uacute;mero </td>
    <td><input type="text" name="det_adqui"  size="20" value="<?php echo $this->_tpl_vars['det_adqui']; ?>
"/></td>
    <td class="enunciados"><div align="left">Serie</div></td>
    <td ><input type="text" name="serie"  size="12" value="<?php echo $this->_tpl_vars['detalle']['serie']; ?>
"/></td>
    <td class="enunciados"><div align="left">Vida Util (meses) </div></td>
    <td><input type="text" name="vida_util"  size="12" value="<?php echo $this->_tpl_vars['detalle']['vida_util']; ?>
"/></td>
  </tr>
    
  <?php if ($this->_tpl_vars['errores']['descripcion'] != null && $this->_tpl_vars['errores']['err_valor'] != null): ?>
  <tr>
  	<td class="error-box" colspan="4"><?php echo $this->_tpl_vars['errores']['descripcion']; ?>
 </td>	
	<td></td>
	<td></td>
	<td colspan="2" class="error-box" ><div align="left"><?php echo $this->_tpl_vars['errores']['err_valor']; ?>
</div></td>
  </tr>
 
 <?php elseif ($this->_tpl_vars['errores']['descripcion'] != null): ?> 
 	<tr>
	    <td class="error-box" colspan="4"><?php echo $this->_tpl_vars['errores']['descripcion']; ?>
 </td>
	    <td ></td>	
	    <td ></td>
		<td ><div align="left"></div></td>
	</tr>
	
  	<?php elseif ($this->_tpl_vars['errores']['err_valor'] != null): ?>
	<tr>	
		<td ></td>
	    <td colspan="3" ></td>	
	    <td ></td>
		<td ></td>
		
		<td colspan="2" class="error-box" ><div align="left"><?php echo $this->_tpl_vars['errores']['err_valor']; ?>
</div></td>
	</tr>
	
  	<?php endif; ?>
 
  <tr>
    <td rowspan="3" class="enunciados"><div align="left">Descripcion</div></td>
    <td colspan="5" rowspan="3"><textarea name="descripcion" cols="35" rows="4"><?php echo $this->_tpl_vars['detalle']['desact']; ?>
</textarea></td>
    <td class="enunciados" ><div align="left">Valor Compra(Bs)</div></td>
    <td><input type="text" name="valor"  size="12" value="<?php echo $this->_tpl_vars['detalle']['valor_compra']; ?>
"/></td>
  </tr>
  <?php if ($this->_tpl_vars['errores']['err_residual'] != null): ?>
    <tr>
      <td colspan="2" class="error-box" ><div align="left"><?php echo $this->_tpl_vars['errores']['err_residual']; ?>
</div></td>
      </tr>
	
	<?php endif; ?>
	<tr>
      <td class="enunciados"><div lign="left">
        <div align="left">Valor Residual(Bs)</div>
      </div></td>
      <td><input type="text" name="residual"  size="12" value="<?php echo $this->_tpl_vars['detalle']['valor_residual']; ?>
"/></td>
      </tr>
  <tr>  </tr>
</table>

</fieldset>
</div>

<center>
  <table width="95%" border="0" cellspacing="2" cellpadding="0">
   <?php if ($this->_tpl_vars['foto'] != ""): ?>
    <tr><?php $_from = $this->_tpl_vars['foto']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
      <td width="6%"><img src="personal_resizeImage.php?max=150&amp;imgorig=../controladores/UploadedFiles/<?php echo $this->_tpl_vars['item']['fotografia']; ?>
" alt="<?php echo $this->_tpl_vars['item']['fotografia']; ?>
" name="imagen_fotografia" border="1" align="middle" id="imagen_fotografia"/>
            <input type="hidden" name="foto" value="<?php echo $this->_tpl_vars['item']['fotografia']; ?>
" />
		<input type="hidden" name="numerito" value="<?php echo $this->_tpl_vars['numerito']; ?>
" /></td>
      <?php endforeach; endif; unset($_from); ?>
	       <td width="94%">&nbsp;</td>
    </tr>
	<?php else: ?>
	<tr >"no existe foto para el activo"</tr>
	<?php endif; ?>
  </table>
  <br>
  <input type="submit" value="Modificar Activo" name="modificar_activo_completo" class="enviar" onmouseover="this.className='boton'" onmouseout="this.className='enviar'"  onclick="return confirm('Seguro que deseas Modificar?')"/>
</center>
</form>

<?php echo '
<script type="text/javascript">
  							 Calendar.setup({
						     inputField     :    "fecha",     // id del campo de texto
						     ifFormat     :     "%Y-%m-%d" ,   // formato de la fecha que se escriba en el campo de texto
						     button     :    "fecha_boton"     // el id del botón que lanzará el calendario
							});
						</script>
'; ?>

</body>