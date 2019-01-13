<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 1/7/2019
 * Time: 4:27 PM
 */

require_once "bootstrap.php";
require_once RESOURCE_PATH . "/VoucherifyClientExt.class.php";


use Voucherify\VoucherifyClient;
use Voucherify\ClientException;

$apiID  = "aa3ad4c6-4859-44e9-b6cd-3769a4431498";
$apiKey = "2f91d6db-5c84-4682-aa4e-f461ca3cde38";

$client = new VoucherifyClientExt($apiID, $apiKey);

$result = $client->distributions->createPublications([
    "campaign" =>[
                   "name" =>  "Referral Reward - 15% Discount",
                   "count" => 1
                ],
    "customer" => [
                    "source_id" => "rajeev-user@voucherify.io",
                    "email" => "rajeev.pillai@voucherify.io",
                    "name"  => "Rajeev Pillai-1"
                  ]
]);

var_dump($result);

/*
 * curl -X POST \
  -H "X-App-Id: c70a6f00-cf91-4756-9df5-47628850002b" \
  -H "X-App-Token: 3266b9f8-e246-4f79-bdf0-833929b1380c" \
  -H "Content-Type: application/json" \
  -d '{
    "campaign": {
      "name": "100k-test",
      "count": 3
    },
    "customer": {
      "source_id": "test-user@voucherify.io",
      "email": "test-user@voucherify.io",
      "name": "Test User"
    },
    "metadata": {
      "test": true,
      "provider": "Shop Admin"
    }
  }' \
  https://api.voucherify.io/v1/publications
 */