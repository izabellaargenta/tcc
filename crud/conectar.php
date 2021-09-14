<?php
   
 //Servidor local (ex. XAMPP)
 //$bd = mysqli_connect("localhost","root","","curso_crud");

 //Um servidor qualquer
 //$bd = mysqli_connect("10.10.6.250:3306","usuário","senha","curso_crud");
 
 //Servidor do IFFar (inf.fw.iffarroupilha.edu.br)
 //$bd = mysqli_connect("localhost","login_aluno","senha_aluno","login_aluno");
 
 //Servidor local (ex. USBWEBSERVER)
 $bd = mysqli_connect("localhost","root","usbw","curso_crud");

 if ($bd) {
	 mysqli_set_charset($bd, "utf8");
 } else {
	 echo "Não foi possível conectar o BD <br>";
	 echo "Mensagem de erro: ".mysqli_connect_error() ;
	 exit();
 }
