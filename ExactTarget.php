<?php

require_once('lib/exacttarget_soap_client.php');

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
	//WSDL instances
    public static $instances = array(
        'default' => 'https://webservice.exacttarget.com/etframework.wsdl',
        's2' => 'https://webservice.s2.exacttarget.com/etframework.wsdl',
        's3' => 'https://webservice.s3.exacttarget.com/etframework.wsdl',
        's4' => 'https://webservice.s4.exacttarget.com/etframework.wsdl',
        's6' => 'https://webservice.s6.exacttarget.com/etframework.wsdl',
        's7' => 'https://webservice.s7.exacttarget.com/etframework.wsdl',
        's8' => 'https://webservice.s8.exacttarget.com/etframework.wsdl',
        'support' => 'https://webservice.test.exacttarget.com/etframework.wsdl'
    );

	//The ExactTarget client object
	public $client;
  public $mid;

	protected $async_options;

    /**
    * Constructor for the ExactTarget class
    *
    * Builds the ExactTarget client object
    *
    * @param username String The ExactTarget account's username
    * @param password String The ExactTraget account's password
    * @param instance String The ExactTraget account's instance, in login URL
    * @param mid String The ExactTarget business unit ID, for on behalf of functionality
    */
	public function __construct($username, $password, $instance = 'default', $mid = null){

		if(isset($username, $password)){

			$this->username = $username;
			$this->password = $password;
			$wsdl = self::$instances[$instance];

			//Create ExactTarget client
			$this->client = new ExactTargetSoapClient($wsdl, array('trace'=>1));
			$this->client->username = $this->username;
			$this->client->password = $this->password;

      $this->mid = $mid;

		}

	}

	/**
	* Handles the request
	*
	* @param $method String The action to take on the object
	* @param $object Object The object that is being acted on
	* @param $async_options Object The asynchronous options to be used for the call
	*
	* @throws Some_Exception_Class If something interesting cannot happen
	* @return Integer The ID of the object
	*/
	public function request($method, $object, $async_options){

		//Check for asynchronous options
		if($async_options){

			$this->async_options = new ExactTarget_UpdateOptions;
			$this->async_options->RequestType = ExactTarget_RequestType::Asynchronous;
			$this->async_options->RequestTypeSpecified = true;

			$ar = self::_buildAsyncOptions($async_options);
		}else{
			$this->async_options = NULL;
		}

    //Set the object's Client ID to determine which business unit to act on
    if($this->mid){
      $objClient = new ExactTarget_ClientID();
      $objClient->ID = $this->mid;
      $object->Client = $objClient;
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
	* Retrieves an object
	*
	* @throws Some_Exception_Class If something interesting cannot happen
	* @return Integer The ID of the object
	*/
	public function retrieve($object, $properties){

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

	/**
	*
	* Builds the asynchronous options object
	*
	* @param $async_options
	*
	* @return ExactTarget_AsyncResponse object
	*/
	private function _buildAsyncOptions($async_options){

		$ar = new ExactTarget_AsyncResponse();

		switch($async_options->respond){
			case 'Never':
				$ar->RespondWhen = ExactTarget_RespondWhen::Never;
				break;
			case 'OnError':
				$ar->RespondWhen = ExactTarget_RespondWhen::OnError;
				break;
			case 'Always':
				$ar->RespondWhen = ExactTarget_RespondWhen::Always;
				break;
			case 'OnConversationError':
				$ar->RespondWhen = ExactTarget_RespondWhen::OnConversationError;
				break;
			case 'OnConversationComplete':
				$ar->RespondWhen = ExactTarget_RespondWhen::OnConversationComplete;
				break;
			case 'OnCallComplete':
				$ar->RespondWhen = ExactTarget_RespondWhen::OnCallComplete;
				break;
			default:
				break;
		}

		switch($async_options->responseType){
			case "Post":
				$ar->ResponseType = ExactTarget_AsyncResponseType::HTTPPost;
        		$ar->ResponseAddress = $async_options->responseURL;
        		break;
        	case "Email":
        		$ar->ResponseType = ExactTarget_AsyncResponseType::email;
        		$ar->ResponseAddress = $async_options->responseEmail;
        		break;
        	case "FTP":
        		$ar->ResponseType = ExactTarget_AsyncResponseType::FTP;
        		$ar->ResponseAddress = $async_options->responseEmail;
        		break;
        	case "None":
        		$ar->ResponseType = ExactTarget_AsyncResponseType::FTP;
        		$ar->ResponseAddress = $async_options->responseEmail;
        		break;
		}

        $ar->IncludeResults = true;
        $ar->IncludeObjects = true;

        $requestOptions->RequestType = ExactTarget_RequestType::Asynchronous;
        $requestOptions->RequestTypeSpecified = true;
		$requestOptions->QueuePriority = $async_options->priority;
		$requestOptions->QueuePrioritySpecified = true;
		$requestOptions->SendResponseTo[] = $ar;

	}
}



?>
