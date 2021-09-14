<?php
   
   if (isset($_GET["erro"]) && $_GET["erro"] == "1")
        $mensagem = "<br><span style='color: red;'>Usuário ou senha 
                       incorretos tente novamente</span>";
   else {
	  
	  if (isset($_GET["erro"]) && $_GET["erro"] == "2")
	    $mensagem = "<br><span style='color: green;'>Sua sessão 
	       expirou ou você está tentando acessar uma página
	       sem autorização! </span>";
	  else 
	     $mensagem = "";
   }
?>

<html>

<head>
	<title>Curso CRUD</title>
	<meta charset="utf-8" />
</head>

<body>
	
	<center>
	<form action="login.php" method="post">
	 <fieldset>
	   <legend>Identifique-se</legend>
	   
	   <label for="login">Login:</label> 
	   <input type="text" id="login" name="login">
	   
	   <br>
	   
	   <label for="senha">Senha:</label>
	   <input type="password" id="senha" name="senha">
	   
	   <?php echo $mensagem; ?>
	   
	 </fieldset>

	 <input type="submit" value="Entrar">
	
	</form>
	</center>
	
</body>

</html>
