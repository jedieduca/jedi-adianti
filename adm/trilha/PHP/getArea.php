<?php 
   
    header('Content-Type: application/json; charset=utf-8');
    
    function jsonRemoveUnicodeSequences( $struct )
{
	return preg_replace( "/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", json_encode( $struct ) );
}
    
    //getArea.php
	//Importing Database Script 
	//require_once('dbConnect.php');
        require_once('dbConn.php');
	
	
	//Creating sql query
	$sql = "SELECT * FROM area";
	
	//getting result 
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	
        $output.="";
        foreach($stmt as $row) 
                $output.=$row['codigo']."#".$row['descricao'].";";
        
        echo $output;
        
	//creating a blank array 
	/*$result = array();

	//looping through all the records fetched
	foreach($stmt as $row) {
			array_push($result,array(
                            "codigo"=>$row['codigo'],
                            "descricao"=>$row['descricao']
                        ));
	}
	
        
	//Displaying the array in json format 

	echo json_encode(array('result'=>$result),JSON_UNESCAPED_UNICODE);*/
        //echo jsonRemoveUnicodeSequences(array('result'=>$result));
        
        /*var_dump(array('result'=>$result));
        $json  = json_encode(array('result'=>$result));
        var_dump($json);*/
	
?>