<ul>
  <li><a href="#">Principal</a></li>
  
  <?php
     if ( $_SESSION["tipo"] == "S" )
        echo "<li><a href='cad_usuario.php'>Usuários</a></li>";
     else
        echo "<li><a href='cad_usuario.php'>Meus Dados</a></li>";
        
  ?>
  
  <li><a href="cad_pedido.php">Pedidos</a></li>
  
  <?php 
     if ( $_SESSION["tipo"] == "S" ) 
        echo "
		  <li class='dropdown'>
			<a href='#' class='dropbtn'>Cadastros Básicos</a>'
			<div class='dropdown-content'>
			  <a href='cad_estado_civil.php'>Estado Civis</a>
			  <a href='cad_cliente.php'>Clientes</a>
        <a href='cad_produto.php'>Produto</a>
			</div>
		  </li>"
  ?>
  
  <li><a href="sair.php">Sair</a></li>
  
</ul> 


