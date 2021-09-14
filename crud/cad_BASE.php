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
	    $id_chave_primaria  = "";
	    $campo_texto  = "";
	    $campo_data  = "";
	    $campo_numerico1  = "";
	    $campo_numerico2  = "";
	    $campo_tipo  = "";
	    
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
				$campo_texto       = mysqli_real_escape_string($bd, $_POST["campo_texto"]);
				$campo_data        = mysqli_real_escape_string($bd, $_POST["campo_data"]);
				$campo_numerico1   = mysqli_real_escape_string($bd, $_POST["campo_numerico1"]);
				$campo_numerico2   = mysqli_real_escape_string($bd, $_POST["campo_numerico2"]);
				$campo_tipo        = mysqli_real_escape_string($bd, $_POST["campo_tipo"]);
			 }
			 
			 if ( strtoupper($acao) == "SALVAR" || 
			      strtoupper($acao) == "EXCLUIR" || 
			      strtoupper($acao) == "BUSCAR") { 
			    
			    /*
	               ==> Atribur a chave primária
	            */
			    $id_chave_primaria = $_POST["id_chave_primaria"];
			 }
			 
			 if (strtoupper($acao) == "INCLUIR") {
				
				/*
	               ==> Montar um comando SQL do tipo INSERT declarando todas as colunas e valores 
	                  (observe a necessidade de aspas em campos do tipo caracter e do tipo data.
	            */
			    $sql = "insert into tabela  
			                 (campo_texto, campo_data, campo_numerico1, campo_numerico2, campo_tipo)
			            values 
			                 ('$campo_texto','$campo_data', $campo_numerico1, $campo_numerico2, '$campo_tipo')";
			    
			    if ( ! mysqli_query($bd, $sql) ) {
					
				    /*
	                    ==> Se sua tabela tiver um campo UNIQUE (ex. login, CPF, nº de matrícula, etc., configure
	                        a mensagem abaixo (a mensagem vai aparecer quando houver uma duplicação de valor não autorizada)
	                */					
					
					if ( mysqli_errno($bd) == 1062 ) {
						$mensagem = "<h3>Prezado usuário o valor escolhido '$id_chave_primaria' já está sendo utilizado. Escolha outro!</h3>";
					} else {
						$mensagem = "<h3>Ocorreu um erro ao inserir os dados: </h3> <h3>".mysqli_error($bd)."</h3> <h4>".mysqli_errno($bd)."</h4>";
				    }

					$descr_acao = "Incluir";
				
				} else {
			    	$descr_acao = "Salvar";
			    	/*
	                    ==> O comando mysqli_insert_id pega o último valor gerado pelo BD
	                */
			    	$id_chave_primaria = mysqli_insert_id($bd);
				}
				 
			 } else if (strtoupper($acao) == "SALVAR") {
				 
				 $descr_acao = "Salvar";
				 
				 /*
	               ==> Montar um comando SQL do tipo UPDATE para fazer a atualização dos dados que podem ser alterados (não é obrigatório usar todos)
	                  (observe a necessidade de aspas em campos do tipo caracter e do tipo data.
	            */
				 
				 $sql = " update tabela  
				          set
				            campo_texto     = '$campo_texto',
				            campo_data      = '$campo_data',
				            campo_numerico1 = $campo_numerico1,
				            campo_numerico2 = $campo_numerico2,
				            campo_tipo      = '$campo_tipo' 
				            
				          where
				            id_chave_primaria = $id_chave_primaria";
				            
				 if ( ! mysqli_query($bd, $sql) ) {
					 
				    /*
	                    ==> Se sua tabela tiver um campo UNIQUE (ex. login, CPF, nº de matrícula, etc., configure
	                        a mensagem abaixo (a mensagem vai aparecer quando houver uma duplicação de valor não autorizada)
	                */					 
					
					if ( mysqli_errno($bd) == 1062 ) {
						$mensagem = "<h3>Prezado usuário o valor escolhido '$id_chave_primaria' já está sendo utilizado. Escolha outro!</h3>";
					} else {
						$mensagem = "<h3>Ocorreu um erro ao alterar os dados: </h3> <h3>".mysqli_error($bd)."</h3>".$sql. "<h4>".mysqli_errno($bd)."</h4>";
				    }
				}         
			 } else if (strtoupper($acao) == "EXCLUIR") {
				 
				 $descr_acao = "Incluir";
				 
				 /*
	               ==> Ajustar o comando para fazer o delete
	             */
				  
				 $sql = "delete from tabela where id_chave_primaria = $id_chave_primaria";
				 
				 mysqli_query($bd, $sql);
				 
			 } else if (strtoupper($acao) == "BUSCAR") {
				 
				 $descr_acao = "Salvar";
				 
				 /*
	               ==> Ajustar o comando para buscar um único registro a partir da chave primária
	             */
				 
				 $sql = "select * from tabela  where id_chave_primaria = $id_chave_primaria";
				 
				 $resultado = mysqli_query($bd, $sql);
				 
				 if ( mysqli_num_rows($resultado) == 1 ) {
					 
					$dados = mysqli_fetch_assoc($resultado);
					
					/*
	                   ==> Atribur os dados recebidos do Banco de Dados às variáveis (inclusive a chave primária)
	                */
	 
					$id_chave_primaria = $dados["id_chave_primaria"];
					$campo_texto       = $dados["campo_texto"];
					$campo_data        = $dados["campo_data"];
					$campo_numerico1   = $dados["campo_numerico1"];
					$campo_numerico2   = $dados["campo_numerico2"];
					$campo_tipo        = $dados["campo_tipo"];
				 }
			 }
		}
		
		/*
	        ==> Ajustar o comando para listar todos os dados existentes
	    */		
		
		$sql_listar = "select * 
		               from tabela 
		               $sqlExtra
		               order by campo_texto ";
		
		$lista = mysqli_query($bd, $sql_listar);
		
		if ( mysqli_num_rows($lista) > 0 ) {
			
		     /*
	           ==> Definir quais serão as informações que devem ser listadas na tabela 
	               (observe que as duas últimas colunas são os botões de alterar e excluir, se não for possível excluir, retire o botão)
	         */				
			
			$tabela = "<table border=1>";
			$tabela = $tabela."<tr><th>campo_texto</th><th>campo_data</th><th>campo_numerico1</th><th>campo_numerico2</th><th>campo_tipo</th><th>Alterar</th><th>Excluir</th></tr>";
			
			while ( $dados = mysqli_fetch_assoc($lista)) {
				
			    /*
	                ==> Definir nomes de variáveis que irão receber os dados do BD e listá-los na tabela
	                    Sugestão ... use a letra "v" na frente (estes nomes de variáveis devem ser distintos das variáveis declaradas anteriormente)
	            */	
				
				$vIdChavePrimaria  = $dados["id_chave_primaria"];
				$vCampoTexto       = $dados["campo_texto"];
				$vCampoData        = $dados["campo_data"];
				$vCampoNumerico1   = $dados["campo_numerico1"];
				$vCampoNumerico2   = $dados["campo_numerico2"];
				$vCampoTipo        = $dados["campo_tipo"];
				
				//imagem do botão ALTERAR (ele vai buscar os dados a partir da chave primária e posicioná-los na tela)
				$alterar = "<center><form method='post'>
				               <input type='hidden' name='id_chave_primaria' value='$vIdChavePrimaria'>
				               <input type='hidden' name='acao' value='BUSCAR'>
				               <input type='image' src='./img/alterar.gif' value='submit'>
				            </form></center> ";
				
				//imagem do botão EXCLUIR (ele vai excluir os dados a partir da chave primária)            
				$excluir = "<center><form method='post'>
				               <input type='hidden' name='id_chave_primaria' value='$vIdChavePrimaria'>
				               <input type='hidden' name='acao' value='EXCLUIR'>
				               <input type='image' src='./img/excluir.gif' value='submit'>
				            </form></center>";
				            
			    /*
	                ==> Fique atento à ordem dos valores (respeite a mesma ordem dos títulos das colunas)
	            */
				$tabela = $tabela."<tr><td>$vCampoTexto</td><td>$vCampoData</td><td>$vCampoNumerico1</td><td>$vCampoNumerico2</td><td>$vCampoTipo</td><td>$alterar</td><td>$excluir</td></td>";
				            
			}
			
			$tabela = $tabela."</table>";
			
		} else {
			$tabela = "não há dados para listar";
		}

        /*
	       ==> Utilize essas três variáveis se você quer construir um menu suspenso (comboBox) para escolha de valores
	    */

		$tipoVal  = array("A","B","C");
		$tipoDescr  = array("Tipo A", "Tipo B", "Tipo C");
		$tipoOpcoes = montaSelect($tipoVal, $tipoDescr, $campo_tipo, false); 
	    
	    mysqli_close($bd);
	?>
	
	<h2>Cadastro de TABELA</h2>
	
	<?php echo $mensagem; ?>
	
	<form method="post">
      <fieldset>
	    <legend>Dados para o cadastro:</legend>
	        
	    <label for="campo_texto" class="campo">campo_texto: </label>
	    <input type="text" id="campo_texto" name="campo_texto" size="100" value="<?php echo $campo_texto; ?>" <?php echo $podeAlterar; ?> > <br>
	        
	    <label for="campo_data" class="campo">campo_data: </label>
	    <input type="date" id="campo_data" name="campo_data" value="<?php echo $campo_data; ?>" <?php echo $podeAlterar; ?> > <br>
	      
	    <label for="campo_numerico1" class="campo">campo_numerico1: </label>
	    <input type="number" id="campo_numerico1" name="campo_numerico1" step="0.01" value="<?php echo $campo_numerico1; ?>" <?php echo $podeAlterar; ?> > <br>
	    
	    <label for="campo_numerico2" class="campo">campo_numerico2: </label>
	    <input type="number" id="campo_numerico2" name="campo_numerico2" step="1" value="<?php echo $campo_numerico2; ?>" <?php echo $podeAlterar; ?> > <br>
        
	    <label for="campo_tipo" class="campo">campo_tipo: </label>
	   
	    <select id="campo_tipo" name="campo_tipo" <?php echo $podeAlterar; ?> >
	      <?php echo $tipoOpcoes; ?>
	    </select><br>
	    
	    <!-- Chave Primária ... MUITO IMPORTANTE -->    
	    <input type="hidden" name="id_chave_primaria" value="<?php echo $id_chave_primaria; ?>">
	        
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
