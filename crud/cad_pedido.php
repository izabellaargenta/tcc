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
	    $id_pedido  = "";
	    $descricao  = "";
	    $dt_pedido  = "";
	    $vl_total   = "";
	    $situacao   = "";
	    $id_cliente = "";

	    
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
				$descricao  = mysqli_real_escape_string($bd, $_POST["descricao"]);
				$dt_pedido  = mysqli_real_escape_string($bd, $_POST["dt_pedido"]);
				$vl_total   = mysqli_real_escape_string($bd, $_POST["vl_total"]);
				$situacao   = mysqli_real_escape_string($bd, $_POST["situacao"]);
				$id_cliente = mysqli_real_escape_string($bd, $_POST["id_cliente"]);
			 }
			 
			 if ( strtoupper($acao) == "SALVAR" || 
			      strtoupper($acao) == "EXCLUIR" || 
			      strtoupper($acao) == "BUSCAR") { 
			    
			    /*
	               ==> Atribur a chave primária
	            */
			    $id_pedido = $_POST["id_pedido"];
			 }
			 
			 if (strtoupper($acao) == "INCLUIR") {
				
				/*
	               ==> Montar um comando SQL do tipo INSERT declarando todas as colunas e valores 
	                  (observe a necessidade de aspas em campos do tipo caracter e do tipo data.
	            */
			    $sql = "insert into pedido  
			                 (descricao, dt_pedido, vl_total, situacao, id_cliente)
			            values 
			                 ('$descricao','$dt_pedido', $vl_total, '$situacao', $id_cliente)";
			    
			    if ( ! mysqli_query($bd, $sql) ) {
					
				    /*
	                    ==> Se sua tabela tiver um campo UNIQUE (ex. login, CPF, nº de matrícula, etc., configure
	                        a mensagem abaixo (a mensagem vai aparecer quando houver uma duplicação de valor não autorizada)
	                */					
					
					if ( mysqli_errno($bd) == 1062 ) {
						$mensagem = "<h3>Prezado usuário o valor escolhido '$id_pedido' já está sendo utilizado. Escolha outro!</h3>";
					} else {
						$mensagem = "<h3>Ocorreu um erro ao inserir os dados: </h3> <h3>".mysqli_error($bd)."</h3> <h4>".mysqli_errno($bd)."</h4>";
				    }

					$descr_acao = "Incluir";
				
				} else {
			    	$descr_acao = "Salvar";
			    	/*
	                    ==> O comando mysqli_insert_id pega o último valor gerado pelo BD
	                */
			    	$id_pedido = mysqli_insert_id($bd);
				}
				 
			 } else if (strtoupper($acao) == "SALVAR") {
				 
				 $descr_acao = "Salvar";
				 
				 /*
	               ==> Montar um comando SQL do tipo UPDATE para fazer a atualização dos dados que podem ser alterados (não é obrigatório usar todos)
	                  (observe a necessidade de aspas em campos do tipo caracter e do tipo data.
	            */
				 
				 $sql = " update pedido  
				          set
				            descricao  = '$descricao',
				            dt_pedido  = '$dt_pedido',
				            vl_total   = $vl_total,
				            situacao   = '$situacao',
				            id_cliente = $id_cliente
				            
				          where
				            id_pedido = $id_pedido";
				            
				 if ( ! mysqli_query($bd, $sql) ) {
					 
				    /*
	                    ==> Se sua tabela tiver um campo UNIQUE (ex. login, CPF, nº de matrícula, etc., configure
	                        a mensagem abaixo (a mensagem vai aparecer quando houver uma duplicação de valor não autorizada)
	                */					 
					
					if ( mysqli_errno($bd) == 1062 ) {
						$mensagem = "<h3>Prezado usuário o valor escolhido '$id_pedido' já está sendo utilizado. Escolha outro!</h3>";
					} else {
						$mensagem = "<h3>Ocorreu um erro ao alterar os dados: </h3> <h3>".mysqli_error($bd)."</h3>".$sql. "<h4>".mysqli_errno($bd)."</h4>";
				    }
				}         
			 } else if (strtoupper($acao) == "EXCLUIR") {
				 
				 $descr_acao = "Incluir";
				 
				 /*
	               ==> Ajustar o comando para fazer o delete
	             */
				  
				 $sql = "delete from pedido where id_pedido = $id_pedido";
				 
				 mysqli_query($bd, $sql);
				 
			 } else if (strtoupper($acao) == "BUSCAR") {
				 
				 $descr_acao = "Salvar";
				 
				 /*
	               ==> Ajustar o comando para buscar um único registro a partir da chave primária
	             */
				 
				 $sql = "select * from pedido  where id_pedido = $id_pedido";
				 
				 $resultado = mysqli_query($bd, $sql);
				 
				 if ( mysqli_num_rows($resultado) == 1 ) {
					 
					$dados = mysqli_fetch_assoc($resultado);
					
					/*
	                   ==> Atribur os dados recebidos do Banco de Dados às variáveis (inclusive a chave primária)
	                */
	 
					$id_pedido  = $dados["id_pedido"];
					$descricao  = $dados["descricao"];
					$dt_pedido  = $dados["dt_pedido"];
					$vl_total   = $dados["vl_total"];
					$situacao   = $dados["situacao"];
					$id_cliente = $dados["id_cliente"];
				 }
			 }
		}
		
		/*
	        ==> Ajustar o comando para listar todos os dados existentes
	    */		
		
		$sql_listar = "select pedido.*, cliente.nome
		               from pedido, cliente
		               where pedido.id_cliente = cliente.id_cliente
		               $sqlExtra
		               order by dt_pedido desc ";
		
		$lista = mysqli_query($bd, $sql_listar);
		
		if ( mysqli_num_rows($lista) > 0 ) {
			
		     /*
	           ==> Definir quais serão as informações que devem ser listadas na tabela 
	               (observe que as duas últimas colunas são os botões de alterar e excluir, se não for possível excluir, retire o botão)
	         */				
			
			$tabela = "<table border=1>";
			$tabela = $tabela."<tr><th>Descrição</th><th>Cliente</th><th>Data do Pedido</th><th>Valor Total (R$)</th><th>Situação</th><th>Itens</th><th>Alterar</th><th>Excluir</th></tr>";
			
			while ( $dados = mysqli_fetch_assoc($lista)) {
				
			    /*
	                ==> Definir nomes de variáveis que irão receber os dados do BD e listá-los na tabela
	                    Sugestão ... use a letra "v" na frente (estes nomes de variáveis devem ser distintos das variáveis declaradas anteriormente)
	            */	
				
				$vIdPedido     = $dados["id_pedido"];
				$vDescricao    = $dados["descricao"];
				$vDtPedido     = $dados["dt_pedido"];
				$vVlTotal      = $dados["vl_total"];
				$vSituacao     = $dados["situacao"];
				$vNomeCliente  = $dados["nome"];
				
				//imagem do botão ALTERAR (ele vai buscar os dados a partir da chave primária e posicioná-los na tela)
				$alterar = "<center><form method='post'>
				               <input type='hidden' name='id_pedido' value='$vIdPedido'>
				               <input type='hidden' name='acao' value='BUSCAR'>
				               <input type='image' src='./img/alterar.gif' value='submit'>
				            </form></center> ";
				
				//imagem do botão EXCLUIR (ele vai excluir os dados a partir da chave primária)            
				$excluir = "<center><form method='post'>
				               <input type='hidden' name='id_pedido' value='$vIdPedido'>
				               <input type='hidden' name='acao' value='EXCLUIR'>
				               <input type='image' src='./img/excluir.gif' value='submit'>
				            </form></center>";
				            
				//imagem do botão ITENS (ele vai chamar a tela de cadastro de itens do pedido)            
				$itens = "<center><form action='cad_itens_pedido.php' method='post'>
				               <input type='hidden' name='id_pedido' value='$vIdPedido'>
				               <input type='image' src='./img/itens.gif' value='submit'>
				            </form></center>";				            
				            
				            
			    /*
	                ==> Fique atento à ordem dos valores (respeite a mesma ordem dos títulos das colunas)
	            */
				$tabela = $tabela."<tr><td>$vDescricao</td><td>$vNomeCliente</td><td>$vDtPedido</td><td>$vVlTotal</td><td>$vSituacao</td><td>$itens</td><td>$alterar</td><td>$excluir</td></td>";
				            
			}
			
			$tabela = $tabela."</table>";
			
		} else {
			$tabela = "não há dados para listar";
		}

        /*
	       ==> Utilize essas três variáveis se você quer construir um menu suspenso (comboBox) para escolha de valores
	    */

		$situacaoVal  = array("N","C","E");
		$situacaoDescr  = array("Novo", "Cancelado", "Encerrado/Entregue");
		$situacaoOpcoes = montaSelect($situacaoVal, $situacaoDescr, $situacao, false); 
		
		$sqlClientes = "select id_cliente, nome from cliente order by nome";
		$clienteOpcoes = montaSelectBD($bd, $sqlClientes, $id_cliente, false);
	    
	    mysqli_close($bd);
	?>
	
	<h2>Cadastro de Pedidos</h2>
	
	<?php echo $mensagem; ?>
	
	<form method="post">
      <fieldset>
	    <legend>Dados para o cadastro:</legend>
	        
	    <label for="descricao" class="campo">Descrição: </label>
	    <input type="text" id="descricao" name="descricao" size="100" value="<?php echo $descricao; ?>" <?php echo $podeAlterar; ?> > <br>

	    <label for="id_cliente" class="campo">Cliente: </label>
	    <select id="id_cliente" name="id_cliente" <?php echo $podeAlterar; ?> >
	      <?php echo $clienteOpcoes; ?>
	    </select><br>
	        
	    <label for="dt_pedido" class="campo">Data do Pedido: </label>
	    <input type="date" id="dt_pedido" name="dt_pedido" value="<?php echo $dt_pedido; ?>" <?php echo $podeAlterar; ?> > <br>
	      
	    <label for="vl_total" class="campo">Valor Total: </label>
	    <input type="number" id="vl_total" name="vl_total" step="0.01" value="<?php echo $vl_total; ?>" <?php echo $podeAlterar; ?> > <br>
	    
	    <label for="situacao" class="campo">Situação: </label>
	    <select id="situacao" name="situacao" <?php echo $podeAlterar; ?> >
	      <?php echo $situacaoOpcoes; ?>
	    </select><br>
	    
	    <!-- Chave Primária ... MUITO IMPORTANTE -->    
	    <input type="hidden" name="id_pedido" value="<?php echo $id_pedido; ?>">
	        
	  </fieldset>
	  
      <input type='submit' value='Novo' <?php echo $podeAlterar; ?> > 
	  <input type="submit" tabindex="1" name="acao" value="<?php echo $descr_acao; ?>" <?php echo $podeAlterar; ?> >
	</form>
	
	<br>
	
	<fieldset>
	   <legend>Pedidos Cadastrados</legend>
	   
	   <?php
	      echo $tabela;
	   ?>
	   
	        
	</fieldset>	
	
</body>

</html>
