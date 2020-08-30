<?php

/**
 * ZainCash PHP SDK
 * @version 1.0.0
 *
 * @package ZainCashSDK
 *
 * @author Alhasan Ahmed Al-Nasiry <alhasan.nasiry@gmail.com>
 */

require_once __DIR__ . '/vendor/autoload.php'; // Autoload files using Composer autoload

use AlhasanIQ\ZainCashSDK;
use Dotenv\Dotenv;

$dotenv = new Dotenv(__DIR__);
$dotenv->load();

try {
  if (isset($_GET['token'])) {

    $zc = new ZainCashSDK([
      'msisdn' => $_ENV['ZC_MSISDN'],
      'secret' => $_ENV['ZC_SECRET'],
      'merchantid' => $_ENV['ZC_MERCHANTID'],
      'production_cred' => ($_ENV['ZC_ENV_PRODUCTION'] === 'true'),
      'language' => 'en', // 'en' or 'ar'
      'redirection_url' => 'https://example.com/test-redirect.php'
    ]);
    $result = $zc->decode($_GET['token']);
    if ($result['status'] == 'success') {
      // do something (ex: show sucess message)
    } else {
      // do something (ex: show errors)
    }
  }
} catch (Exception $e) {
  echo $e->getMessage();
}
