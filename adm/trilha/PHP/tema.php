<?php
     
     $user = $_GET['username'];
	 
     require_once 'conectaBD.php';
	 
     if(!empty($user)) 
     {
         $query = "Select t.Codigo, t.Nome, a.Descricao Area, t.Descricao, t.Visibilidade from Tema t, Area a, Usuario_Tema u ";
		 $query.= "where t.CodArea=a.Codigo and t.Codigo=u.codTema and u.Login='" . $user . "'";
	 }
	 else
	 {
	     $query = "Select t.Codigo, t.Nome, a.Descricao Area, t.Descricao, t.Visibilidade from Tema t, Area a, Usuario_Tema u ";
		 $query.= "where t.CodArea=a.Codigo and t.Codigo=u.codTema";
	 }

     $result = $conn->query($query);

     while($row = $result->fetch_array())
     {
         echo $row['Codigo'] . "\t" . utf8_encode($row['Nome']) . "\t" . utf8_encode($row['Area']). "\t" . utf8_encode($row['Descricao']). "\t" . $row['Visibilidade'] . "\n"; // And output them 
     }
     $conn->close(); 
      
?>
