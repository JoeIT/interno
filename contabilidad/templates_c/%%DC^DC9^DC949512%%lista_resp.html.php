<?php /* Smarty version 2.6.18, created on 2008-11-08 10:08:16
         compiled from lista_resp.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "cabecera.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> <br />
<?php echo '

<script language="javascript">
/*function actualizar (paginaservidor,combo,lista,clave){

var miAjax = new Ajax(paginaservidor,
{
	method: \'get\',
	data:clave+\'=\'+$(combo).value,
	
	update: $(lista)
	
});
miAjax.request();

//alert (paginaservidor+" "+combo+" "+lista+" "+$(combo).value);
}*/
function actualizar (paginaservidor,combo,lista,clave){

new Ajax.Updater(lista, paginaservidor, { 
  method: \'get\', 
  parameters: { primaria: $F(combo)} 
});

//alert (paginaservidor+" "+combo+" "+lista+" "+$(combo).value);
}
</script>

			<SCRIPT LANGUAGE="JavaScript">
			function consultar(nombre,opcion)
			{
				if(opcion=="eliminar")
				{
					if(confirm("Esta seguro que desea eliminar el clip: " + nombre ))
					{
						document.form_lista.elegido.value=nombre;
						document.form_lista.funcion.value=opcion;
						document.form_lista.submit();
					}
					else
					{
						document.form_lista.funcion.value="index";
						
					} 
				}
				else
				{
					document.form_lista.elegido.value=nombre;
					document.form_lista.funcion.value=opcion;
					document.form_lista.submit();
				}
			}
			</SCRIPT>
	 '; ?>



<form name="orden" method="get" action="../controladores/activo.php"  align="center">
 
  <table  border="0" align="center" cellpadding="0" cellspacing="3">
				<tr> 
					 <th colspan="2">Responsable</th> </tr>
					
					   <tr> 
				<tr> <?php if ($this->_tpl_vars['error'] != null): ?>
					 <td class="error-box" colspan="2"> <?php echo $this->_tpl_vars['error']; ?>
</td> </tr><?php endif; ?>
					<?php if ($this->_tpl_vars['errores']['err_confirm'] != null): ?>
					   <tr> 
					 <td class="error-box" colspan="2"> <?php echo $this->_tpl_vars['errores']['err_confirm']; ?>
 </td> </tr> <?php endif; ?>

  
 
  
	
	 
  
    <td class="enunciados"><div align="left">Responsable:</div></td>
    <td class="body-sector"  >
	<div align="left" class="entrada" style="margin:5px; border:none">
	 <span class="entrada" style="margin:5px; border:none">
	 	<span class="entrada" style="margin:5px;">

		 <input type="text" name="nombre_asis2" id="nombre_asis2" style="border:none;"  onkeypress="return handleEnter(this, event)" value="<?php echo $this->_tpl_vars['nombre_asis2']; ?>
" size="40"/>
        </span>	</span><span class="entrada" style="margin:5px; border:none">
        <span class="entrada" style="margin:5px; border:none"><img src="../../templates/imagenes/combo.jpg" class="imagenes" onclick="<?php echo 'document.getElementById(\'nombre_asis2\').focus();document.getElementById(\'nombre_asis2\').select();'; ?>
" style="cursor:pointer;" alt="Seleccionar Nombre"/></span>
        <input type="hidden" name="resp_id2" id="resp_id2"  value="<?php echo $this->_tpl_vars['resp_id2']; ?>
" />
        </span><span class="entrada" style="margin:5px; border:none"><span class="entrada" style="margin:5px; border:none">
        <input type="hidden" name="resp_1" id="resp_1"  value="<?php echo $this->_tpl_vars['resp_1']; ?>
" />
        </span></span></div>
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

	  <div id="mainContent"> <a  href="javascript:ventanaSecundaria1 ('responsable.php?popup=true')"> registrar responsable </a> </div>
	  </td>
	  
    </tr>
</table>

  <center><p><input type="submit" value="Buscar" name="listar_resp" class="enviar" onmouseover="this.className='boton'" onmouseout="this.className='enviar'">
  </p></center>
  <p><?php if ($this->_tpl_vars['busqueda1']): ?>  </p>
  <table align="center" cellpadding="3">
    <tr>
      <th colspan="9"> Lista de Responsables </th>
    </tr>
    <tr>
      <td class="enunciados" style="text-align:center">Nombre</td>
      <td class="enunciados">Cargo</td>
      <td class="enunciados" style="text-align:center">Eliminar</td>
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
	<!--
	solo para modificar los activos que ya tinen sin responsables
	-->  
	  <!--<a href="../controladores/modificar_activo.php?funcion=detalle&elegido=<?php echo $this->_tpl_vars['lista1'][$this->_sections['ind']['index']]['act_id']; ?>
 " name="<?php echo $this->_tpl_vars['lista1'][$this->_sections['ind']['index']]['act_id']; ?>
" id="<?php echo $this->_tpl_vars['lista1'][$this->_sections['ind']['index']]['act_id']; ?>
"><?php echo $this->_tpl_vars['lista1'][$this->_sections['ind']['index']]['numero']; ?>
</a>
-->

		<a href="../controladores/modificar_responsable.php?funcion=detalle&elegido=<?php echo $this->_tpl_vars['resp']['resp_id']; ?>
 " name="<?php echo $this->_tpl_vars['resp']['resp_id']; ?>
" id="<?php echo $this->_tpl_vars['resp']['resp_id']; ?>
"><?php echo $this->_tpl_vars['resp']['completo']; ?>
</a>
      <td><?php echo $this->_tpl_vars['resp']['cargo']; ?>
</td>
	  <td class="body-sector"><center>
	   <a href="../controladores/modificar_responsable.php?funcion=eliminar&elegido=<?php echo $this->_tpl_vars['resp']['resp_id']; ?>
 " onclick="<?php echo 'if(confirm(\'Esta seguro de eliminar ?\') == false){return false;}'; ?>
">
				<img src="../../templates/imagenes/eliminar.gif" width="29" height="30" class="imagenes"/>				</a>	
            </center>    </tr>
    
  <tr>
    <td colspan="9"><hr>    </td>
    </tr>
  </table>
  <br />
 
<?php endif; ?>
<p>&nbsp;</p>
  <p>&nbsp;    </p>

</form>

</body>