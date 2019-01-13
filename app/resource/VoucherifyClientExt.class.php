<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 1/12/2019
 * Time: 9:00 PM
 */

require_once RESOURCE_PATH . "/VoucherifyDistExt.class.php";

use Voucherify\VoucherifyClient;
use Voucherify\ApiClient;


class VoucherifyClientExt extends VoucherifyClient
{
    /**
     * @var \Voucherify\ApiClient
     */
    private $client;

    public function __construct($apiId, $apiKey, $apiVersion = null, $apiUrl = null)
    {
        parent::__construct($apiId, $apiKey, $apiVersion, $apiUrl);

        $this->client = new ApiClient($apiId, $apiKey, $apiVersion, $apiUrl);

        $this->distributions = new VoucherifyDistExt($this->client);
    }
}