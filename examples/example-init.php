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
  $zc = new ZainCashSDK([
    'msisdn' => $_ENV['ZC_MSISDN'],
    'secret' => $_ENV['ZC_SECRET'],
    'merchantid' => $_ENV['ZC_MERCHANTID'],
    'production_cred' => ($_ENV['ZC_ENV_PRODUCTION'] === 'true'),
    'language' => 'en', // 'en' or 'ar'
    'redirection_url' => 'https://example.com/redirect.php'

  ]);
  $zc->charge(
    1000,
    'Something bad and shady',
    'Order_00001'
  );
} catch (Exception $e) {
  echo $e->getMessage();
}
