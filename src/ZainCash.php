<?php

/**
 * ZainCash PHP SDK
 * @version 1.0.0
 *
 * @package ZainCashSDK
 *
 * @author Alhasan Ahmed Al-Nasiry <alhasan.nasiry@gmail.com>
 */

namespace AlhasanIQ;

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use \Firebase\JWT\JWT;
use \GuzzleHttp\Client as HTTPClient;

use \Exception;
use \DateTime;

class ZainCashSDK
{

  /** 
   * @var string Wallet Number (ZainCash IT will provide it)
   * 
   * Example: 9647800000000 
   */
  private $msisdn = "";

  /** 
   * @var string Secret (ZainCash IT will provide it)
   * 
   * Example: 1zaza5a444a6e8323asd123asd123sfeasdase12312davwqf123123xc2ego
   */
  private  $secret = '';

  /** 
   * @var string Merchant ID (ZainCash IT will provide it)
   * 
   * Example: "57f3a0635a126a48ee912866"
   */
  private  $merchantid = '';

  /** 
   * @var bool Credentials Environment: Test credentials or Production credentials (true=production , false=test)
   */
  private $production_cred = false;

  /** 
   * @var string Language: 'ar'=Arabic / 'en'=english
   */
  private  $language = 'ar';
  //--------------------------------------------

  /** 
   * @var string Redirection URL (capture endpoint), After a successful or failed transaction, the user will get redirected to this url
   *
   * Example: 'https://example.com/redirect.php'
   * 
   * or  'https://example.com/order/redirect'
   * 
   * NOTE api will return the token with a GET parameter (token): https://example.com/redirect.php?token=XXXXXXXXXXXXXX
   * 
   */
  private $redirection_url = '';




  //--------------------------------------------

  /**
   * Transaction Endpoints
   * will be set automatically after credentials are set in __construct
   */
  private  $transactionInitURL = '';
  private  $transactionRedirectURL = '';

  /**
   * Status flag to check if should proceed to setTransactionEndpoints() / initiatingTransaction()
   * will be set to true in setCredentials()
   */
  private $gotCredentials = false;

  /**
   * @param array $credentials credentials array (see above)
   * 
   *  $creds[
   *    'msisdn' => '',
   *    'secret' => '',
   *    'merchantid' => '',
   *    'production_cred' => '',
   *    'language' => '',
   *    'redirection_url' => ''
   * ]
   * 
   */
  public function __construct(array $credentials)
  {
    if ($this->verifyCredentials($credentials)) {
      $this->setCredentials($credentials);
      $this->setTransactionEndpoints();
    }
  }

  /**
   * Verifys that credentials are set with correct types
   * @param array $creds
   * @return boolean when all credentials are set with the correct type.
   */
  private function verifyCredentials(array $creds)
  {

    $validation = [
      'msisdn' => [
        'string'
      ],
      'secret' => [
        'string'
      ],
      'merchantid' => [
        'string'
      ],
      'production_cred' => [
        'bool'
      ],
      'language' => [
        'string',
        'language'
      ],
      'redirection_url' => [
        'string'
      ]
    ];

    foreach ($validation as $field => $rules) {
      if (!isset($creds[$field])) {
        throw new Exception("ERROR: " . $field . " is not set.", 1);
        return false;
      }
      foreach ($rules as $rule) {
        switch ($rule) {
          case 'string':
            if (!is_string($creds[$field])) {
              throw new Exception("ERROR: " . $field . " has an invalid type, it should be a string.", 1);
              return false;
            }
            break;

          case 'boolean':
            if (!is_bool($creds[$field])) {
              throw new Exception("ERROR: " . $field . " has an invalid type, it should be a boolean.", 1);
              return false;
            }
            break;

          case 'bool':
            if (!is_bool($creds[$field])) {
              throw new Exception("ERROR: " . $field . " has an invalid type, it should be a boolean.", 1);
              return false;
            }
            break;

          case 'language':
            if (!in_array($creds[$field], ['ar', 'en'])) {
              throw new Exception("ERROR: " . $field . " has an invalid type, it should be a string with value of 'ar' or 'en'.", 1);
              return false;
            }
            break;
          default:
            throw new Exception("Unknown validation rule: " . $rule, 1);
            break;
        }
      }
    }
    return true;
  }

  /**
   * Sets credentials to the SDK object instance.

   * $creds[
   *    'msisdn' => '',
   *    'secret' => '',
   *    'merchantid' => '',
   *    'production_cred' => '',
   *    'language' => '',
   *    'redirection_url' => ''
   * ]
   * 
   * @param array $creds credentials array (see above)
   * @return void
   */
  private function setCredentials(array $creds)
  {
    $this->gotCredentials = true;

    $this->msisdn = $creds['msisdn'];
    $this->secret = $creds['secret'];
    $this->merchantid = $creds['merchantid'];
    $this->production_cred = $creds['production_cred'];
    $this->language = $creds['language'];
    $this->redirection_url = $creds['redirection_url'];
  }

  /**
   * Sets $transactionInitURL , $transactionRedirectURL according to $production_cred value
   */
  private function setTransactionEndpoints()
  {
    $this->checkCredentials();

    if ($this->production_cred) {
      $this->transactionInitURL = 'https://api.zaincash.iq/transaction/init';
      $this->transactionRedirectURL = 'https://api.zaincash.iq/transaction/pay?id=';
    } else {
      $this->transactionInitURL = 'https://test.zaincash.iq/transaction/init';
      $this->transactionRedirectURL = 'https://test.zaincash.iq/transaction/pay?id=';
    }
  }

  /**
   * Checks if the instace has the needed credentials
   * @return boolean
   */
  private function checkCredentials()
  {
    if ($this->gotCredentials === false) throw new Exception("ERROR: Credentials not provided", 1);
  }

  /**
   * @param integer $amount amount of money in Iraqi Dinars
   * 
   * @param string $service_type a Merchant defined string to describe the transaction
   *        ex: 'Product purchase', 'Subscription fees', 'Hosting fees'
   * 
   * @param string $order_id Custom Identifier for the transaction. MAX:512 chars, a Merchant defined string to label the transaction
   *                ex: 'Bill_1234567890' , 'Receipt_004321'
   */
  public function charge(int $amount, string $service_type, string $order_id)
  {
    //encodes data to JWt token
    $jwt_token = $this->encode($amount, $service_type, $order_id);

    //prepares JWT and other data for http
    $http_context = $this->prepareHttpRequest($jwt_token);

    //sends http request
    $http_response = $this->sendHttpRequest($http_context);

    //handles http response and return redirection url
    $redirect_url = $this->handleHttpResponse($http_response);

    //redirects to redirection url
    $this->redirect($redirect_url);
  }

  /**
   * Encodes the data to JWT
   * @param integer $amount amount of money in Iraqi Dinars
   * @param string $service_type a Merchant defined string to describe the transaction
   *        ex: 'Product purchase', 'Subscription fees', 'Hosting fees'
   * @param string $order_id Custom Identifier for the transaction. MAX:512 chars, a Merchant defined string to label the transaction
   *                ex: 'Bill_1234567890' , 'Receipt_004321'
   * @return string $token (JWT) 
   */
  private function encode(int $amount, string $service_type, string $order_id)
  {
    $now = new DateTime();
    $payload = [
      'amount'  => $amount,
      'serviceType'  => $service_type,
      'msisdn'  => $this->msisdn,
      'orderId'  => $order_id,
      'redirectUrl'  => $this->redirection_url,
      'iat'  => $now->getTimestamp(),
      'exp'  => $now->getTimestamp() + 60 * 60 * 4
    ];

    $token = JWT::encode(
      $payload,      //Data to be encoded in the JWT
      $this->secret,
      'HS256'
    );

    return $token;
  }

  /**
   * @param string $token JWT token from redirection $_GET
   * @return array $result
   */
  public function decode(string $token)
  {
    $result = (array) JWT::decode($token, $this->secret, array('HS256'));
    /*
        Example of $result
        array(5) {
          ["status"]=>
          string(7) "success"
          ["orderid"]=>
          string(9) "Bill12345"
          ["id"]=>
          string(24) "58630f0f90c6362288da08cf"
          ["iat"]=>
          int(1483018052)
          ["exp"]=>
          int(1483032452)
        }
      */

    return $result;
  }
  /**
   * @param string $token, JWT token from encode()
   * @return array $requestBody
   */
  private function  prepareHttpRequest($token)
  {

    $requestBody = [
      'form_params' => [ //this option automatically sets header: 'application/x-www-form-urlencoded'
        'token' => urlencode($token), // JWT Token
        'merchantId' => $this->merchantid,
        'lang' => $this->language,
      ]
    ];

    return $requestBody;
  }


  /**
   * sends http request and return the response body string
   * @param HTTPRequest $request
   * @return string JSON response body
   */
  private function sendHttpRequest(array $requestBody)
  {
    $client = new HTTPClient();

    $response = $client->request('POST', $this->transactionInitURL, $requestBody);

    if ($response === false || $response === null) throw new Exception("ERROR: Failing to contact api, communication layer issue.");

    return $response->getBody();
  }

  /**
   * Handles http response and return the redirection url
   * 
   * @param string $response JSON 
   * 
   * @return string URL redirection-url
   */
  private function handleHttpResponse(string $response)
  {
    $array = json_decode($response, true);

    if (isset($array['err'])) throw new Exception("ERROR: Transaction request failed (" . $array['err']['msg'] . ")");

    $transaction_id = $array['id'];
    $newurl = $this->transactionRedirectURL . $transaction_id;

    return $newurl;
  }

  /**
   * Redirects to the api callback url
   * 
   * NOTE This is a very dirty and intrusive approach, kindly use with caution
   * @param string $url
   */
  private function redirect($url)
  {
    header('Location: ' . $url);
    exit();
  }
}
