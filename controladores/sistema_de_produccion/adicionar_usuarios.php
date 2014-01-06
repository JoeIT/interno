<html>
<head>
	<title>
		Ingresar Nuevo Usuario
	</title>
	<LINK REL="stylesheet" TYPE="text/css" HREF="../../vistas/configs/estilo.css">
	<script type="text/javascript" src="../../scripts/md5.js"></script>
	<script language="JavaScript"> 
		function comprobar(passw1,passw2)
		    {
			  
			  
		      if(passw1!=passw2)
		      {
		         alert("No coinciden las contraseñas");
		         
				    //document.form1.funcion.value="index";
					
			  }
		      else
		      {
					document.form1.hash.value = hex_md5(passw1 + "contraseña encriptada");	
		        	document.form1.submit();

		      }
		    }
	</script>
	
</head>
<body>
	<h1 align="center"> Nuevo usuario </h1>
	<form name="form1" action="index.php" method="POST">
		
		<input type="hidden" name="hash">
		
		<table align="center">
			<tr>
				<td>Nick:</td>
				<td><input type="text" name="nick"><br></td>
			</tr>
			<tr>
				<td>Password:</td>
				<td><input type="password" name="pass1"><br></td>
			</tr>
			<tr>
				<td>Repite Password:</td>
				<td><input type="password" name="pass2"><br></td>
			</tr>
			
			<tr>
				<td>Nombre:</td>
				<td><input type="text" name="nombre"><br></td>
			</tr>
			<tr>
				<td>E-mail:</td>
				<td><input type="text" name="email"><br></td>
			</tr>
			<tr>
				<td>Categoria:</td>
				<td><input type="text" name="categoria"><br></td>
			</tr>
			<tr>
				<input type="hidden" name="funcion" value="registrar">
						
				<td><br><input type="button" name="Crear" value="Registrar" onclick=comprobar(pass1.value,pass2.value)></td>
			</tr>
		</table>
	</form> 
</body>
</html>