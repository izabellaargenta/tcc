<?php

  include_once("conectar.php");

  $login = mysqli_real_escape_string($bd, $_POST["login"]);
  $senha = mysqli_real_escape_string($bd, $_POST["senha"]);
    
  $sql = "select * from usuario
          where
             login = '$login' and
             senha = '$senha' ";
  
  $resultado = mysqli_query($bd, $sql);
  
  //se o número de linhas resultante é 1
  if ( mysqli_num_rows($resultado) == 1 ) {
	  
	 session_start(); //inicia a sessão
	 
	 $dados = mysqli_fetch_assoc($resultado);
	 
	 //coloca na sessão alguns dados ...
	 $_SESSION["login"]      = $login;
	 $_SESSION["tipo"]       = $dados["tipo"];
	 $_SESSION["id_usuario"] = $dados["id_usuario"];

     mysqli_close($bd);
     
     //redireciona para o arquivo menu.php
     header("location: menu.php");
     
  } else {
     mysqli_close($bd);
     header("location: index.php?erro=1");
  }

?>
