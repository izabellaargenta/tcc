<?php 
   include_once("validar_sessao.php"); 
   include_once("conectar.php");
?>

<html>
<head>
	<title>Curso CRUD</title>
	<meta charset="utf-8" />
	<link rel='stylesheet' href="./css/menu.css">	
</head>

<body bgcolor = '#BEF781'>
	<?php
	   if ( $_SESSION["tipo"] != "S" ) {
	      mysqli_close($bd);
          header("location: index.php?erro=2");
	   } else {
		  include_once("monta_menu.php");
	   }
    ?>	
	
	<h1>Cadastro Genérico 2</h1>
	
	<h2>Essa tela é restrita a usuários administradores</h2>
	
	<h3>Se você está vendo essa tela é por que está logado como Administrador</h3>
	
</body>

</html>
