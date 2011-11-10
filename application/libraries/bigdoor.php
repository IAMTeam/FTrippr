<?php

//NOTE TO RØØT DEVELOPERS: THIS FILE HAS BEEN MODIFIED TO WORK WITH CODEIGNITER, PLEASE DON'T UPDATE IT WITHOUT PAYING ATTENTION TO THE CONSTANTS ON LINE 84

/**
*  BigDoor PHP Kit - API client
*  
*  @author Mark Edwards <mark@simplercomputing.net>
*
*  http://simplercomputing.net
*
*  @version 0.1
*/


/**
* @package BigDoor_PHP_Kit
* @class BDM_Client
* 
* 
* This class provides the overall connectivity layer and general API access functions.
*
*/

/*
BigDoor Open License
Copyright (c) 2010 BigDoor Media, Inc.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
of the Software, and to permit persons to whom the Software is furnished to
do so, subject to the following conditions:

- This copyright notice and all listed conditions and disclaimers shall
be included in all copies and portions of the Software including any
redistributions in binary form.

– The Software connects with the BigDoor API (api.bigdoor.com) and
all uses, copies, modifications, derivative works, mergers, publications,
distributions, sublicenses and sales shall also connect to the BigDoor API and
shall not be used to connect with any API, software or service that competes
with BigDoor’s API, software and services.

- Except as contained in this notice, this license does not grant you rights to
use BigDoor Media, Inc. or any contributors’ name, logo, or trademarks.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.
*/

if (!class_exists('Bigdoor')) {

    class Bigdoor {

	var $api_host = '';
	var $app_key;
	var $app_secret; 
	var $base_path;
	var $localization;
	var $bdm_sig_exclude = array('format','sig');
	var $connect_timeout;
	var $timeout;

	/**
	* Constructor (PHP4)
	*/
	function Bigdoor() {
	    $this->__construct();
	}
	

	/**
	*  Class constructor (PHP 5)
	*/
	function __construct() {
		$this->init(BD_APP_SECRET,BD_APP_KEY,BD_APP_HOST);
	}

	/**
	* Initialize class variables
	*
	* @param string $method HTTP method (e.g. GET, POST, PUT, DELETE)
	* @param string $object_path BigDoor API path fragment (e.g. /leaderboard/execute, etc) 
	* @return boolean True upon success, false if parameters are missing
	*/
	function init( $app_secret, $app_key, $api_host ) { 

	    $this->localization = 'BDM'; // PHP language localization variable

	    if (!$app_secret || !$app_key || !$api_host) 
		return false;

	    $this->api_host = $api_host;
	    $this->app_key = $app_key;
	    $this->app_secret = $app_secret;
	    $this->base_path = 	'/api/publisher/' . $this->app_key;

	    $this->connect_timeout = 15;
	    $this->timeout = 15;
	    
	    return true;

	}


	/**
	* Returns a response object
	*
	* @param string $method HTTP method (e.g. GET, POST, PUT, DELETE)
	* @param string $object_path BigDoor API path fragment (e.g. /leaderboard/execute, etc) 
	* @param array  $params URL query string parameters
	* @param array  $envelope POST/PUT/DELETE method parameters
	* @return string HTTP response string
	*/
	function do_request( $method, $object_path, $params = array(), $envelope = array() ) {

	    if (!$method || !$object_path)
		return 'ERROR: You must define a method and object path';

	    $result = '';

	    switch($method) { 

		case 'GET' : 
		    $result = $this->get( $object_path, $params );
		    break;

		case 'POST' : 
		    $result = $this->post( $object_path, $params, $envelope );
		    break;

		case 'PUT' : 
		    $result = $this->put( $object_path, $params, $envelope );
		    break;
		
		case 'DELETE' : 
		    $result = $this->delete( $object_path, $params, $envelope );
		    break;

	    }

	    return $result;

	}

	/**
	* Returns a response object
	*
	* @param string $object_path BigDoor API path fragment (e.g. /leaderboard/execute, etc) 
	* @param array  $params GET method parameters
	* @return string HTTP response string
	*/

	function get( $object_path, $params = array() ) { 

	    return  $this->_http_call( 'GET', $object_path, $params );

	}


	/**
	* Returns a response object
	*
	* @param string $object_path BigDoor API path fragment (e.g. /leaderboard/execute, etc) 
	* @param array  $params URL query string parameters
	* @param array  $envelope DELETE method parameters
	* @return string HTTP response string
	*/
	function delete( $object_path, $params = array(), $envelope = array() ) { 

	    return $this->_http_call( 'DELETE', $object_path, $params, $envelope );

	}

	/**
	* Returns a response object
	*
	* @param string $object_path BigDoor API path fragment (e.g. /leaderboard/execute, etc) 
	* @param array  $params URL query string parameters
	* @param array  $envelope POST method parameters
	* @return string HTTP response string
	*/
	function post( $object_path, $params = array(), $envelope = array() ) { 

	    return $this->_http_call( 'POST', $object_path, $params, $envelope );

	}

	/**
	* Returns a response object
	*
	* @param string $object_path BigDoor API path fragment (e.g. /leaderboard/execute, etc) 
	* @param array  $params URL query string parameters
	* @param array  $envelope PUT method parameters
	* @return string HTTP response string
	*/
	function put( $object_path, $params = array(), $envelope = array() ) { 

	    return $this->_http_call( 'PUT', $object_path, $params, $envelope );

	}

	/**
	* Returns a response object - DO NOT CALL THIS FUNCTION DIRECTLY
	*
	* @access private
	*
	* @param string $method HTTP method (e.g. GET, POST, PUT, DELETE)
	* @param string $object_path BigDoor API path fragment (e.g. /leaderboard/execute, etc) 
	* @param array  $params URL query string parameters
	* @param array  $envelope POST/PUT/DELETE method parameters
	* @return string HTTP response string
	*/
	function _http_call( $method, $object_path, $params = array(), $envelope = array() ) { 

	    if (!function_exists('curl_init'))
		die("Your server's PHP installation must support CURL in order to use the BigDoor PHP Kit" );

	    if (!$method || !$object_path)
		die ('ERROR: You must define a method and object path');

	    $bd_reason = '';
	    $bd_code = '';

	    $agent = 'BigDoor-PHP-Kit http://bigdoor.com';

	    $path = $this->base_path . $object_path;

	    $sig_gend = $this->generate_signature( $path, $params, $envelope, $method );

	    $parameters = $sig_gend['ret_pars'];
	    $the_envelope = $sig_gend['ret_env'];

	    foreach($parameters as $key => $val) { 
		    $string[] = $key.'='.$val;
	    }

	    $req = implode('&', $string);

	    $path = $this->api_host . $this->base_path . $object_path . '?' . $req;

	    $ch = curl_init($path);

	    if ( count($the_envelope) > 0) { 

		if (!function_exists('http_build_query'))
			$env = _http_build_query($the_envelope, null, '&');
		else
			$env = http_build_query($the_envelope, null, '&');

	    }

	    if ( 'DELETE' == $method ) { 

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');

	    } else if ( 'PUT' == $method ) { 

		curl_setopt($ch, CURLOPT_POSTFIELDS, $env);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');

	    } else if ( 'POST' == $method ) { 

		curl_setopt($ch, CURLOPT_POSTFIELDS, $env);
		curl_setopt($ch, CURLOPT_POST, true);

	    } 

	    curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connect_timeout);
	    curl_setopt($ch, CURLOPT_HEADER, 1);
	    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	    $res = curl_exec($ch);

	    if ( !empty($res) ) {

		    $hlength = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

		    $headers = trim( substr($res, 0, $hlength) );

		    if ( strlen($res) > $hlength )
			    $body = substr( $res, $hlength );
		    else
			    $body = '';

		    if ( false !== strrpos($headers, "\r\n") ) {
			    $headers = explode("\r\n", $headers);
		    }

		    foreach ($headers as $header) { 
			if (strpos($header, 'BDM-Reason-Phrase') !== false) {
			    $header = explode(':', $header);
			    $bd_reason = trim($header[1]);
			    $bd_code = $body;
			    break;
			}
		    }

	    } else {

		    $headers = '';
		    $body = '';

	    }

	    $response =  array( 
		    'http_status_code' => curl_getinfo( $ch, CURLINFO_HTTP_CODE ),
		    'http_reason_phrase' => $this->get_status_header_desc( curl_getinfo( $ch, CURLINFO_HTTP_CODE ) ),
		    'bdm_status_code' => $bd_code,
		    'bdm_status_phrase' => $bd_reason,
		    'headers' => $headers,
		    'request_method' => $method,
		    'request_url' => $path,
		    'data' => $body
		    );

	    curl_close($ch);

	    return $response;

	}

	/**
	* Returns a HTTP result string as it relates to a HTTP response code
	*
	* Taken from WordPress core code.
	*
	* @param int $code HTTP response code
	* @return string HTTP response string
	*/
	function get_status_header_desc( $code ) {

	    $code = intval( $code );

	    $header_to_desc = array(
		100 => 'Continue',
		101 => 'Switching Protocols',
		102 => 'Processing',

		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		207 => 'Multi-Status',
		226 => 'IM Used',

		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		306 => 'Reserved',
		307 => 'Temporary Redirect',

		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		422 => 'Unprocessable Entity',
		423 => 'Locked',
		424 => 'Failed Dependency',
		426 => 'Upgrade Required',

		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
		506 => 'Variant Also Negotiates',
		507 => 'Insufficient Storage',
		510 => 'Not Extended'
	    );

	    $res = $header_to_desc[$code];

	    if ('' != $res) 
		return $res;
	    else
		return '';
	}



	/** Generates a token for API requests */
	function generate_token() {
	    return md5( uniqid() );
	}
	

	/**
	* Returns a request signature
	*
	* @param string $endpoint BigDoor API path fragment (e.g. /leaderboard/execute, etc) f
	* @param array  $bdm_r_pars URL query string parameters
	* @param array  $bdm_r_env POST/PUT/DELETE method parameters
	* @param string $method HTTP method (e.g. GET, POST, PUT, DELETE)
	* @return array  signed array values
	*/
	function generate_signature( $endpoint, $parms, $env, $method ) {

	    if (!array_key_exists('time', $parms)) {
		$parms['time'] = time() . '.00';
	    }

	    if ( 'POST' == $method || 'PUT' == $method ) {
		    $env['time'] = $parms['time'];
		    $env['token'] = $this->generate_token();
	    }
 
	    if ( 'DELETE' == $method ) 
		$parms['delete_token'] = $this->generate_token();

	    $sig = $endpoint . $this->_flatten_request_array($parms) . $this->_flatten_request_array($env) . $this->app_secret;

	    //  The hash method is only supported in PHP 5, so we use an external lib for PHP 4 if necessary. 
	    if ( !version_compare(phpversion(), '5.0', '>=' ) ) {
		    require_once( dirname(__FILE__).'/php4-sha256.php' );
	    }

	    $parms['sig'] = hash('sha256',$sig); 
	    
	    return array('ret_pars' => $parms, 'ret_env' => $env);
	}

	

	/**
	* Flatten parameters and envelope values into a string, sorted in alphabetic order - DO NOT REMOVE SORTING!
	*
	* This function contributed by Brian Oldfield
	*
	* @access private
	*
	* @param string $bdm_request_endpoint BigDoor API path fragment (e.g. /leaderboard/execute, etc) f
	* @return string array values converted to a string suitable for HTTP query use
	*/
	function _flatten_request_array( $flat_array = array() ) {

	    if (count($flat_array)) {

		// Sort the keys prior to flattening.
		$sorted_keys = array();

		foreach ($flat_array as $flt_itm => $ign_val) {
		    $sorted_keys[] = $flt_itm;
		}

		sort($sorted_keys);
		
		// Setup an array to implode. 
		$imp_arr = array();

		foreach($sorted_keys as $key) {
		    // Make sure we're not getting things in the sig string that shouldn't be there.
		    if (!in_array( $key, $this->bdm_sig_exclude ) ) {
			$imp_arr[] = $key . $flat_array[$key];
		    }
		}

		$ret = implode('',$imp_arr);

	    } else {

		$ret = '';

	    }
	    
	    return $ret;
	}



	/**
	* For backward support in PHP 4. Taken from PHP.net and WordPress core code.
	*/
	function _http_build_query($data, $prefix=null, $sep=null, $key='', $urlencode=true) {
		$ret = array();

		foreach ( (array) $data as $k => $v ) {
			if ( $urlencode)
				$k = urlencode($k);
			if ( is_int($k) && $prefix != null )
				$k = $prefix.$k;
			if ( !empty($key) )
				$k = $key . '%5B' . $k . '%5D';
			if ( $v === NULL )
				continue;
			elseif ( $v === FALSE )
				$v = '0';

			if ( is_array($v) || is_object($v) )
				array_push($ret,_http_build_query($v, '', $sep, $k, $urlencode));
			elseif ( $urlencode )
				array_push($ret, $k.'='.urlencode($v));
			else
				array_push($ret, $k.'='.$v);
		}

		if ( NULL === $sep )
			$sep = ini_get('arg_separator.output');

		return implode($sep, $ret);
	}


    } // ======== END Class ===========

} // ---- end if class exists
   
   
?>
