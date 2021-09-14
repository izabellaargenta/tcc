?php include_once("validar_sessao.php"); ?>

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
	    
	    if ( ! isset($_POST["id_pedido"] ) ) 
	        header("location: cad_pedido.php");
	    
	    /*
	     ==> Declarar uma variável para cada campo da tabela
	    */
	    $id_itens_pedido = "";
	    $id_pedido       = $_POST["id_pedido"];
	    $id_produto      = "";
	    $quantidade      = "";
	    
	    $podeAlterar = "";
	    $sqlExtra = " and itens_pedido.id_pedido = $id_pedido ";
	    

	    if ( ! isset($_POST["acao"] ) ) {
	        $descr_acao = "Incluir";
		} else {
			 $acao = $_POST["acao"];
			 
			 if ( strtoupper($acao) == "INCLUIR" || 
			      strtoupper($acao) == "SALVAR" ) {
	            /*
	               ==> Atribur os dados recebidos por POST às variáveis (exceto a chave primária)
	            */					  
				$id_pedido   = mysqli_real_escape_string($bd, $_POST["id_pedido"]);
				$id_produto   = mysqli_real_escape_string($bd, $_POST["id_produto"]);
				$quantidade   = mysqli_real_escape_string($bd, $_POST["quantidade"]);
			 }
			 
			 if ( strtoupper($acao) == "SALVAR" || 
			      strtoupper($acao) == "EXCLUIR" || 
			      strtoupper($acao) == "BUSCAR") { 
			    
			    /*
	               ==> Atribur a chave primária
	            */
			    $id_itens_pedido = $_POST["id_itens_pedido"];
			 }
			 
			 if (strtoupper($acao) == "INCLUIR") {
				
				/*
	               ==> Montar um comando SQL do tipo INSERT declarando todas as colunas e valores 
	                  (observe a necessidade de aspas em campos do tipo caracter e do tipo data.
	            */
			    $sql = "insert into itens_pedido  
			                 (id_pedido, id_produto, quantidade)
			            values 
			                 ($id_pedido, $id_produto, $quantidade)";
			    
			    if ( ! mysqli_query($bd, $sql) ) {
					
				    /*
	                    ==> Se sua tabela tiver um campo UNIQUE (ex. login, CPF, nº de matrícula, etc., configure
	                        a mensagem abaixo (a mensagem vai aparecer quando houver uma duplicação de valor não autorizada)
	                */					
					
					if ( mysqli_errno($bd) == 1062 ) {
						$mensagem = "<h3>Prezado usuário o produto informado já faz parte deste pedido. Escolha outro!</h3>";
					} else {
						$mensagem = "<h3>Ocorreu um erro ao inserir os dados: </h3> <h3>".mysqli_error($bd)."</h3> <h4>".mysqli_errno($bd)."</h4>";
				    }

					$descr_acao = "Incluir";
				
				} else {
			    	$descr_acao = "Salvar";
			    	/*
	                    ==> O comando mysqli_insert_id pega o último valor gerado pelo BD
	                */
			    	$id_itens_pedido = mysqli_insert_id($bd);
				}
				 
			 } else if (strtoupper($acao) == "SALVAR") {
				 
				 $descr_acao = "Salvar";
				 
				 /*
	               ==> Montar um comando SQL do tipo UPDATE para fazer a atualização dos dados que podem ser alterados (não é obrigatório usar todos)
	                  (observe a necessidade de aspas em campos do tipo caracter e do tipo data.
	            */
				 
				 $sql = " update itens_pedido  
				          set
				            id_pedido  = $id_pedido,
				            id_produto = $id_produto,
				            quantidade = $quantidade				            
				          where
				            id_itens_pedido = $id_itens_pedido";
				            
				 if ( ! mysqli_query($bd, $sql) ) {
					 
				    /*
	                    ==> Se sua tabela tiver um campo UNIQUE (ex. login, CPF, nº de matrícula, etc., configure
	                        a mensagem abaixo (a mensagem vai aparecer quando houver uma duplicação de valor não autorizada)
	                */					 
					
					if ( mysqli_errno($bd) == 1062 ) {
						$mensagem = "<h3>Prezado usuário o produto informado já faz parte deste pedido. Escolha outro!</h3>";
					} else {
						$mensagem = "<h3>Ocorreu um erro ao alterar os dados: </h3> <h3>".mysqli_error($bd)."</h3>".$sql. "<h4>".mysqli_errno($bd)."</h4>";
				    }
				}         
			 } else if (strtoupper($acao) == "EXCLUIR") {
				 
				 $descr_acao = "Incluir";
				 
				 /*
	               ==> Ajustar o comando para fazer o delete
	             */
				  
				 $sql = "delete from itens_pedido where id_itens_pedido = $id_itens_pedido";
				 
				 mysqli_query($bd, $sql);
				 
			 } else if (strtoupper($acao) == "BUSCAR") {
				 
				 $descr_acao = "Salvar";
				 
				 /*
	               ==> Ajustar o comando para buscar um único registro a partir da chave primária
	             */
				 
				 $sql = "select * from itens_pedido where id_itens_pedido = $id_itens_pedido";
				 
				 $resultado = mysqli_query($bd, $sql);
				 
				 if ( mysqli_num_rows($resultado) == 1 ) {
					 
					$dados = mysqli_fetch_assoc($resultado);
					
					/*
	                   ==> Atribur os dados recebidos do Banco de Dados às variáveis (inclusive a chave primária)
	                */
	 
					$id_itens_pedido = $dados["id_itens_pedido"];
					$id_pedido       = $dados["id_pedido"];
					$id_produto      = $dados["id_produto"];
					$quantidade      = $dados["quantidade"];
				 }
			 }
		}
		
		/*
	        ==> Ajustar o comando para listar todos os dados existentes
	    */		
		
		$sql_listar = "select itens_pedido.*, produto.descricao
		               from itens_pedido, produto
		               where itens_pedido.id_produto = produto.id_produto
		               $sqlExtra
		               order by id_pedido, produto.descricao ";
		
		$lista = mysqli_query($bd, $sql_listar);
		
		if ( mysqli_num_rows($lista) > 0 ) {
			
		     /*
	           ==> Definir quais serão as informações que devem ser listadas na tabela 
	               (observe que as duas últimas colunas são os botões de alterar e excluir, se não for possível excluir, retire o botão)
	         */				
			
			$tabela = "<table border=1>";
			$tabela = $tabela."<tr><th>Id. Pedido</th><th>Produto</th><th>quantidade</th><th>Alterar</th><th>Excluir</th></tr>";
			
			while ( $dados = mysqli_fetch_assoc($lista)) {
				
			    /*
	                ==> Definir nomes de variáveis que irão receber os dados do BD e listá-los na tabela
	                    Sugestão ... use a letra "v" na frente (estes nomes de variáveis devem ser distintos das variáveis declaradas anteriormente)
	            */	
				
				$vIdItensPedido  = $dados["id_itens_pedido"];
				$vIdPedido       = $dados["id_pedido"];
				$vIdProduto      = $dados["id_produto"];
				$vQuantidade     = $dados["quantidade"];
				$vProduto        = $dados["descricao"]; 
				
				//imagem do botão ALTERAR (ele vai buscar os dados a partir da chave primária e posicioná-los na tela)
				$alterar = "<center><form method='post'>
				               <input type='hidden' name='id_itens_pedido' value='$vIdItensPedido'>
				               <input type='hidden' name='id_pedido' value='$vIdPedido'>
				               <input type='hidden' name='acao' value='BUSCAR'>
				               <input type='image' src='./img/alterar.gif' value='submit'>
				            </form></center> ";
				
				//imagem do botão EXCLUIR (ele vai excluir os dados a partir da chave primária)            
				$excluir = "<center><form method='post'>
				               <input type='hidden' name='id_itens_pedido' value='$vIdItensPedido'>
				               <input type='hidden' name='id_pedido' value='$vIdPedido'>
				               <input type='hidden' name='acao' value='EXCLUIR'>
				               <input type='image' src='./img/excluir.gif' value='submit'>
				            </form></center>";
				            
			    /*
	                ==> Fique atento à ordem dos valores (respeite a mesma ordem dos títulos das colunas)
	            */
				$tabela = $tabela."<tr><td>$vIdPedido</td><td>$vProduto</td><td>$vQuantidade</td><td>$alterar</td><td>$excluir</td></td>";
				            
			}
			
			$tabela = $tabela."</table>";
			
		} else {
			$tabela = "não há dados para listar";
		}

        /*
	       ==> Utilize essas três variáveis se você quer construir um menu suspenso (comboBox) para escolha de valores
	    */
	    
	    $sqlProduto = "select id_produto, descricao from produto order by descricao";
	    $produtoOpcoes = montaSelectBD($bd, $sqlProduto, $id_produto, false);
	    
	    $sqlPedido = "select id_pedido, descricao from pedido order by descricao";
	    $pedidoOpcoes = montaSelectBD($bd, $sqlPedido, $id_pedido, false);

	    
	    mysqli_close($bd);
	?>
	
	<h2>Cadastro de Itens de um Pedido</h2>
	
	<?php echo $mensagem; ?>
	
	<form method="post">
      <fieldset>
	    <legend>Dados para o cadastro:</legend>
	        
	    <label for="pedido" class="campo">Pedido: </label>
	    <select id="pedido" name="pedido" disabled>
	        <?php echo $pedidoOpcoes; ?>    
	    </select><br>
	    
	    <label for="id_produto" class="campo">Produto: </label>
	    <select id="id_produto" name="id_produto">
	        <?php echo $produtoOpcoes; ?>    
	    </select><br>	    

	    <label for="quantidade" class="campo">Quantidade: </label>
	    <input type="number" id="quantidade" name="quantidade" step="1" value="<?php echo $quantidade; ?>" <?php echo $podeAlterar; ?> > <br>
	    
	    <!-- Chave Primária ... MUITO IMPORTANTE -->    
	    <input type="hidden" name="id_itens_pedido" value="<?php echo $id_itens_pedido; ?>">
	    
	    <!-- Chave Estrangeira ... MUITO IMPORTANTE -->    
	    <input type="hidden" name="id_pedido" value="<?php echo $id_pedido; ?>">
	        
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
