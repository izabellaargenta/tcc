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
	    
	    /*
	     ==> Declarar uma variável para cada campo da tabela
	    */
	    $id_cliente    = "";
	    $nome          = "";
	    $cpf           = "";
	    $dt_nasc       = "";
	    $id_est_civil  = "";
	    $sexo          = "";
	    
	    $podeAlterar = "";
	    $sqlExtra = "";

	    if ( ! isset($_POST["acao"] ) ) {
	        $descr_acao = "Incluir";
		} else {
			 $acao = $_POST["acao"];
			 
			 if ( strtoupper($acao) == "INCLUIR" || 
			      strtoupper($acao) == "SALVAR" ) {
	            /*
	               ==> Atribur os dados recebidos por POST às variáveis (exceto a chave primária)
	            */					  
				$id_cliente     = mysqli_real_escape_string($bd, $_POST["id_cliente"]);
				$nome           = mysqli_real_escape_string($bd, $_POST["nome"]);
				$cpf            = mysqli_real_escape_string($bd, $_POST["cpf"]);
				$dt_nasc        = mysqli_real_escape_string($bd, $_POST["dt_nasc"]);
				$id_est_civil   = mysqli_real_escape_string($bd, $_POST["id_est_civil"]);
				$sexo           = mysqli_real_escape_string($bd, $_POST["sexo"]);
			 }
			 
			 if ( strtoupper($acao) == "SALVAR" || 
			      strtoupper($acao) == "EXCLUIR" || 
			      strtoupper($acao) == "BUSCAR") { 
			    
			    /*
	               ==> Atribur a chave primária
	            */
			    $id_cliente = $_POST["id_cliente"];
			 }
			 
			 if (strtoupper($acao) == "INCLUIR") {
				
				/*
	               ==> Montar um comando SQL do tipo INSERT declarando todas as colunas e valores 
	                  (observe a necessidade de aspas em campos do tipo caracter e do tipo data.
	            */
			    $sql = "insert into cliente  
			                 (nome, cpf, dt_nasc, id_est_civil, sexo)
			            values 
			                 ('$nome', '$cpf', '$dt_nasc', $id_est_civil, '$sexo')";
			    
			    if ( ! mysqli_query($bd, $sql) ) {
					
				    /*
	                    ==> Se sua tabela tiver um campo UNIQUE (ex. login, CPF, nº de matrícula, etc., configure
	                        a mensagem abaixo (a mensagem vai aparecer quando houver uma duplicação de valor não autorizada)
	                */					
					
					if ( mysqli_errno($bd) == 1062 ) {
						$mensagem = "<h3>Prezado usuário o valor escolhido '$cpf' já está sendo utilizado. Escolha outro!</h3>";
					} else {
						$mensagem = "<h3>Ocorreu um erro ao inserir os dados: </h3> <h3>".mysqli_error($bd)."</h3> <h4>".mysqli_errno($bd)."</h4>";
				    }

					$descr_acao = "Incluir";
				
				} else {
			    	$descr_acao = "Salvar";
			    	/*
	                    ==> O comando mysqli_insert_id pega o último valor gerado pelo BD
	                */
			    	$id_cliente = mysqli_insert_id($bd);
				}
				 
			 } else if (strtoupper($acao) == "SALVAR") {
				 
				 $descr_acao = "Salvar";
				 
				 /*
	               ==> Montar um comando SQL do tipo UPDATE para fazer a atualização dos dados que podem ser alterados (não é obrigatório usar todos)
	                  (observe a necessidade de aspas em campos do tipo caracter e do tipo data.
	            */
				 
				 $sql = " update cliente  
				          set
				            nome     = '$nome',
				            cpf      = '$cpf',
				            dt_nasc  = '$dt_nasc',
				            id_est_civil = $id_est_civil,
				            sexo = '$sexo'
				            
				          where
				            id_cliente = $id_cliente";
				            
				 if ( ! mysqli_query($bd, $sql) ) {
					 
				    /*
	                    ==> Se sua tabela tiver um campo UNIQUE (ex. login, CPF, nº de matrícula, etc., configure
	                        a mensagem abaixo (a mensagem vai aparecer quando houver uma duplicação de valor não autorizada)
	                */					 
					
					if ( mysqli_errno($bd) == 1062 ) {
						$mensagem = "<h3>Prezado usuário o valor escolhido '$cpf' já está sendo utilizado. Escolha outro!</h3>";
					} else {
						$mensagem = "<h3>Ocorreu um erro ao alterar os dados: </h3> <h3>".mysqli_error($bd)."</h3>".$sql. "<h4>".mysqli_errno($bd)."</h4>";
				    }
				}         
			 } else if (strtoupper($acao) == "EXCLUIR") {
				 
				 $descr_acao = "Incluir";
				 
				 /*
	               ==> Ajustar o comando para fazer o delete
	             */
				  
				 $sql = "delete from cliente where id_cliente = $id_cliente";
				 
				 mysqli_query($bd, $sql);
				 
			 } else if (strtoupper($acao) == "BUSCAR") {
				 
				 $descr_acao = "Salvar";
				 
				 /*
	               ==> Ajustar o comando para buscar um único registro a partir da chave primária
	             */
				 
				 $sql = "select * from cliente  where id_cliente = $id_cliente";
				 
				 $resultado = mysqli_query($bd, $sql);
				 
				 if ( mysqli_num_rows($resultado) == 1 ) {
					 
					$dados = mysqli_fetch_assoc($resultado);
					
					/*
	                   ==> Atribur os dados recebidos do Banco de Dados às variáveis (inclusive a chave primária)
	                */
					$id_cliente   = $dados["id_cliente"];
					$nome         = $dados["nome"];
					$cpf          = $dados["cpf"];
					$dt_nasc      = $dados["dt_nasc"];
					$id_est_civil = $dados["id_est_civil"];
					$sexo         = $dados["sexo"];
				 }
			 }
		}
		
		/*
	        ==> Ajustar o comando para listar todos os dados existentes
	    */		
		
		$sql_listar = "select cliente.*, estado_civil.descricao 
		               from cliente, estado_civil
		               where cliente.id_est_civil = estado_civil.id_est_civil 
		               $sqlExtra
		               order by cliente.nome ";
		
		$lista = mysqli_query($bd, $sql_listar);
		
		if ( mysqli_num_rows($lista) > 0 ) {
			
		     /*
	           ==> Definir quais serão as informações que devem ser listadas na tabela 
	               (observe que as duas últimas colunas são os botões de alterar e excluir, se não for possível excluir, retire o botão)
	         */				
			
			$tabela = "<table border=1>";
			$tabela = $tabela."<tr><th>Nome</th><th>CPF</th><th>Dt. Nasc.</th><th>Estado Civil</th><th>Sexo</th><th>Alterar</th><th>Excluir</th></tr>";
			
			while ( $dados = mysqli_fetch_assoc($lista)) {
				
			    /*
	                ==> Definir nomes de variáveis que irão receber os dados do BD e listá-los na tabela
	                    Sugestão ... use a letra "v" na frente (estes nomes de variáveis devem ser distintos das variáveis declaradas anteriormente)
	            */	
				
				$vIdCliente    = $dados["id_cliente"];
				$vNome         = $dados["nome"];
				$vCPF          = $dados["cpf"];
				$vDtNascimento = $dados["dt_nasc"];
				$vIdEstCivil   = $dados["id_est_civil"];
				$vDescrEstCivil= $dados["descricao"];
				$vSexo         = $dados["sexo"];
				
				//imagem do botão ALTERAR (ele vai buscar os dados a partir da chave primária e posicioná-los na tela)
				$alterar = "<center><form method='post'>
				               <input type='hidden' name='id_cliente' value='$vIdCliente'>
				               <input type='hidden' name='acao' value='BUSCAR'>
				               <input type='image' src='./img/alterar.gif' value='submit'>
				            </form></center> ";
				
				//imagem do botão EXCLUIR (ele vai excluir os dados a partir da chave primária)            
				$excluir = "<center><form method='post'>
				               <input type='hidden' name='id_cliente' value='$vIdCliente'>
				               <input type='hidden' name='acao' value='EXCLUIR'>
				               <input type='image' src='./img/excluir.gif' value='submit'>
				            </form></center>";
				            
			    /*
	                ==> Fique atento à ordem dos valores (respeite a mesma ordem dos títulos das colunas)
	            */
				$tabela = $tabela."<tr><td>$vNome</td><td>$vCPF</td><td>$vDtNascimento</td><td>$vIdEstCivil - $vDescrEstCivil</td><td>$vSexo</td><td>$alterar</td><td>$excluir</td></td>";
				            
			}
			
			$tabela = $tabela."</table>";
			
		} else {
			$tabela = "não há dados para listar";
		}

        /*
	       ==> Utilize essas três variáveis se você quer construir um menu suspenso (comboBox) para escolha de valores
	    */

		$sexoVal  = array("F","M");
		$sexoDescr  = array("Feminino","Masculino");
		$sexoOpcoes = montaSelect($sexoVal, $sexoDescr, $sexo, false); 
		
		$sql_es = "select id_est_civil, descricao from estado_civil order by descricao";
		$estadoCivilOpcoes = montaSelectBD($bd, $sql_es, $id_est_civil, false);
	    
	    mysqli_close($bd);
	?>
	
	<h2>Cadastro de Clientes</h2>
	
	<?php echo $mensagem; ?>
	
	<form method="post">
      <fieldset>
	    <legend>Dados para o cadastro:</legend>
	        
	    <label for="nome" class="campo">Nome: </label>
	    <input type="text" id="nome" name="nome" size="100" value="<?php echo $nome; ?>" <?php echo $podeAlterar; ?> > <br>
	    
	    <label for="cpf" class="campo">CPF: </label>
	    <input type="text" id="cpf" name="cpf" size="14" placeholder="000.000.000-00" value="<?php echo $cpf; ?>" <?php echo $podeAlterar; ?> > <br>
	        
	    <label for="dt_nasc" class="campo">Data de Nascimento: </label>
	    <input type="date" id="dt_nasc" name="dt_nasc" value="<?php echo $dt_nasc; ?>" <?php echo $podeAlterar; ?> > <br>
	      
	    <label for="id_est_civil" class="campo">Estado Civil: </label>
        <select id="id_est_civil" name="id_est_civil">
           <?php echo $estadoCivilOpcoes; ?> 
        </select><br>
        
        
	    <label for="sexo" class="campo">Sexo: </label>
	 
	    <select id="sexo" name="sexo" <?php echo $podeAlterar; ?> >
	      <?php echo $sexoOpcoes; ?>
	    </select><br>
	    
	    <!-- Chave Primária ... MUITO IMPORTANTE -->    
	    <input type="hidden" name="id_cliente" value="<?php echo $id_cliente; ?>">
	        
	  </fieldset>
	  
      <input type='submit' value='Novo' <?php echo $podeAlterar; ?> > 
	  <input type="submit" tabindex="1" name="acao" value="<?php echo $descr_acao; ?>" <?php echo $podeAlterar; ?> >
	</form>
	
	<br>
	
	<fieldset>
	   <legend>Dados Cadastrados</legend>
	   
	   <?php
	      echo $tabela;
	   ?>
	   
	        
	</fieldset>	
	
</body>

</html>
