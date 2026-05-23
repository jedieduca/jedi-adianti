<?php 
   
    //header('Content-Type: application/json; charset=utf-8');
    
    function jsonRemoveUnicodeSequences( $struct )
{
	return preg_replace( "/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", json_encode( $struct ) );
}
    
    //getArea.php
	//Importing Database Script 
	//require_once('dbConnect.php');
        require_once('dbConn2.php');
	
	
	//Creating sql query
	$sql = "SELECT * FROM area";
	
	//getting result 
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	
	//creating a blank array 
	$result = array();

	/*if ($stmt->rowCount()==0)
	{
            array_push($result,array(
                        "codigo"=>"codigo",
                        "descricao"=>"descricao"
                        ));
            echo json_encode(array('result'=>$result));
            return;
	}*/

        	/*foreach($stmt as $row) {
			echo utf8_decode($row['descricao']);
                        
	}*/
        
        
	//looping through all the records fetched
	/*foreach($stmt as $row) {
			array_push($result,array(
                            "codigo"=>$row['codigo'],
                            "descricao"=>utf8_decode($row['descricao'])
                        ));
	}*/
        
        $output.="";
        foreach($stmt as $row) {
                $output.=$row['codigo']."#".utf8_decode($row['descricao']).";";
                        
	}
	
        
	//Displaying the array in json format 

	echo $output;
        //echo json_encode(array('result'=>$result));
        //echo jsonRemoveUnicodeSequences(array('result'=>$result));
        
        /*var_dump(array('result'=>$result));
        $json  = json_encode(array('result'=>$result));
        var_dump($json);*/
	
?>