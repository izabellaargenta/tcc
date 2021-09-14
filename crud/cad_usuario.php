<?php include_once("validar_sessao.php"); ?>

<!DOCTYPE html>

<html>

<head>
	<title>Curso CRUD</title>
	<meta charset="utf-8" />
	<link rel='stylesheet' href="./css/menu.css">	
	<link rel='stylesheet' href="./css/formularios.css">	
	
</head>

<body>
	
	<?php 
	    include_once("conectar.php");
	    include_once("funcoes.php");
	    include_once("monta_menu.php"); 
	    
	    $mensagem = "";
	    $tabela = "";
	    
	    $id_usuario = "";
	    $login = "";
	    $nome = "";
	    $senha = "";
	    $tipo = "";
	    
	    $podeAlterar = "";
	    $sqlExtra = "";
	    
	    if ( $_SESSION["tipo"] == "U" ) {
	       $sqlExtra = " where id_usuario = ".$_SESSION["id_usuario"];
	       $podeAlterar = " disabled ";   
	    }
	    
	    if ( ! isset($_POST["acao"] ) ) {
	        $descr_acao = "Incluir";
		} else {
			 $acao = $_POST["acao"];
			 
			 if ( strtoupper($acao) == "INCLUIR" || 
			      strtoupper($acao) == "SALVAR" ) {

				$login  = mysqli_real_escape_string($bd, $_POST["login"]);
				$nome   = mysqli_real_escape_string($bd, $_POST["nome"]);
				$senha  = mysqli_real_escape_string($bd, $_POST["senha"]);
				$tipo   = mysqli_real_escape_string($bd, $_POST["tipo"]);
			 }
			 
			 if ( strtoupper($acao) == "SALVAR" || 
			      strtoupper($acao) == "EXCLUIR" || 
			      strtoupper($acao) == "BUSCAR") { 
			    
			    //Chave(s) primária(s)
			    $id_usuario = $_POST["id_usuario"];
			 }
			 
			 if (strtoupper($acao) == "INCLUIR") {
				 
			    $sql = "insert into usuario 
			                 (login, nome, senha, tipo)
			            values 
			                 ('$login','$nome', '$senha','$tipo')";
			    
			    if ( ! mysqli_query($bd, $sql) ) {
					
					if ( mysqli_errno($bd) == 1062 ) {
						$mensagem = "<h3>Prezado usuário o login 
						    escolhido '$login' já está sendo 
						    utilizado. Escolha outro!</h3>";
					} else {
						$mensagem = "<h3>Ocorreu um erro ao 
						inserir os dados: </h3> 
						<h3>".mysqli_error($bd)."</h3> 
						<h4>".mysqli_errno($bd)."</h4>";
				    }

					$descr_acao = "Incluir";
				
				} else {
			    	$descr_acao = "Salvar";
			    	$id_usuario = mysqli_insert_id($bd);
				}
				 
			 } else if (strtoupper($acao) == "SALVAR") {
				 
				 $descr_acao = "Salvar";
				 
				 $sql = " update usuario 
				          set
				            login = '$login',
				            nome = '$nome',
				            senha = '$senha',
				            tipo = '$tipo'
				          where
				            id_usuario = $id_usuario";
				            
				 if ( ! mysqli_query($bd, $sql) ) {
					
					if ( mysqli_errno($bd) == 1062 ) {
						$mensagem = "<h3>Prezado usuário o login 
						    escolhido '$login' já está sendo 
						    utilizado. Escolha outro!</h3>";
					} else {
						$mensagem = "<h3>Ocorreu um erro ao 
						alterar os dados: </h3> 
						<h3>".mysqli_error($bd)."</h3>".$sql. 						
						"<h4>".mysqli_errno($bd)."</h4>";
				    }
				}         
			 } else if (strtoupper($acao) == "EXCLUIR") {
				 
				 $descr_acao = "Incluir";
				  
				 $sql = "delete from usuario where id_usuario = $id_usuario";
				 
				 mysqli_query($bd, $sql);
				 
			 } else if (strtoupper($acao) == "BUSCAR") {
				 
				 $descr_acao = "Salvar";
				 
				 $sql = "select * from usuario where id_usuario = $id_usuario";
				 
				 $resultado = mysqli_query($bd, $sql);
				 
				 if ( mysqli_num_rows($resultado) == 1 ) {
					 
					$dados = mysqli_fetch_assoc($resultado);
					 
					$id_usuario = $dados["id_usuario"];
					$nome       = $dados["nome"];
					$login      = $dados["login"];
					$senha      = $dados["senha"];
					$tipo       = $dados["tipo"];
				 }
			 }
		}
		
		$sql_listar = "select * 
		               from usuario 
		               $sqlExtra
		               order by nome ";
		
		$lista = mysqli_query($bd, $sql_listar);
		
		if ( mysqli_num_rows($lista) > 0 ) {
			
			$tabela = "<table border=1>";
			$tabela = $tabela."<tr><th>Nome</th><th>Login</th><th>Tipo</th><th>Alterar</th><th>Excluir</th></tr>";
			
			while ( $dados = mysqli_fetch_assoc($lista)) {
				$vIdUsuario  = $dados["id_usuario"];
				$vNome       = $dados["nome"];
				$vLogin      = $dados["login"];
				$vSenha      = $dados["senha"];
				$vTipo       = $dados["tipo"];
				
				$alterar = "<center><form method='post'>
				               <input type='hidden' name='id_usuario' value='$vIdUsuario'>
				               <input type='hidden' name='acao' value='BUSCAR'>
				               <input type='image' src='./img/alterar.gif' value='submit'>
				            </form></center> ";
				            
				$excluir = "<center><form method='post'>
				               <input type='hidden' name='id_usuario' value='$vIdUsuario'>
				               <input type='hidden' name='acao' value='EXCLUIR'>
				               <input type='image' src='./img/excluir.gif' value='submit'>
				            </form></center>";
				            
				
				$tabela = $tabela."<tr><td>$vNome</td><td>$vLogin</td><td>$vTipo</td><td>$alterar</td><td>$excluir</td></td>";
				            
			}
			
			$tabela = $tabela."</table>";
			
		} else {
			$tabela = "não há dados para listar";
		}

        if ($_SESSION["tipo"] == "S") { 
		    $tipoVal      = array("U","S");
		    $tipoDescr    = array("Normal","Administrador");
		} else {
		    $tipoVal  = array("U");
		    $tipoDescr  = array("Normal","Administrador");
		}
		
		$tipoOpcoes = montaSelect($tipoVal, $tipoDescr, $tipo, false); 
	    
	    mysqli_close($bd);
	?>
	
	<h2>Cadastro de Usuários</h2>
	
	<?php echo $mensagem; ?>
	
	<form action="cad_usuario.php" method="post">
      <fieldset>
	    <legend>Usuário:</legend>
	        
	    <label for="nome" class="campo">Nome: </label>
	    <input type="text" id="nome" name="nome" size="60" value="<?php echo $nome; ?>" <?php echo $podeAlterar; ?> > <br>
	        
	    <label for="login" class="campo">Login: </label>
	    <input type="text" id="login" name="login" size="60" value="<?php echo $login; ?>" <?php echo $podeAlterar; ?> > <br>
	      
	    <label for="senha" class="campo">Senha: </label>
	    <input type="password" id="senha" name="senha" size="60" value="<?php echo $senha; ?>" <?php echo $podeAlterar; ?> > <br>
        
	    <label for="tipo" class="campo">Tipo: </label>
	   
	    <select id="tipo" name="tipo" <?php echo $podeAlterar; ?> >
	      <?php echo $tipoOpcoes; ?>
	    </select><br>
	        
	    <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
	        
	  </fieldset>
	  
      <input type='submit' value='Novo' <?php echo $podeAlterar; ?> > 
	  <input type="submit" tabindex="1" name="acao" value="<?php echo $descr_acao; ?>" <?php echo $podeAlterar; ?> >
	</form>
	
	<br>
	
	<fieldset>
	   <legend>Usuários Cadastrados</legend>
	   
	   <?php
	      echo $tabela;
	   ?>
	   
	        
	</fieldset>	
	
</body>

</html>
