<?php

require_once('exacttarget_soap_client.php');

/**
 * ExactTarget class file
 *
 * LICENSE: 
 *
 * @copyright  2011 Nick Holdren
 * @license    
 * @version    1.0
 * @link       
 * @since      File available since Release 1.0
 */
 
 /**
 * ExactTarget Class
 *
 * A class for consuming the ExactTarget API
 *
 * @copyright  2011 Nick Holdren
 * @license    
 * @version    Release: 1.0
 * @link      
 * @since      Class available since Release 1.0
 */ 
class ExactTarget{
	
	public $username;
	public $password;
	
	public static $methods = array(
		'add',
		'delete',
		'perform',
		'update',
		'retrieve'
	);
	
	public static $resources = array(
		'account',
		'dataextension',
		'importdefinition',
		'list',
		'subscriber'
	);
	
	//ExactTarget SOAP API endpoint
	public static $wsdl = 'https://on.exacttarget.com/etframework.wsdl';

	//The ExactTarget client object
	public $client;
	
	protected $async_options;

	/**
	* Handles the request
	*
	* @throws Some_Exception_Class If something interesting cannot happen
	* @return Integer The ID of the object
	*/ 
	public function __construct($username, $password){
		
		if(isset($username, $password)){
		
			$this->username = $username;
			$this->password = $password;
			
			//Create ExactTarget client
			$this->client = new ExactTargetSoapClient(self::$wsdl, array('trace'=>1));
			$this->client->username = $this->username;
			$this->client->password = $this->password;
			
		}
		
	}
	
	/**
	* Handles the request
	*
	* @throws Some_Exception_Class If something interesting cannot happen
	* @return Integer The ID of the object
	*/ 
	public function request($method, $object, $async){
		
		//Check for asynchronous options
		if($async){
			
			$this->async_options = new ExactTarget_UpdateOptions;
			$this->async_options->RequestType = ExactTarget_RequestType::Asynchronous;
			$this->async_options->RequestTypeSpecified = true;
			
		}else{
		
			$this->async_options = NULL;	
		}
		
		//Select the method
		switch(strtolower($method)){
			case 'update':
				return $this->update($object);
				break;
			case 'create':
				return $this->create($object);
				break;
			case 'delete':
				return $this->delete($object);
				break;
			case 'upsert':
				return $this->upsert($object);	
				break;
		}
	}
	
	/**
	* Creates an object
	*
	* @throws Some_Exception_Class If something interesting cannot happen
	* @return Integer The ID of the object
	*/ 
	public function create($object){
		
		try{
		
			$request = new ExactTarget_CreateRequest();
			$request->Objects = $object;
			$request->Options = $this->async_options;
			$results = $this->client->Create($request);
			
			return $results;
		
		}catch(Exception $e){
			
			var_dump($e);	
		}
	
	}
	
	/**
	* Updates an object
	*
	* @throws Some_Exception_Class If something interesting cannot happen
	* @return Integer The ID of the object
	*/ 
	public function update($object){
		
		try{
		
			$request = new ExactTarget_UpdateRequest();
			$request->Objects = $object;
			$request->Options = $this->async_options;
			$results = $this->client->Update($request);
			
			return $results;
		
		}catch(Exception $e){
			
			var_dump($e);	
		}
	}
	
	/**
	* Upserts a data extension object
	*
	* @throws Some_Exception_Class If something interesting cannot happen
	* @return Integer The ID of the object
	*/ 
	public function upsert($object){
		
		try{
			
			$saveOption = new ExactTarget_SaveOption();                
    	    $saveOption->PropertyName= $object;
	        $saveOption->SaveAction=ExactTarget_SaveAction::UpdateAdd;

			$request = new ExactTarget_UpdateRequest();
			$request->Objects = $object;
			$request->Options = $this->async_options;
			$results = $this->client->Update($request);
		
			return true;
			
		}catch(Exception $e){
			
			var_dump($e);	
		}
	}
	
	/**
	* Deletes an object
	*
	* @throws Some_Exception_Class If something interesting cannot happen
	* @return Integer The ID of the object
	*/ 
	public function delete($object){
		
		try{
		
			$request = new ExactTarget_DeleteRequest();
			$request->Objects = $object;
			$request->Options = $this->async_options;
			$results = $this->client->Delete($request);
			
			var_dump($results);
			
			return true;
		
		}catch(Exception $e){
			
			var_dump($e);	
		}
	}
	
	/**
	* Performs an action
	*
	* @throws Some_Exception_Class If something interesting cannot happen
	* @return Integer The ID of the object
	*/ 
	public function perform($object){
		
		try{
			
			$request = new ExactTarget_PerformRequestMsg();
			$request->Action = 'start';
			$request->Definitions->Definition = $object;
			$results = $this->client->Perform($request);
			
			return $results;
		
		}catch(Exception $e){
			
			var_dump($e);	
		}
	}
	
	/**
	* Retrieves the data extension folder within the account
	*
	* @throws Some_Exception_Class If something interesting cannot happen
	* @return Integer The ID of the object
	*/ 
	public function search($object_name, $object_type = 'DataFolder'){

		try{
			
			//First find the parent folder
			$filter = new ExactTarget_SimpleFilterPart();
			$filter->Property = "Name";
			$filter->SimpleOperator = ExactTarget_SimpleOperators::equals;
			$filter->Value = array($object_name);
			
			//Encode the filter object
			$filter = new SoapVar($filter, SOAP_ENC_OBJECT, 'SimpleFilterPart', "http://exacttarget.com/wsdl/partnerAPI");
	
			//Setup and execute the request
			$rr = new ExactTarget_RetrieveRequest();
			$rr->ObjectType = $object_type;
			
			switch(strtolower($object_type)){
				case 'datafolder':
					$rr->Properties = array("ID", "CustomerKey", "Name");
					break;	
				case 'dataextension':
					$rr->Properties = array("ObjectID", "CustomerKey", "Name");
					break;
			}
			
			
			$rr->Filter = $filter;
			
			//Execute the request
			$rrm = new ExactTarget_RetrieveRequestMsg();
			$rrm->RetrieveRequest = $rr;
			$results = $this->client->Retrieve($rrm);
			
			//Return the object ID
			return $results;
		
		}catch(SoapFault $e) {
			
			//Let's just handle this nicely
			return false; 
			
		}
		
	}
}



?>