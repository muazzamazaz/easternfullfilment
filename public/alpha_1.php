<?php


  
    $url = "https://sandbox.bankalfalah.com/HS/HS/HS";
	 
	 //$url = "https://payments.bankalfalah.com/HS/HS/HS";
        
            
          // $bankorderId   = $this->session->userdata('bankorderId');
         $bankorderId   = rand(0,1786612);
        
        
        $Key1= "yUSfW42pgMHaq349";
        $Key2= "2764787997628385";
        $HS_ChannelId="1001";
        $HS_MerchantId="26599" ;
        $HS_StoreId="037350" ;
        $HS_IsRedirectionRequest  = 0;
        $HS_ReturnURL="https://track-pad.com/";
        $HS_MerchantHash="OUU362MB1uryEJPW4sgow2ddvpuwgugbxDuRutgh6nY=";
        $HS_MerchantUsername="dydevy" ;
        $HS_MerchantPassword="7NyKJsEOg4tvFzk4yqF7CA==";
        $HS_TransactionReferenceNumber= $bankorderId;
         $transactionTypeId = "1";
	    $TransactionAmount = "300";   
		
		   $cipher="aes-128-cbc";
	    
        $mapString = 
          "HS_ChannelId=$HS_ChannelId" 
        . "&HS_IsRedirectionRequest=$HS_IsRedirectionRequest" 
        . "&HS_MerchantId=$HS_MerchantId" 
        . "&HS_StoreId=$HS_StoreId" 
        . "&HS_ReturnURL=$HS_ReturnURL"
        . "&HS_MerchantHash=$HS_MerchantHash"
        . "&HS_MerchantUsername=$HS_MerchantUsername"
        . "&HS_MerchantPassword=$HS_MerchantPassword"
        . "&HS_TransactionReferenceNumber=$HS_TransactionReferenceNumber";
        
        $cipher_text = openssl_encrypt($mapString, $cipher, $Key1,   OPENSSL_RAW_DATA , $Key2);
        $hashRequest = base64_encode($cipher_text);
	
        //The data you want to send via POST
        $fields = [
            "HS_ChannelId"=>$HS_ChannelId,
            "HS_IsRedirectionRequest"=>$HS_IsRedirectionRequest,
            "HS_MerchantId"=> $HS_MerchantId,
            "HS_StoreId"=> $HS_StoreId,
            "HS_ReturnURL"=> $HS_ReturnURL,
            "HS_MerchantHash"=> $HS_MerchantHash,
            "HS_MerchantUsername"=> $HS_MerchantUsername,
            "HS_MerchantPassword"=> $HS_MerchantPassword,
            "HS_TransactionReferenceNumber"=> $HS_TransactionReferenceNumber,
            "HS_RequestHash"=> $hashRequest
        ];
        
        $fields_string = http_build_query($fields);
      //  print_r($fields);
        //open connection
        $ch = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
        //execute post
        $result = curl_exec($ch);
        
       $handshake =  json_decode($result);
       
        $AuthToken = $handshake->AuthToken;
        
        
	   // echo $fields_string ."<br>";
	 //   echo $AuthToken ."<br>";
	  
	  
	  /* ==============SSO CALL ================*/
	  
	  // you need Auth Token & Amount Here before Hashing
	  
	  
	  
	
$RequestHash1 = NULL;
$Currency = "PKR";
$IsBIN =0;

$mapStringSSo = 
  "AuthToken=$AuthToken" 
. "&RequestHash=$hashRequest" 
. "&ChannelId=$HS_ChannelId"
. "&Currency=$Currency"
. "&IsBIN=$IsBIN"
. "&ReturnURL=$HS_ReturnURL"
. "&MerchantId=$HS_MerchantId"
. "&StoreId=$HS_StoreId" 
. "&MerchantHash=$HS_MerchantHash"
. "&MerchantUsername=$HS_MerchantUsername"
. "&MerchantPassword=$HS_MerchantPassword"
. "&TransactionTypeId=3"
. "&TransactionReferenceNumber=$HS_TransactionReferenceNumber"
. "&TransactionAmount=$TransactionAmount";

	echo $mapStringSSo."<br>";
		 
$cipher_text = openssl_encrypt($mapStringSSo, $cipher, $Key1,   OPENSSL_RAW_DATA , $Key2);
$hashRequest1 = base64_encode($cipher_text);

    //echo $hashRequest1;

?>
        <form action="https://sandbox.bankalfalah.com/SSO/SSO/SSO" id="PageRedirectionForm" method="post" novalidate="novalidate">                                                              
     	<input id="AuthToken" name="AuthToken" type="text" value="<?php echo $AuthToken; ?>">                                                                                                                                
     	<input id="RequestHash" name="RequestHash" type="text" value="<?php echo $hashRequest1; ?>">                                                                                                                            
     	<input id="ChannelId" name="ChannelId" type="text" value="<?php echo $HS_ChannelId; ?>">                                                                                                                            
     	<input id="Currency" name="Currency" type="text" value="PKR">                                                                                                                               
         <input id="IsBIN" name="IsBIN" type="text" value="0">                                                                                     
     	<input id="ReturnURL" name="ReturnURL" type="text" value="https://track-pad.com/">                                                                            
         <input id="MerchantId" name="MerchantId" type="text" value="<?php echo $HS_MerchantId;?>">                                                                                                                           
         <input id="StoreId" name="StoreId" type="text" value="<?php echo $HS_StoreId;?>">                                                                                                                     
     	<input id="MerchantHash" name="MerchantHash" type="text" value="<?php echo $HS_MerchantHash;?>">                                  
     	<input id="MerchantUsername" name="MerchantUsername" type="text" value="<?php echo $HS_MerchantUsername;?>">                                                                                                            
     	<input id="MerchantPassword" name="MerchantPassword" type="text" value="<?php echo $HS_MerchantPassword;?>">  
    <input id="TransactionTypeId" name="TransactionTypeId" type="text" value="3"> 
      
                                                                                                                                                                              
     	<input autocomplete="off" id="TransactionReferenceNumber" name="TransactionReferenceNumber" placeholder="Order ID" type="text" value="<?php echo $HS_TransactionReferenceNumber;?>">                                  
    	<input autocomplete="off"  id="TransactionAmount" name="TransactionAmount" placeholder="Transaction Amount" type="text" value="<?php echo $TransactionAmount; ?>">  
          
     
				 <br>
     <center>	<button type="submit" class="btn btn-custon-four btn-danger" id="run">PAY ONLINE</button>        </center>                                                                                                    
     </form> 
				
		
		
		

		
		