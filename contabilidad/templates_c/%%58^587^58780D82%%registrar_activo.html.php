<?php /* Smarty version 2.6.18, created on 2011-05-31 18:23:36
         compiled from registrar_activo.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'commify', 'registrar_activo.html', 317, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "cabecera.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> <br/>
<?php echo '
<script type="text/javascript">
    var xmlhttp;
    
    function desplegar(fecha)
        {
			<!--alert(fecha);-->
    	xmlhttp=GetXmlHttpObject();
    	if (xmlhttp==null)
    	  {
    	  alert ("Browser does not support HTTP Request");
    	  return;
    	  }
       
    	 url="/sistema/contabilidad/controladores/ajaxtipo.php?fecha=";
    	 url=url+fecha;
		 
    	 xmlhttp.onreadystatechange=stateChanged;
         xmlhttp.open("GET",url,true);
    	 xmlhttp.send(null);
        }
    function stateChanged()
        {
        	/*aqui mostramos un mensajem, animacion, efecto, de cargando, el ajax esta haciendo la solicitud */
        	if (xmlhttp.readyState==1)
        	{
        		//document.getElementById("load_products").innerHTML="<select style=\'font-size:11px;height:20px;\'  name=\'select_product\' id=\'select_product\' ><option disabled=\'true  value=\'0\'>Loading Products</option></select>";
        	}
        	/* la solicitud esta echa y lista para  meterla a nuestro documento*/
        	if (xmlhttp.readyState==4)
        	{
        	   document.getElementById(\'return-ajax\').innerHTML=xmlhttp.responseText;
			   if(document.getElementById(\'ajaxdolar\').value!=\'\')
			   {
				document.getElementById(\'cambioDolar\').value=document.getElementById(\'ajaxdolar\').value
			   }
			   else
			   {
				document.getElementById(\'cambioDolar\').value="No actualizada"
			   }

			   document.getElementById(\'cambioUfv\').value=document.getElementById(\'ajaxufv\').value
        	}
        }

    /* creamos un objecto, esto nunca cambiara en nuestro codigo, y si hacemos varaias funciones de solicitud, todas llamara a esta para la creacion del objectoa sincrono*/
    function GetXmlHttpObject()
    {
    	if (window.XMLHttpRequest)
    	  {
    	  // code for IE7+, Firefox, Chrome, Opera, Safari
    	  return new XMLHttpRequest();
    	  }
    	if (window.ActiveXObject)
    	  {
    	  // code for IE6, IE5
    	  return new ActiveXObject("Microsoft.XMLHTTP");
    	  }
    	return null;
    }
</script>
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

<form name="orden" method="get" action="../controladores/activo.php?opcion=insertar_activo"  align="center">
 
 <div align="center"> </div> 
 <table border="0" align="center" cellpadding="0" cellspacing="3" width="95%">
    <tr>
      <th>Registro de activos</th>
    </tr>
  </table>
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
 		
		 <option value="selc">Seleccionar</option>  

		 <?php $_from = $this->_tpl_vars['primaria']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>	          
                
        <option value="<?php echo $this->_tpl_vars['item']['primaria_id']; ?>
"  <?php if ($this->_tpl_vars['prima1']['primaria_id'] == $this->_tpl_vars['item']['primaria_id']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['item']['descripcion']; ?>
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
        </span>
	</span>
            <input type="hidden" name="resp_id2" id="resp_id2"  value="<?php echo $this->_tpl_vars['resp_id2']; ?>
" />
            <img src="../../templates/imagenes/combo.jpg" class="imagenes" onclick="<?php echo 'document.getElementById(\'nombre_asis2\').focus();document.getElementById(\'nombre_asis2\').select();'; ?>
" style="cursor:pointer;" alt="Seleccionar Nombre"/></div>
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
    <td> <div id="secundaria" ><select name="secun" id="select"  >
	
		
		 <option value="selc">Seleccionar</option>  
		 
	   
		<?php $_from = $this->_tpl_vars['secundaria']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>	 
	   	    
            
      <option value="<?php echo $this->_tpl_vars['item']['secundaria_id']; ?>
" <?php if ($this->_tpl_vars['secunda']['secundaria_id'] == $this->_tpl_vars['item']['secundaria_id']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['item']['descripcion']; ?>
</option>
                      	    	   
	  
	<?php endforeach; endif; unset($_from); ?>	
  
				
      </select></div></td>
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
" style="cursor:pointer;" alt="Seleccionar Nombre"/></div>
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
    <td height="23" colspan="2" class="error-box"><?php echo $this->_tpl_vars['errores']['err_gru']; ?>
 </td>
	
	<td colspan="2"> </td>
  </tr>
  <?php endif; ?>
  <tr>
    <td height="42" class="enunciados"><div align="left">Cuenta:</div></td>
    <td><div id="cuenta" >
      <select name="gru" id="gru1" onchange="actualizar('combo2.php','gru1','numcorr','cuenta') ">

      <option value="selc">Seleccionar</option>  
	
 
  <?php $_from = $this->_tpl_vars['grupo']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>	    
 
        <option value="<?php echo $this->_tpl_vars['item']['grupo_id']; ?>
" <?php if ($this->_tpl_vars['gru']['grupo_id'] == $this->_tpl_vars['item']['grupo_id']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['item']['descripcion']; ?>
</option>

	<?php endforeach; endif; unset($_from); ?>	

      </select>
    </div></td>
    <td><div align="left"></div></td>
    <td class="body-sector" ><div id="mainContent"> <a  href="javascript:ventanaSecundaria1 ('responsable.php?popup=true')"> registrar responsable </a> </div></td>
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
<table width="100%" border="0" cellspacing="3" cellpadding="0">
  <tr>
    <td class="enunciados"><div align="left">N Correlativo</div></td>
    <td colspan="3"><div id="numcorr">
	 <?php if ($this->_tpl_vars['ver']): ?>
	 	<input type="text" name="num_corr"  value="" size="12" readonly="false"/>
	 <?php endif; ?>
	 <?php if ($this->_tpl_vars['ver2']): ?>
	     <input type="text" name="num_corr"  value="<?php echo $this->_tpl_vars['correlativo']['num_act']; ?>
" size="12" readonly="false"/>
	 <?php endif; ?>    
	</div></td>
    <td class="enunciados"><div align="left">Fecha</div></td>
	
    <td><span align="left" class="entrada" style="margin:5px;vertical-align:top;">
			<img src="../../templates/imagenes/fecha.jpg" class="imagenes"/>
			<input id="fecha_inicio" type="text" name="fecha_inicio" value="<?php echo $this->_tpl_vars['fecha']; ?>
" readonly style="border:none;"  />
		</span>
        <input type="button" id="fecha_boton" class="boton" value="..." /></td>
    <td class="enunciados"><div align="left">Dolar $</div></td>
    <td class="body-sector">
	  <?php $_from = $this->_tpl_vars['moneda']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
		
	  <?php if ($this->_tpl_vars['item']['id'] == 1): ?>
	   <?php if ($this->_tpl_vars['fecha'] > $this->_tpl_vars['item']['fecha_ini']): ?>	 
		<input id="cambioDolar" type="text" name="tipo"  size="12"  value="<?php echo ((is_array($_tmp=$this->_tpl_vars['item']['tipo_cambio'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
"   style="border: #FF3333 solid 1px; color:#FF3333" title="Tipo cambio dolar no actualizado"/><div style="font-size:10px"><?php echo $this->_tpl_vars['item']['fecha_ini']; ?>
</div>
		<?php else: ?>
      <input id="cambioDolar"  type="text" name="tipo"  size="12" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['item']['tipo_cambio'])) ? $this->_run_mod_handler('commify', true, $_tmp, 2) : smarty_modifier_commify($_tmp, 2)); ?>
" /><div style="font-size:10px">		       <?php echo $this->_tpl_vars['item']['fecha_ini']; ?>
</div>
       <?php endif; ?>
	 <?php endif; ?>
	 <?php endforeach; endif; unset($_from); ?>	</td>
  </tr>
  <tr>
    <td class="enunciados"><div align="left">Cantidad</div></td>
    <td colspan="3"><input type="text" name="cantidad"  value="1" size="12"/></td>
    <td class="enunciados"><div align="left">Unidad</div></td>
    <td><select name="unidad" id="select" >
      <option value="">seleccionar</option>
      <option value="Pza">Pza</option>
      <option value="GLB">GLB</option>
      <option value="Juego">Juego</option>
    </select></td>
    <td class="enunciados"><div align="left">UFV</div></td>
	
    <td class="body-sector" >
	<?php $_from = $this->_tpl_vars['moneda']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
	
	 <?php if ($this->_tpl_vars['item']['id'] == 2): ?>
	   <?php if ($this->_tpl_vars['fecha'] > $this->_tpl_vars['item']['fecha_ini']): ?>	 
		<input id="cambioUfv" type="text" name="ufv"  size="12"  value="<?php echo $this->_tpl_vars['item']['tipo_cambio']; ?>
"   style="border: #FF3333 solid 1px; color: #FF3333" title="Tipo cambio UFV no actualizado"/><div style="font-size:10px"><?php echo $this->_tpl_vars['item']['fecha_ini']; ?>
</div>
		
		<?php else: ?>
	    <input id="cambioUfv" type="text" name="ufv"  size="12" value="<?php echo $this->_tpl_vars['item']['tipo_cambio']; ?>
"  />
	    <div style="font-size:10px">							        <?php echo $this->_tpl_vars['item']['fecha_ini']; ?>
</div>
	   <?php endif; ?>
	 <?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>	</td>
  </tr>
  <tr>
  <td height="23" ></td>
  <td colspan="3"></td>
  <td></td>
  <td></td>
  <td align="left" class="body-sector"><div align="left"><a href="javascript:ventanaSecundaria1 ('tipo_cambio.php?popup=true')">Ingresar T/C </a></div></td>
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
  	
	<td colspan="4"> </td>
	<td colspan="2"></td>
	<td class="error-box" colspan="2"><div align="left"><?php echo $this->_tpl_vars['errores']['err_vida']; ?>
 </div></td>	
  </tr>
  <?php endif; ?>
  
  
  <tr>
    <td class="enunciados"><div align="left">Adquisici&oacute;n</div></td>
    <td><select name="adqui" id="ad" >
		 
		 <option value="selc">Seleccionar</option>  

	
  <?php $_from = $this->_tpl_vars['adqui']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>	       
        
     
      <option value="<?php echo $this->_tpl_vars['item']['ad_id']; ?>
" <?php if ($this->_tpl_vars['ad']['ad_id'] == $this->_tpl_vars['item']['ad_id']): ?>selected="selected"<?php endif; ?> ><?php echo $this->_tpl_vars['item']['nombre']; ?>
</option>
 
	  
	<?php endforeach; endif; unset($_from); ?>	 
  
    </select> </td>
    <td class="enunciados">N&uacute;mero</td>
    <td><input type="text" name="det_adqui"  size="12" value="<?php echo $this->_tpl_vars['det_adqui']; ?>
"/></td>
    <td class="enunciados"><div align="left">Serie</div></td>
    <td ><input type="text" name="serie"  size="12" value="<?php echo $this->_tpl_vars['serie']; ?>
"/></td>
    <td class="enunciados"><div align="left">Vida Util (meses) </div></td>
    <td><input type="text" name="vida_util"  size="12" value="<?php echo $this->_tpl_vars['vida_util']; ?>
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
    <td rowspan="3" class="enunciados"><div align="left">Descripci&oacute;n</div></td>
    <td colspan="5" rowspan="3"><textarea name="descripcion" cols="45" rows="4" ><?php echo $this->_tpl_vars['descripcion']; ?>
</textarea></td>
    <td class="enunciados"><div align="left">Valor Compra(Bs)</div></td>
    <td><input type="text" name="valor"  size="12" value="<?php echo $this->_tpl_vars['valor']; ?>
"/></td>
  </tr>
  <?php if ($this->_tpl_vars['errores']['err_residual'] != null): ?>
    <tr>
      <td colspan="2" class="error-box" ><div align="left"><?php echo $this->_tpl_vars['errores']['err_residual']; ?>
</div></td>
      </tr>
	
	<?php endif; ?>
	<tr>
      <td class="enunciados"><div align="left">Valor Residual(Bs)</div></td>
      <td><input type="text" name="residual"  size="12" value="<?php echo $this->_tpl_vars['residual']; ?>
"/>        </td>
      </tr>
  <tr>  </tr>
  <tr><td></td> 
  <td colspan="3"></td> 
  <td></td> 
  <td></td> 
  <td></td> 
  <td></td> </tr>
</table>

<p>&nbsp;</p>
</fieldset>
</div>

<center>
  <br>
  <input type="submit" value="Insertar Activo" name="insertar_activo" class="enviar" onmouseover="this.className='boton'" onmouseout="this.className='enviar'">
</center>
</form>
<?php echo '
<script type="text/javascript">
  							 Calendar.setup({
						     inputField     :    "fecha_inicio",     // id del campo de texto
						     ifFormat     :  "%Y-%m-%d",     // formato de la fecha que se escriba en el campo de texto
						     button     :    "fecha_boton",     // el id del botón que lanzará el calendario
							 onUpdate  :    function(){ var i21n3s = document.getElementById(\'fecha_inicio\').value;
							desplegar(i21n3s);}
							});
						</script>
'; ?>

<?php if ($this->_tpl_vars['verfoto']): ?>
<table align="center"  width="95%" cellpadding="3">
    <tr>
      <td width="13%" class="enunciados" style="text-align:center">Activo</td>
      <td width="11%" class="enunciados" style="text-align:center">Loc Primaria </td>
      <td width="14%" class="enunciados" style="text-align:center">Loc secundaria </td>
      <td width="5%" class="enunciados" style="text-align:center">Grupo</td>
      <td width="13%" class="enunciados" style="text-align:center">Num correlativo </td>
      <td width="44%" class="enunciados" style="text-align:center">Descripcion</td>
      <td width="44%" class="enunciados" style="text-align:center">&nbsp;</td>
    </tr>
 <?php $this->assign('CSSclass', 0); ?>
	 <?php $this->assign('clases', "lista-normal"); ?>
    
		<?php $this->assign('CSSclass', ($this->_tpl_vars['CSSclass']+1)); ?>
	    <?php if (( $this->_tpl_vars['CSSclass'] % 2 ) == 0): ?>
		    <?php $this->assign('clases', "lista-seleccionada"); ?>
	    <?php else: ?>
		    <?php $this->assign('clases', "lista-normal"); ?>
	    <?php endif; ?>
    <tr class="<?php echo $this->_tpl_vars['clases']; ?>
" onmouseover="this.style.cursor = 'pointer';this.className = 'lista_terrible';" onmouseout="this.className = '<?php echo $this->_tpl_vars['clases']; ?>
';" align="center"> 
     
	  
      <td align="center">
		<?php echo $this->_tpl_vars['mostrar_foto']['numero']; ?>

      <td><?php if ($this->_tpl_vars['mostrar_foto']['localizacion'] < 10): ?>0<?php echo $this->_tpl_vars['mostrar_foto']['localizacion']; ?>
<?php else: ?><?php echo $this->_tpl_vars['mostrar_foto']['localizacion']; ?>
<?php endif; ?></td>
			   <td align="center">
			   <?php if ($this->_tpl_vars['mostrar_foto']['locsecundaria'] < 10): ?>0<?php echo $this->_tpl_vars['mostrar_foto']['locsecundaria']; ?>
<?php else: ?><?php echo $this->_tpl_vars['mostrar_foto']['locsecundaria']; ?>
<?php endif; ?></td>
			   
			       <td><?php if ($this->_tpl_vars['mostrar_foto']['grupo_id'] < 10): ?>0<?php echo $this->_tpl_vars['mostrar_foto']['grupo_id']; ?>
<?php else: ?><?php echo $this->_tpl_vars['mostrar_foto']['grupo_id']; ?>
<?php endif; ?></td>
                   <td><?php echo $this->_tpl_vars['mostrar_foto']['num_act']; ?>
</td>
                   <td><?php echo $this->_tpl_vars['mostrar_foto']['desact']; ?>
</td>
                   <td><a href="javascript:ventanafotografiasactivo ('activo.php?popup=true&&opcion=registrar_foto&&numero=<?php echo $this->_tpl_vars['mostrar_foto']['numero']; ?>
-<?php echo $this->_tpl_vars['mostrar_foto']['act_id']; ?>
')">Ingresar Foto </a></td>
    </tr>
 
  <tr>
    <td colspan="7"><hr>    </td>
    </tr>
  </table>
<?php endif; ?>
<div id="return-ajax" style="visibility: hidden;">123123123123123123123</div>
</body>