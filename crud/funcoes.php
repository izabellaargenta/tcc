<?php

include_once("conectar.php");

/*
 
 $teste = montaSelectBD($bd, "select idmodalidade, modalidade from tmodalidades", "", false);
 
 $teste = montaSelectBD($bd, "select idPessoa, login from tpessoa", "", false);
  
 <select name="teste">
    <?php echo $teste; ?>
 </select>  
  
 
  
 */ 

  function montaSelectBD($bd, $sql, $valor_atual, $aceita_nulos) {
		$elemento = "";
	    if ($aceita_nulos == true)
	        $elemento = "<option value=''></option>";
	   		
		$lista = mysqli_query($bd, $sql);

		if ( mysqli_num_rows($lista) > 0 ) {
			$selecionado = "";
			while ( $dados = mysqli_fetch_row($lista)) {
		
				if ( $dados[0] == $valor_atual )
		             $selecionado = " selected ";
		        else 
		             $selecionado = "";
		             
		        $elemento = $elemento."<option value='".$dados[0]."' 
		           $selecionado >".$dados[1]."</option>";
            }
		}
		return $elemento;
  }
  
  function montaSelect($valores, $descricoes, $valor_atual, $aceita_nulos) {
	
	$elemento = "";
	
	if ($aceita_nulos == true)
	   $elemento = "<option value=''></option>";
	   
	for ($i=0; $i < sizeof($valores); $i++) {
		$selecionado = "";
		
		if ($valores[$i] == $valor_atual)
		   $selecionado = " selected ";
		else
		   $selecionado = "";
		   
		$elemento = $elemento."<option value='$valores[$i]' 
		     $selecionado>$descricoes[$i]</option>";
	}
	
	return $elemento;
  }


?>
