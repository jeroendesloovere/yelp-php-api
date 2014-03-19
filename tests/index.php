<?php

/**
 * Yelp API test
 *
 * @author Jeroen Desloovere <jeroen@siesqo.be>
 */

// require
require_once('../src/JeroenDesloovere/Yelp/Yelp.php');

// define variables
$consumerKey = '';
$consumerSecret = '';
$token = '';
$tokenSecret = '';

// define API
$API = new Yelp($consumerKey, $consumerSecret, $token, $tokenSecret);

// search for business
$results = $API->searchForBusiness('the-waterboy-sacramento');

// search for term (at a location)
$results = $API->searchForTerm('taco', 'sf');

// show results
print_r($results);
