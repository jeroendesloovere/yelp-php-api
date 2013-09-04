<?php

/**
 * Yelp API
 *
 * @author Jeroen Desloovere <jeroen@siesqo.be>
 * @version 0.0.1
 */
class Yelp
{
	// API url
	const API_URL = 'http://api.yelp.com/';

	// API version
	const API_VERSION = 'v2';

    // current version
    const VERSION = '0.0.1';

	/**
	 * Consumer key
	 *
	 * @var string
	 */
	private $consumerKey;

	/**
	 * Consumer secret
	 *
	 * @var string
	 */
	private $consumerSecret;

	/**
	 * Token
	 *
	 * @var string
	 */
	private $token;

	/**
	 * Token secret
	 *
	 * @var string
	 */
	private $tokenSecret;

	/**
	 * Construct
	 *
	 * @param string $consumerKey
	 * @param string $consumerSecret
	 * @param string $token
	 * @param string $tokenSecret
	 */
	public function __construct($consumerKey, $consumerSecret, $token, $tokenSecret)
	{
		$this->consumerKey = $consumerKey;
		$this->consumerSecret = $consumerSecret;
		$this->token = $token;
		$this->tokenSecret = $tokenSecret;
	}

	/**
	 * Do call
	 *
	 * @param string $url The URL to call.
	 * @param array[optional] $data The data to pass.
	 */
	private function doCall($url, $data = null)
	{
		// enter the path that the oauth library is in relation to the php file
		require_once ('oauth.php');

		// define unsigned url
		$unsignedUrl = API_URL . API_VERSION . '/' . $method;

		// token object built using the OAuth library
		$token = new OAuthToken($this->token, $this->tokenSecret);

		// consumer object built using the OAuth library
		$consumer = new OAuthConsumer($this->consumerKey, $this->consumerSecret);

		// Yelp uses HMAC SHA1 encoding
		$signatureMethod = new OAuthSignatureMethod_HMAC_SHA1();

		// build OAuth Request using the OAuth PHP library. Uses the consumer and token object created above.
		$oauthRequest = OAuthRequest::from_consumer_and_token($consumer, $token, 'GET', $unsignedUrl);

		// sign the request
		$oauthRequest->sign_request($signatureMethod, $consumer, $token);

		// get the signed URL
		$signedUrl = $oauthRequest->to_url();

		// send Yelp API Call
		$ch = curl_init($signedUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, 0);

		// Yelp response
		$data = curl_exec($ch); 

		// close connection
		curl_close($ch);

		// handle Yelp response data
		return(json_decode($data));
	}

	/**
	 * Search for business
	 *
	 * @param string $value
	 */
	public function searchForBusiness($value)
	{
		// build url
		$url = '/business/' . (string) $value;

		// do call
		return $this->doCall($url);
	}

	/**
	 * Search for term
	 *
	 * @param string $term
	 * @param string[optional] $location
	 */
	public function searchForTerm($term, $location = null)
	{
		// build url
		$url = '/search';

		// define data
		$data = array('term' => $term);

		// if location is defined
		if($location !== null) $data['location'] = $location;

		// return results
		return $this->doCall($url, $data);
	}
}


/**
 * Yelp API Exception
 *
 * @author Jeroen Desloovere <jeroen@siesqo.be>
 */
class YelpException extends Exception
{	
}
